<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Auth;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp;
use Mail;


class LoginController extends Controller

	{
    
    
        public function cerrarSesion()
        {
            Auth::logout();
            $resultado = ['status' => 'success', 'redirect' => '/'];
				return response()->json(
					$resultado, 200);
        } 
    
	public function iniciarSesion(Request $request)
		{

		// validamos

		$validator = Validator::make($request->json()->all() , ['email' => 'required|max:255|email', 'password' => 'required|max:255|string', ]);
		if ($validator->fails())
			{
			$resultado = ['status' => 'fail', 'message' => 'Debe propocionar un email y una contraseña'];
			return response()->json($resultado, 400);
			}

		// responder

		if (Auth::attempt(['email' => $request->json('email') , 'password' => $request->json('password') ]))
			{
			$user = Auth::user();
			if ($user->estado != "activo")
				{
				Auth::logout();
				$resultado = ['status' => 'fail', 'message' => 'El usuario no se encuentra activo'];
				return response()->json($resultado
				 , 401);
				}
			  else
				{
				$resultado = ['status' => 'success', 'redirect' => 'index'];
				return response()->json($resultado , 200);
				}
			}
		  else
			{
			$resultado = ['status' => 'error', 'message' => 'Email y/o contraseña incorrectos'];
			return response()->json($resultado, 400);
			}
		}

	public function activarCuenta($email, $token)
		{
		$user = \App\User::where('email', $email)->first();
		if ($user == null)
			{
			return response()->json(['status' => 'fail', 'message' => 'no hay coincidencias'], 401);
			}
		elseif ($user->estado == "bloqueado" || $user->estado == "activo")
			{
			return response()->json(['status' => 'fail', 'message' => 'autorizacion no valida'], 401);
			}
		elseif ($user->token_activacion != $token)
			{
			return response()->json(['status' => 'fail', 'message' => 'no hay coincidencias'], 401);
			}
		  else
			{
			$user->estado = "activo";
			$user->save();
			return redirect('/login');
			}

		// responder

		if (Auth::attempt(['email' => $request->json('email') , 'password' => $request->json('password') ]))
			{
			$user = Auth::user();
			if ($user != "activo")
				{
				Auth::logout();
				$resultado = ['status' => 'fail', 'message' => 'El usuario no se encuentra activo'];
				return response()->json(array(
					$resultado,
					$user
				) , 200);
				}
			  else
				{
				$resultado = ['status' => 'success', 'redirect' => 'index'];
				return response()->json(array(
					$resultado,
					$user
				) , 200);
				}
			}
		  else
			{
			$resultado = ['status' => 'error', 'message' => 'Email y/o contraseña incorrectos'];
			return response()->json($resultado, 400);
			}
		}

	public function crearCuenta(Request $request)
		{

		// validamos entrada

		$validator = Validator::make($request->json()->all() , ['email' => 'required|max:255|email', 'password' => 'required|max:255|string', 'tipo_documento' => 'required|max:255|string', 'pais_documento' => 'required|max:255|string', 'numero_documento' => 'required|max:255|string', ]);
		if ($validator->fails())
			{
			$resultado = ['status' => 'fail', 'message' => 'Faltan datos para procesar la petición'];
			return response()->json($resultado, 400);
			}

		// obtenemos los datos de api.sapientia.tk/v1/alumnos
                $alumnos = file_get_contents('http://api.sapientia.tk/v1/alumnos?tipo_documento=' . $request->json('tipo_documento') . '&numero_documento=' . $request->json('numero_documento') . '&pais_documento=' . $request->json('pais_documento') . '&email=' . $request->json('email'));
                $alumnos = json_decode($alumnos,true);

		// verificamos que haya algun alumno
		if (count($alumnos["data"]["alumnos"]) == 0)
			{
			$resultado = ['status' => 'fail', 'message' => 'Los datos que ingreso no corresponden a un alumno'];
			return response()->json($resultado, 401);
			}

		// verificamos que el alumno ya no exista

		$user = \App\User::where('email', $request->json('email'))->first();
		if ($user != null)
			{
			$resultado = ['status' => 'fail', 'message' => 'El alumno ya existe en el sistema'];
			return response()->json($resultado, 401);
			}

		$user = new \App\User;
		try
			{

			// agregamos el usuario al sistema

			$user->email = $request->json('email');
			$user->numero_documento = $request->json('numero_documento');
			$user->tipo_documento = $request->json('tipo_documento');
			$user->pais_documento = $request->json('pais_documento');
			$user->nombre = $alumnos["data"]["alumnos"][0]["nombre"];
			$user->password = bcrypt($request->json('password'));
			$user->tipo_usuario = 'alumno';
			$user->estado = 'inactivo';
			$user->token_activacion = str_random(10);
			$user->save();

			// enviamos el mail de confirmacion

			$url = asset('activar/' . $user->email . '/' . $user->token_activacion);
			$nombre = $alumnos["data"]["alumnos"][0]["nombre"];
			$email = $request->json('email');
			$datos = ['url' => $url, 'nombre' => $nombre];
			Mail::send('emails.activacion', $datos,
			function ($message) use($email)
				{
				$message->from('no-reply@cecytuca.tk', 'Activar cuenta');
				$message->to($email);
				});
			}

		catch(Exception $e)
			{
			$resultado = ['status' => 'fail', 'message' => 'Error interno'];
			return response()->json($resultado, 200);
			}

		$resultado = ['status' => 'success', 'message' => 'Hemos enviado un email a ' . $user->email . ' con un codigo de activacion.'];
		return response()->json($resultado, 200);
		}
	}
