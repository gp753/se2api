<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

use \App\Horario;

use Validator;

use DB;

use Carbon;


class HorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'agregarHorarioPersonalizado',
            'agregarHorarioPersonalizadoDetalle',
            'listarHorariosPersonalizados',
            'mostrarHorarioPersonalizado',
            
            ]]);
    }
    public function agregarHorarioPersonalizado(Request $request){

        //solo alumnos pueden crear horarios personalizados
        if (Auth::User()->tipo_usuario=!'alumno'){
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 401);
        }
        
        //validamos
        $validator = Validator::make($request->json()->all(), [
                'titulo' => 'required|max:255|string',
                'semestre' => 'required|digits:1',
                'ano' => 'required|digits:4',
                'carrera' => 'required|max:255|string',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
        //insertamos
        $horario = new \App\Horario;
        $horario->titulo = $request->json('titulo');
        $horario->semestre = $request->json('semestre');
        $horario->ano = $request->json('ano');
        $horario->user_id = Auth::User()->id;
        $horario->carrera = $request->json('carrera');
        $horario->save();
        
        //devemos devolver lista de materias, y horario
        $lista = $this->listarMateriasHorarioPersonalizado($horario->id, $horario->semestre, $horario->ano, $horario->carrera);
        
        
        $resultado = ['status' => 'success',
                          'data' => array('horario'=>$horario, 'materias'=>json_decode($lista))];
        
        
        
                return response()->json($resultado, 200);
        
        
    }
    //borrarHorarioPersonalizado
    public function borrarHorarioPersonalizado($id){
        $horario = \App\Horario::find($id);
        if ($horario==null){
            $resultado = ['status' => 'fail',
                              'message' => 'horario no existe'];
                return response()->json($resultado, 401);
        }
        
        if ($horario->user_id != Auth::User()->id){
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 400);
        }
        
        $horario->delete();
        $resultado = ['status' => 'sucess',
                              'message' => 'eliminado'];
                return response()->json($resultado, 200);
    }
    //agregarHorarioPersonalizadoDetalle
    public function agregarHorarioPersonalizadoDetalle(Request $request, $id){
        set_time_limit(120);
        
        
        $horario = \App\Horario::find($id);
        //verificamos si id existe
        if ($horario==null){
            $resultado = ['status' => 'fail',
                              'message' => 'horario no existe'];
                return response()->json($resultado, 401);
        }
        
        //verificamos que horario pertenezca a usuario
        
        if ($horario->user_id != Auth::User()->id){
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 401);
        }
        
        //validamos
        $validator = Validator::make($request->json()->all(), [
                'codigo_materia' => 'required|max:255|string',
                'ano_actual' => 'required|max:255|string',
                'semestre_actual' => 'required|max:255|string',
                'carrera' => 'required|max:255|string',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
        
        
        //$horarios = json_decode(,true);
        $direccion = 'http://api.sapientia.tk/v1/horarios?semestre='. $request->json('semestre_actual') .'&ano='. $request->json('ano_actual') .'&carrera=' . $request->json('carrera');
        
        $consulta = file_get_contents($direccion);   
        
        $horarios = json_decode($consulta,true);
        
        

        
        $horarios = $horarios['data']['horarios'];
        
        //insertamos en forma recursiva
        foreach ($horarios as $horario) {
            if ($horario["codigo_materia"]==$request->json('codigo_materia')) {
                $horario_detalle = new \App\Horario_Detalle;
                $horario_detalle->horario_id = $id;
                $horario_detalle->codigo_materia = $request->json('codigo_materia');
                $horario_detalle->nombre_materia = $horario["nombre_materia"];
                $horario_detalle->semestre = $horario['semestre'];
                $horario_detalle->inicio = $horario['inicio'];
                $horario_detalle->fin = $horario['fin'];
                $horario_detalle->dia = $horario['dia'];
                $horario_detalle->profesor = $horario['profesor'];
                $horario_detalle->aula = $horario['aula'];
                $horario_detalle->save();
                
            }
            
        }
        
        $horario_detalle = \App\Horario_Detalle::where('horario_id',$id)->where('deleted_at',null)->orderBy('inicio','asc')->get();
         //devemos devolver lista de materias, y horario
        $lista = $this->listarMateriasHorarioPersonalizado($id,  $request->json('semestre_actual'),  $request->json('ano_actual'),  $request->json('carrera'));
        
        $resultado = ['status' => 'success',
                              'data' => array('horario_detalle'=>$horario_detalle, 'lista'=>json_decode($lista))];
                return response()->json($resultado, 200);
        
         
         
        
    }
    //borrarHorarioPersonalizadoDetalle
    public function borrarHorarioPersonalizadoDetalle($id){
        $horario_detalle = \App\Horario_Detalle::find($id);
        
        if ($horario_detalle==null){
            $resultado = ['status' => 'fail',
                              'message' => 'horario detalle no existe'];
                return response()->json($resultado, 401);
        }
        
        if ($horario_detalle->horario->user_id != Auth::User()->id){
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 400);
        }
        
        $hora = Carbon\Carbon::now();
        $hora = $hora->toDateTimeString();
        
        //eliminamos
        DB::table('horario__detalles')
            ->where('horario_id', $horario_detalle->horario_id)
            ->where('codigo_materia', $horario_detalle->codigo_materia)    
            ->update(['deleted_at' => $hora]);
        
             
        
        
        $horario = \App\Horario::find($horario_detalle->horario_id);
        $horario_id = $horario->id;
        
        $horario_detalle = \App\Horario_Detalle::where('horario_id',$horario_detalle->horario_id)->where('deleted_at',null)->orderBy('inicio','asc')->get();
         //devemos devolver lista de materias, y horario
        $lista = $this->listarMateriasHorarioPersonalizado($horario_id,  $horario->semestre,  $horario->ano,  $horario->carrera);
        //return var_dump($lista);
        
        $resultado = ['status' => 'success',
                              'data' => array('horario_detalle'=>$horario_detalle, 'lista'=>json_decode($lista))];
                return response()->json($resultado, 200);
    }
    //listarHorariosPersonalizados
    public function listarHorariosPersonalizados(){
        $horarios = \App\User::find(Auth::User()->id);
        $horarios = $horarios->horariosPersonalizados;
        $resultado = ['status' => 'success',
                              'data' => array('horarios'=>$horarios)];
                return response()->json($resultado, 200); 
    }
    //mostrarHorarioPersonalizado
    public function mostrarHorarioPersonalizado($id){
        $horario = \App\Horario::find($id);
        //verificamos si existe
        if ($horario==null) {
            $resultado = ['status' => 'fail',
                              'message' => 'no existe horario personalizado'];
                return response()->json($resultado, 400);
        }
        
        //verificamos si horario pertenece a usuario
        if ($horario->user_id!=Auth::User()->id){
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 401);
        }
        
        $detalle = $horario->detalle;
        $resultado = ['status' => 'success',
                              'data' => array('detalle'=>$detalle, 'horario'=>$horario)];
                return response()->json($resultado, 200);
        
    }

   
    
   
    
    public function listarMateriasHorarioPersonalizado ($id, $semestre, $ano, $carrera){
        $horario_personalizado = \App\Horario::find($id);
        if ($horario_personalizado==null){
            return null;
        }
        $user = \App\User::find($horario_personalizado->user_id);
        

        
        $horarios = json_decode(file_get_contents('http://api.sapientia.tk/v1/horarios?semestre='. $semestre .'&ano='. $ano .'&carrera='.$carrera),true);
        $horarios = $horarios['data']['horarios'];
        
        $aprobadas = json_decode(file_get_contents('http://api.sapientia.tk/v1/aprobadas?tipo_documento='. $user->tipo_documento .'&numero_documento='. $user->numero_documento .'&pais_documento='.$user->pais_documento),true);
        $aprobadas = $aprobadas['data']['aprobadas'];
        
        $agregadas = \App\Horario_Detalle::where('horario_id',$id)->where('deleted_at',null)->get();
        
        $salidas = array();
        
        $agregar = true;
        
        foreach ($horarios as $horario) {
            //no agregamos si ya esta aprobada
            foreach ($aprobadas as $aprobada) {
                if ($horario["codigo_materia"]==$aprobada["codigo_materia"] && $aprobada["aprobada"]=="SI"){
                    $agregar = false;
                    
                }
            }
            //no agregamos si ya esta en la lista
            foreach ($agregadas as $agregada) {
                if ($horario["codigo_materia"]==$agregada["codigo_materia"]){
                    $agregar = false;
                }
            }
            //verificamos existencia en salida
            foreach ($salidas as $salida) {
                if ($horario["codigo_materia"]==$salida["codigo_materia"]){
                    $agregar = false;
                }
            }
            
            if ($agregar==true){
                array_push($salidas, $horario);
            }
            $agregar=true;
        }
        
        return json_encode($salidas);
        

        
    }
}
