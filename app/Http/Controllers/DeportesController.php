<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Validator;
use Carbon;
use DB;

class DeportesController extends Controller
{
    //
     public function __construct()
    {
        /*$this->middleware('auth', ['only' => [
            'post_torneo',
            'put_torneo',
            'delete_torneo',

            'post_partido',
            'put_partido',
            'delete_partido',

            'post_par_tan',
            'put_par_tan',
            'delete_par_tan',

            'post_equipo',
            'put_equipo',
            'delete_equipo'
            ]]);*/
    }

    public function post_amonestacion (Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
                'par_id' => 'required|integer',
                'jug_id' => 'required|integer',
                'equ_id' => 'required|integer',
                'amonestacion' => 'required|string',
                'descripcion'=>'string'
                
            ]);
        
            if ($validator->fails()) {
                $resultado = ['message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            $amonestacion = new \App\Amonestacion;
            $amonestacion->par_id = $request->json('par_id');
            $amonestacion->jug_id = $request->json('jug_id');
            $amonestacion->equ_id = $request->json('equ_id');
            $amonestacion->amonestacion = $request->json('amonestacion');
            $amonestacion->descripcion = $request->json('descripcion');
            $amonestacion->save();

            $amonestaciones = DB::table('amonestacions')
                                    ->select('amonestacions.jug_id', 'jugadors.nombre as jugador', 'amonestacions.par_id','partidos.lugar','amonestacions.equ_id','equipos.nombre as equipo')
                                    ->leftjoin('jugadors','jugadors.id','=','amonestacions.jug_id')
                                    ->leftjoin('partidos','partidos.id','=','amonestacions.par_id')
                                    ->leftjoin('equipos','equipos.id','=','amonestacions.equ_id')
                                    ->whereNull('amonestacions.deleted_at')
                                    ->where('amonestacions.jug_id','=',$request->json('jug_id'))
                                    ->get();

             $resultado = ['data' => array('amonestaciones'=>$amonestaciones),
                            'message' => 'success'];
                return response()->json($resultado, 200);
    }

    public function get_jug_amonestacion($id)
    {
         $amonestaciones = DB::table('amonestacions')
                                    ->select('amonestacions.jug_id', 'jugadors.nombre as jugador', 'amonestacions.par_id','partidos.lugar','amonestacions.equ_id','equipos.nombre as equipo','amonestacions.amonestacion')
                                    ->leftjoin('jugadors','jugadors.id','=','amonestacions.jug_id')
                                    ->leftjoin('partidos','partidos.id','=','amonestacions.par_id')
                                    ->leftjoin('equipos','equipos.id','=','amonestacions.equ_id')
                                    ->whereNull('amonestacions.deleted_at')
                                    ->where('amonestacions.jug_id','=',$id)
                                    ->get();

             $resultado = ['data' => array('amonestaciones'=>$amonestaciones),
                            'message' => 'success'];
                return response()->json($resultado, 200);
    }
    public function get_equ_amonestacion($id) //amonestaciones por equipo
    {
         $amonestaciones = DB::table('amonestacions')
                                    ->select('amonestacions.jug_id', 'jugadors.nombre as jugador', 'amonestacions.par_id','partidos.lugar','amonestacions.equ_id','equipos.nombre as equipo','amonestacions.amonestacion')
                                    ->leftjoin('jugadors','jugadors.id','=','amonestacions.jug_id')
                                    ->leftjoin('partidos','partidos.id','=','amonestacions.par_id')
                                    ->leftjoin('equipos','equipos.id','=','amonestacions.equ_id')
                                    ->whereNull('amonestacions.deleted_at')
                                    ->where('amonestacions.equ_id','=',$id)
                                    ->get();

             $resultado = ['data' => array('amonestaciones'=>$amonestaciones),
                            'message' => 'success'];
                return response()->json($resultado, 200);
    }

    public function get_par_amonestacion($id) //amonestaciones por partido
    {
         $amonestaciones = DB::table('amonestacions')
                                    ->select('amonestacions.jug_id', 'jugadors.nombre as jugador', 'amonestacions.par_id','partidos.lugar','amonestacions.equ_id','equipos.nombre as equipo','amonestacions.amonestacion')
                                    ->leftjoin('jugadors','jugadors.id','=','amonestacions.jug_id')
                                    ->leftjoin('partidos','partidos.id','=','amonestacions.par_id')
                                    ->leftjoin('equipos','equipos.id','=','amonestacions.equ_id')
                                    ->whereNull('amonestacions.deleted_at')
                                    ->where('amonestacions.par_id','=',$id)
                                    ->get();

             $resultado = ['data' => array('amonestaciones'=>$amonestaciones),
                            'message' => 'success'];
                return response()->json($resultado, 200);
    }

    public function get_goleadores() //goleadores
    {
        $goleadores = DB::table('partido__tantos')
                        ->select('partido__tantos.jug_id','jugadors.nombre as jugador', DB::raw('sum(partido__tantos.valor) as goles'))
                        ->leftjoin('jugadors','jugadors.id','=','partido__tantos.jug_id')
                        ->groupBy('partido__tantos.jug_id','jugadors.nombre')
                        ->orderBy('goles','desc')
                        ->whereNull('partido__tantos.deleted_at')
                        ->get();
        $resultado = ['data' => array('goleadores'=>$goleadores),
                            'message' => 'success'];
        return response()->json($resultado, 200);
    }

    public function delete_amonestacion ($id) //borra la amonestacion
    {
        $amonestacion = \App\Amonestacion::find($id);
        $partido = $amonestacion->par_id;
        $amonestacion->delete();
        $amonestaciones = DB::table('amonestacions')
                                    ->select('amonestacions.jug_id', 'jugadors.nombre as jugador', 'amonestacions.par_id','partidos.lugar','amonestacions.equ_id','equipos.nombre as equipo','amonestacions.amonestacion')
                                    ->leftjoin('jugadors','jugadors.id','=','amonestacions.jug_id')
                                    ->leftjoin('partidos','partidos.id','=','amonestacions.par_id')
                                    ->leftjoin('equipos','equipos.id','=','amonestacions.equ_id')
                                    ->whereNull('amonestacions.deleted_at')
                                    ->where('amonestacions.par_id','=',$partido)
                                    ->get();

             $resultado = ['data' => array('amonestaciones'=>$amonestaciones),
                            'message' => 'success'];
                return response()->json($resultado, 200);
    }

    public function get_deporte(){
        $deporte = \App\Deporte::all();
        $resultado = ['data'=> $deporte];
        return response()->json($resultado, 200);
    }

    public function post_deporte (Request $request)
    {
    	$validator = Validator::make($request->json()->all(), [
                'descripcion' => 'required|string',
                
            ]);
        
            if ($validator->fails()) {
                $resultado = ['message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            $deporte = new \App\Deporte;
            $deporte->descripcion = $request->json('descripcion');
            $deporte->save();

            $deportes= DB::table('deportes')
                            ->select('id','descripcion')
                            ->whereNull('deleted_at')
                            ->get();
             $resultado = ['data' => array('deporte'=>$deportes),
                            'message' => 'success'];
                return response()->json($resultado, 200);
    }

    public function put_deporte ($id , Request $request)
    {
    	$deporte = \App\Deporte::find($id);

    	if ($deporte == null)
    	{
    		$resultado = ['status' => 'fail',
                              'message' => 'No existe ningun dato'];
                return response()->json($resultado, 400);  
    	}
    	else
    	{
    		 $validator = Validator::make($request->json()->all(), [
                'descripcion' => 'required|max:255|string',
                
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
            $deporte->descripcion = $request->json('descripcion');
            $deporte->save();
             $deportes= DB::table('deportes')
                            ->select('id','descripcion')
                            ->whereNull('deleted_at')
                            ->get();
             $resultado = ['data' => array('deporte'=>$deportes),
                            'message' => 'success'];
                return response()->json($resultado, 200);

    	}
    }

    public function delete_deporte ($id)
    {
		$deporte = \App\Deporte::find($id);
		$deporte->delete();
		$deportes= DB::table('deportes')
                            ->select('id','descripcion')
                            ->whereNull('deleted_at')
                            ->get();
        $resultado = ['data' => array('deporte'=>$deportes),
                            'message' => 'success'];
                return response()->json($resultado, 200);
    }

    //fin controlador deporte
    //inicio controlador equipo
    public function get_equipo ()
    {
    	
        $equipos = DB::table('equipos')
                        ->select('equipos.id', 'equipos.nombre', 'equipos.dep_id as dep_id', 'deportes.descripcion as dep_nombre')
                        ->leftjoin('deportes','deportes.id','=','equipos.dep_id')
                        ->whereNull('equipos.deleted_at')
                        ->get();

    	$resultado = ['data'=> array('equipos'=>$equipos)]; 
        return response()->json($resultado, 200);
    }

    public function get_miembros_equipo ($id)
    {
        $equipo = \App\Equipo::find($id);
        $jugadores = DB::table('jugadors')
                        ->select('jugadors.id','jugadors.nombre')
                        ->rightjoin('jugador__equipos','jugadors.id','=', 'jugador__equipos.jug_id')
                        ->where ('jugador__equipos.equ_id','=',$id,'AND','jugador__equipos.deleted_at','is','null')
                        ->whereNull('jugador__equipos.deleted_at')
                        ->groupBy('jugadors.id')
                        ->get();

        $resultado = ['data'=> $jugadores];
        return response()->json($resultado, 200);
    }

    public function post_equipo (Request $request)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }
    	 $validator = Validator::make($request->json()->all(), [
                'dep_id' => 'required|integer',
                'nombre' => 'required|string',
            ]);
        
        if ($validator->fails()) {
            $resultado = ['status' => 'fail',
                          'message' => 'Formato de entrada incorrecto'];
            return response()->json($resultado, 400);
        }

        $equipo = new \App\Equipo;
        $equipo->dep_id = $request->json('dep_id');
        $equipo->nombre = $request->json('nombre');
        $equipo->save();
       $equipos = DB::table('equipos')
                        ->select('equipos.id', 'equipos.nombre', 'equipos.dep_id as dep_id', 'deportes.descripcion as dep_nombre')
                        ->leftjoin('deportes','deportes.id','=','equipos.dep_id')
                        ->whereNull('equipos.deleted_at')
                        ->get();

        $resultado = ['data'=> array('equipos'=>$equipos)]; 
        return response()->json($resultado, 200);



    }

    public function put_equipo ($id,Request $request)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }
    	$equipo = \App\Equipo::find($id);
    	if ($equipo==null) 
    	{
            $resultado = ['status' => 'fail',
                          'message' => 'No existe ningun dato'];
            return response()->json($resultado, 400);               
	    }
	    else
	    {
	    	$validator = Validator::make($request->json()->all(), [
                'dep_id' => 'integer',
                'nombre' => 'string',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            if ($request->json('dep_id')!=null) { $equipo->dep_id = $request->json('dep_id'); }
            if ($request->json('nombre')!=null) {   $equipo->nombre = $request->json('nombre');}

            $equipo->save();
            $equipos = DB::table('equipos')
                        ->select('equipos.id', 'equipos.nombre', 'equipos.dep_id as dep_id', 'deportes.descripcion as dep_nombre')
                        ->leftjoin('deportes','deportes.id','=','equipos.dep_id')
                        ->whereNull('equipos.deleted_at')
                        ->get();

        $resultado = ['data'=> array('equipos'=>$equipos)]; 
        return response()->json($resultado, 200);


	    }
    }

    public function delete_equipo ($id)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }
    	$equipo = \App\Equipo::find($id);

    	$equipo->delete();
    	 $equipos = DB::table('equipos')
                        ->select('equipos.id', 'equipos.nombre', 'equipos.dep_id as dep_id', 'deportes.descripcion as dep_nombre')
                        ->leftjoin('deportes','deportes.id','=','equipos.dep_id')
                        ->whereNull('equipos.deleted_at')
                        ->get();

        $resultado = ['data'=> array('equipos'=>$equipos)]; 
        return response()->json($resultado, 200);
       
    }

    //fin controlador Equipo
    //inicio controlador jugador equipo

    public function get_jug_equ($id){
        $pes = DB::table('equipos')
                    ->select('equipos.id','equipos.nombre')
                    ->leftjoin('jugador__equipos','jugador__equipos.equ_id','=','equipos.id')
                    ->whereNull('jugador__equipos.deleted_at')
                    ->where('jugador__equipos.jug_id','=', $id)
                    ->get();
        $resultado = ['data' => array('equipos' => $pes)];
        return response()->json($resultado, 200);
    } //devuelve equipos de un jugador

    public function get_equ_jug($id){ 
        $pes = DB::table('jugadors')
                    ->select('jugadors.id','jugadors.nombre')
                    ->rightjoin('jugador__equipos','jugadors.id', '=', 'jugador__equipos.jug_id')
                    ->whereNull('jugador__equipos.deleted_at')
                    ->where('jugador__equipos.equ_id','=', $id)
                    ->get();
        $resultado = ['data' => array('jugadores' => $pes)];
        return response()->json($resultado, 200);
    } //devuelve jugadores de un equipo

    public function post_jug_equ ($jug_id,$equ_id)
    {
       /* $request = array('jug_id' => $jug_id, 'equ_id' =>$equ_id);
    	$validator = Validator::make($request::all(), [
                'jug_id' => 'required|integer',
                'equ_id' => 'required|integer',
            ]);
        
        if ($validator->fails()) {
            $resultado = ['status' => 'fail',
                          'message' => 'Formato de entrada incorrecto'];
            return response()->json($resultado, 400);
        }*/

        $pe = new \App\Jugador_Equipo;
        $pe->jug_id = $jug_id;
        $pe->equ_id = $equ_id;

        $pe->save();

        $pes = DB::table('jugadors')
                    ->select('jugadors.id','jugadors.nombre')
                    ->rightjoin('jugador__equipos','jugadors.id', '=', 'jugador__equipos.jug_id')
                    ->whereNull('jugador__equipos.deleted_at')
                    ->where('jugador__equipos.equ_id','=', $equ_id)
                    ->get();
        $resultado = ['data' => array('jugadores' => $pes)];
        return response()->json($resultado, 200);


	}

	public function put_jug_equ ($id, Request $request)
	{
		$pe = \App\Jugador_Equipo::find($id);

		if ($pe==null) 
		{
			
			$resultado = ['status' => 'fail',
						  'message' => 'No existe ningun dato'];
			return response()->json($resultado, 400);               
		 }
		 else 
		 {
           
  //validamos
            $validator = Validator::make($request->json()->all(), [
                'equ_id' => 'integer',
                'jug_id' => 'integer',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }


  //agregamos
            if ($request->json('jug_id')!=null) { $pe->jug_id = $request->json('jug_id'); }
            if ($request->json('equ_id')!=null) { $pe->equ_id = $request->json('equ_id'); }
            $pe->save();
            $resultado = ['status' => 'succes',
                          'data' => $pe];
			return response()->json($resultado, 200);

        } 
	}

	public function delete_jug_equ($jug_id , $equ_id)
	{    
        $id = DB::table('jugador__equipos')
                    ->select('id')
                    ->whereNull('deleted_at')
                    ->where('jug_id', '=', $jug_id, 'AND', 'equ_id', '=', $equ_id)
                    ->orderby('id')
                    ->limit(1)
                    ->get();
		$pe = \App\Jugador_Equipo::find($id[0]->id);

		$pe->delete();

		$pes = DB::table('jugadors')
                    ->select('jugadors.id','jugadors.nombre')
                    ->rightjoin('jugador__equipos','jugadors.id', '=', 'jugador__equipos.jug_id')
                    ->whereNull('jugador__equipos.deleted_at')
                    ->where('jugador__equipos.equ_id','=', $equ_id)
                    ->get();
        $resultado = ['data' => array('jugadores' => $pes)];
        return response()->json($resultado, 200);
	}
	//fin jugador equipo
	//inicio jugador
	 public function get_jugador ()
    {

    	$jugadores = DB::table('jugadors')
                            ->select('id','nombre','usu_id')
                            ->whereNull('jugadors.deleted_at')
                            ->get();
         $resultado = ['data' => array('jugadores'=>$jugadores)];
            return response()->json($resultado, 200);

    }

    public function get_equipo_jugador($id)
    {
        $jugador = \App\Jugador::find($id);
        $ideq = \App\Jugador_Equipo::select('equ_id')->where('jug_id','=',$id)->get();

        $equipo = DB::table('equipos')
                            ->select('equipos.id', 'nombre')
                            ->rightJoin('jugador__equipos','equipos.id','=', 'jugador__equipos.equ_id')
                            ->where('jugador__equipos.jug_id','=',$id, 'AND', 'jugador__equipos.deleted_at','is','null')
                            ->whereNull('jugador__equipos.deleted_at')
                            ->get();
        $resultado = ['data'=> $equipo];
        return response()->json($resultado, 200);
    }

    public function post_jugador (Request $request)
    {
    	//validamos
            $validator = Validator::make($request->json()->all(), [
                'usu_id' => 'integer',
                'nombre' =>'required|string',
            ]);
        //return json_encode($request->json()->all());
        
            if ($validator->fails()) {
                $resultado = ['message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            $jugador =  new \App\Jugador;
            if ($request->json('usu_id')!= null){ $jugador->usu_id = $request->json('usu_id');};
            $jugador->nombre = $request->json('nombre');
            $jugador->save();
            $jugadores = DB::table('jugadors')
                            ->select('id','nombre', 'usu_id')
                            ->where('jugadors.deleted_at')
                            ->get();
            $resultado = ['data' => array('jugadores'=>$jugadores)];
            return response()->json($resultado, 200);
    }

    public function put_jugador ($id, Request $request)
    {
    	$jugador = \App\Jugador::find($id);

    	if($jugador == null)
    	{

    		$resultado = ['status' => 'fail',
                              'message' => 'No existe ningun dato'];
            return response()->json($resultado, 400);               
         }
         else
         {
         	$validator = Validator::make($request->json()->all(), [
                'usu_id' => 'integer',
                'nombre' => 'string',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            if($request->json('usu_id') != null){ $jugador->usu_id = $request->json('usu_id');};
            if($request->json('nombre') != null){ $jugador->nombre = $request->json('nombre');};
            $jugador->save();
            $jugadores = DB::table('jugadors')
                            ->select('id','nombre', 'usu_id')
                            ->where('jugadors.deleted_at')
                            ->get();
            $resultado = ['data' => array('jugadores'=>$jugadores)];
            return response()->json($resultado, 200);

                
         }

    }

    public function delete_jugador ($id)
    {
    	$jugador = \App\Jugador::find($id);

    	$jugador->delete();
    	 $jugadores = DB::table('jugadors')
                            ->select('id','nombre', 'usu_id')
                            ->whereNull('jugadors.deleted_at')
                            ->get();
            $resultado = ['data' => array('jugadores'=>$jugadores)];
            return response()->json($resultado, 200);
    }

    //fin controlador jugador
    //inicio partido equipo
    public function get_par_equ($id){
        $pe = \App\Partido_Equipo::find($id);
        $resultado = ['data'=> $pe];
        return response()->json($resultado, 200);
    }

    public function post_par_equ ($par_id, $equ_id)
    {
    	/*$validator = Validator::make($request->json()->all(), [
                'equ_id' => 'required|integer',
                'part_id' => 'required|integer',
            ]);
        
        if ($validator->fails()) {
            $resultado = ['status' => 'fail',
                          'message' => 'Formato de entrada incorrecto'];
            return response()->json($resultado, 400);
        }*/

        $pe = new \App\Partido_Equipo;
        //$pe->equ_id = $request->json('equ_id');
        //$pe->part_id = $request->json('part_id');

        $pe->equ_id = $equ_id;
        $pe->part_id = $par_id;
        $pe->save();

        $pes = DB::select('select partido__equipos.id,partido__equipos.equ_id, equipos.nombre as equipo, partido__equipos.part_id
                            from partido__equipos
                            left join equipos
                            on partido__equipos.equ_id = equipos.id
                            where partido__equipos.part_id ='.$par_id .' and partido__equipos.deleted_at is null
');
        

        $resultado = ['data' => array('equipos'=>$pes)];
        return response()->json($resultado, 200);


	}

	public function put_par_equ ($id, Request $request)
	{
		$pe = \App\Partido_Equipo::find($id);

		if ($pe==null) 
		{
			
			$resultado = ['status' => 'fail',
						  'message' => 'No existe ningun dato'];
			return response()->json($resultado, 400);               
		 }
		 else 
		 {
           
  //validamos
            $validator = Validator::make($request->json()->all(), [
                'equ_id' => 'integer',
                'part_id' => 'integer',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }


  //agregamos
            if ($request->json('part_id')!=null) { $pe->part_id = $request->json('part_id'); }
            if ($request->json('equ_id')!=null) { $pe->equ_id = $request->json('equ_id'); }
            $pe->save();
            $resultado = ['status' => 'succes',
                          'data' => $pe];
			return response()->json($resultado, 200);

        } 
	}

	public function delete_par_equ($id)
	{
		$pe = \App\Partido_Equipo::find($id);

		$pe->delete();

		$pes = DB::select('select partido__equipos.id,partido__equipos.equ_id, equipos.nombre as equipo, partido__equipos.part_id
                            from partido__equipos
                            left join equipos
                            on partido__equipos.equ_id = equipos.id
                            where partido__equipos.part_id ='.$par_id .' and partido__equipos.deleted_at is null
');
        

        $resultado = ['data' => array('equipos'=>$pes)];
        return response()->json($resultado, 200);
	}
	//fin controlador partido equipo
	//inicio partido tanto
	public function get_par_tan($id){
        $partido_tanto = \App\Partido_Tanto::find($id);
        $resultado = ['data'=> $partido_tanto];
        return response()->json($resultado, 200);
    }

    

    public function post_par_tan (Request $request)
    {
    	//validamos
       /* if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }*/
            $validator = Validator::make($request->json()->all(), [
                'par_id' => 'required|integer',
                'jug_id' => 'required|integer',
                'equ_id' => 'required|integer',
                'valor' => 'required|integer',
                'detalle' => 'string',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            $tanto = new \App\Partido_Tanto;
            $tanto->par_id = $request->json('par_id');
            $tanto->equ_id = $request->json('equ_id');
            $tanto->jug_id = $request->json('jug_id');
            $tanto->valor=$request->json('valor');
            $tanto->detalle = $request->json('detalle');

            $tanto->save();

            $valor_equipo1 = DB::select('select sum(valor) as total
                                        from partido__tantos
                                        where par_id ='.$tanto->par_id.'  and equ_id = '.$tanto->equ_id.' and deleted_at is null');
            $id_equipo2 = DB::select('  select equ_id
                                        from partido__equipos
                                        where partido__equipos.equ_id != '.$tanto->equ_id.' and partido__equipos.part_id = '.$tanto->par_id.' and deleted_at is null
                                        limit 1');
            

           // return response()->json($id_equipo2[0]->equ_id, 200);

            $valor_equipo2 = DB::select('select sum(valor) as total
                                        from partido__tantos
                                        where par_id ='.$tanto->par_id.'  and equ_id = '. $id_equipo2[0]->equ_id .' and deleted_at is null');
            if($valor_equipo2 > $valor_equipo1)
            {
                $win_id = $id_equipo2[0]->equ_id;
                $los_id = $tanto->equ_id;
            }
            else
            {
                $win_id = $tanto->equ_id;
                $los_id = $id_equipo2[0]->equ_id;
            }
            $victoria = \App\Partido::find($tanto->par_id);
            $victoria->win_id = $win_id;
            $victoria->los_id = $los_id;
            $victoria->save();

            $partido = DB::table('jugadors')
                        ->select('jugadors.id as jug_id', 'jugadors.nombre', 'partido__tantos.detalle','partido__tantos.valor', 'partidos.dep_id as dep_id','deportes.descripcion as deporte')
                        ->rightjoin('partido__tantos', 'partido__tantos.jug_id', '=', 'jugadors.id')
                        ->leftjoin('partidos','partidos.id', '=', 'partido__tantos.par_id')
                        ->leftjoin('deportes','deportes.id','=','partidos.dep_id')
                        ->where('partidos.id','=', $tanto->par_id)
                        ->whereNull('partido__tantos.deleted_at')
                       ->get();
        $resultado = ['data'=> array('resultados'=> $partido)];
        return response()->json($resultado, 200);
    }

    public function put_par_tan ($id, Request $request)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }
    	$tanto = \App\Partido_Tanto::find($id);

    	  if ($tanto==null) {
                $resultado = ['status' => 'fail',
                              'message' => 'No existe ningun dato'];
                return response()->json($resultado, 400);               
             }
             else {
           
  //validamos
            $validator = Validator::make($request->json()->all(), [
                'par_id' => 'integer',
                'jug_id' => 'integer',
                'equ_id' => 'integer',
                'valor' => 'integer',
                'detalle' => 'string',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            if($request->json('par_id') != null) {  $tanto->par_id = $request->json('par_id');}
            if($request->json('equ_id') != null) {  $tanto->equ_id = $request->json('equ_id');}
            if($request->json('jug_id') != null) {  $tanto->jug_id = $request->json('jug_id');}
            if($request->json('valor') != null) {  $tanto->valor = $request->json('valor');}
            if($request->json('detalle') != null) {  $tanto->detalle = $request->json('detalle');}

            $tanto->save();
            $resultado = ['status' => 'Success',
                          'data' => $tanto];
			return response()->json($resultado, 200);


        }
    }

    public function delete_par_tan($id)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }
    	$tanto = \App\Partido_Tanto::find($id);

    	$tanto->delete();
    	$resultado = ['status' => 'success',
                          'data' => $tanto];
		return response()->json($resultado, 200);
    }

    //fin partido tanto
    //inicio partido
    public function get_partido($id){
        $partido = \App\Partido::find($id);
        $resultado = ['data'=> $partido];
        return response()->json($resultado, 200);
    }

    public function get_tantos_partido($id)
    {
        $partido = DB::table('jugadors')
                        ->select('jugadors.id as jug_id', 'jugadors.nombre', 'partido__tantos.detalle','partido__tantos.valor', 'partidos.dep_id as dep_id','deportes.descripcion as deporte')
                        ->rightjoin('partido__tantos', 'partido__tantos.jug_id', '=', 'jugadors.id')
                        ->leftjoin('partidos','partidos.id', '=', 'partido__tantos.par_id')
                        ->leftjoin('deportes','deportes.id','=','partidos.dep_id')
                        ->where('partidos.id','=', $id)
                        ->whereNull('partido__tantos.deleted_at')
                       ->get();
        $resultado = ['data'=> array('resultados'=> $partido)];
        return response()->json($resultado, 200);
    }

    public function get_tanto_equipo_partido($id)
    {
        $partido = DB::table('partido__tantos')
                        ->select('equ_id',DB::raw('sum( valor)'))
                        ->from ('partido__tantos')
                        ->groupBy('par_id','equ_id')
                        ->orderBy('par_id')
                        ->whereNUll('deleted_at')
                        ->where('par_id','=',$id)
                        ->get();
        $resultado = ['data'=> $partido];
        return response()->json($resultado, 200);

    }

     public function get_tanto_equipos_partido()
    {
        $partido = DB::table('partido__tantos')
                        ->select('equ_id',DB::raw('sum( valor)'))
                        ->from ('partido__tantos')
                        ->groupBy('par_id','equ_id')
                        ->orderBy('par_id')
                        ->whereNUll('deleted_at')
                        ->get();
        $resultado = ['data'=> $partido];
        return response()->json($resultado, 200);

    }



    public function post_partido (Request $request)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }

    	 $validator = Validator::make($request->json()->all(), [
                'dep_id' => 'required|integer',
                'tor_id' => 'required|integer',
                'fecha'=> 'required|date',
                'lugar'=>'required|string',
                'puntaje_ganador'=>'integer',
                'puntaje_derrotado' => 'integer'
            ]);
        
            if ($validator->fails()) {
                $resultado = ['message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            $partido = new \App\Partido;
            $partido->puntaje_ganador = $request->json('puntaje_ganador');
            $partido->puntaje_derrotado = $request->json('puntaje_derrotado');
            $partido->dep_id = $request->json('dep_id');
            $partido->tor_id = $request->json('tor_id');
            $partido->fecha = $request->json('fecha');
            $partido->lugar = $request->json('lugar');

            $partido->save();
             
            $partidos = DB::table('partidos')
                            ->select('partidos.id','partidos.dep_id','deportes.descripcion as deporte',
                                'partidos.tor_id','torneos.nombre as torneo', 'partidos.fecha',
                                'partidos.lugar', 'partidos.puntaje_ganador','partidos.puntaje_derrotado')
                            ->leftjoin('deportes','deportes.id', '=', 'partidos.dep_id')
                            ->leftjoin('torneos','torneos.id', '=', 'partidos.tor_id')
                            ->whereNull('partidos.deleted_at')
                            ->where('partidos.tor_id','=',$partido->tor_id)
                            ->get();
            $resultado = ['data'=> array('partidos'=>$partidos)];
        return response()->json($resultado, 200);


           /* $resultado = ['status' => 'success',
                          'data' => $partido];
            return response()->json($resultado, 200);*/
    }

    public function get_partidos_torneo_list($id)
    {
        $partidos = DB::select('
            select partidos.id, partidos.dep_id, deportes.descripcion as deporte, partidos.tor_id, torneos.nombre as torneo, partidos.fecha,
    partidos.lugar, partidos.puntaje_ganador, partidos.puntaje_derrotado,
    (select equipos.id as id_local
from equipos
left join partido__equipos
on partido__equipos.equ_id = equipos.id
where partido__equipos.part_id = partidos.id
order by id_local
limit 1),
(select equipos.nombre as equipo_local
from equipos
left join partido__equipos
on partido__equipos.equ_id = equipos.id
where partido__equipos.part_id = partidos.id
order by partido__equipos.id
limit 1),
(select equipos.id as id_visitante
from equipos
left join partido__equipos
on partido__equipos.equ_id = equipos.id
where partido__equipos.part_id = partidos.id and equipos.id !=  (select equipos.id as id_local
from equipos
left join partido__equipos
on partido__equipos.equ_id = equipos.id
where partido__equipos.part_id = partidos.id
order by id_local
limit 1)
order by equipos.id
limit 1
),
(select equipos.nombre as equipo_visitante
from equipos
left join partido__equipos
on partido__equipos.equ_id = equipos.id
where partido__equipos.part_id = partidos.id and equipos.id !=  (select equipos.id as id_local
from equipos
left join partido__equipos
on partido__equipos.equ_id = equipos.id
where partido__equipos.part_id = partidos.id
order by id_local
limit 1)
order by equipos.id
limit 1
)
from partidos
left join deportes
on deportes.id = partidos.dep_id
left join torneos
on torneos.id = partidos.tor_id
where partidos.tor_id = '.$id.' and partidos.deleted_at is null;
 ');
            $resultado = ['data'=> array('partidos'=>$partidos)];
        return response()->json($resultado, 200);
    }

    public function put_partido ($id, Request $request)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }
    	$partido = \App\Partido::find($id);

    	 if ($partido==null) {
            $resultado = ['message' => 'No existe ningun dato'];
            return response()->json($resultado, 400);               
         }
         else {
       
//validamos
	        $validator = Validator::make($request->json()->all(), [
	           'dep_id' => 'integer',
                'tor_id' => 'integer',
                'fecha'=> 'date',
                'lugar'=>'string',
                'puntaje_ganador'=>'integer',
                'puntaje_derrotado' => 'integer'
	        ]);
	    
	        if ($validator->fails()) {
	            $resultado = ['status' => 'fail',
	                          'message' => 'Formato de entrada incorrecto'];
	            return response()->json($resultado, 400);
	        }

	        if($request->json('puntaje_ganador') != null)
	        {
	        	 $partido->puntaje_ganador = $request->json('puntaje_ganador');	
	        }
	         if($request->json('puntaje_derrotado') != null)
	        {
	        	 $partido->puntaje_ganador = $request->json('puntaje_derrotado');	
	        }

	        if($request->json('dep_id') != null)
	        {
	        	$partido->dep_id = $request->json('dep_id');	
	        }

	        if($request->json('tor_id') != null)
	        {
	        	$partido->tor_id = $request->json('tor_id');
	        	
	        }

	        if($request->json('fecha') != null)
	        {
	        	$partido->fecha = $request->json('fecha');
	        }
	        if($request->json('lugar') != null)
	        {
	        	$partido->lugar = $request->json('lugar');
	        }
	        $partido->save();

            $partidos = DB::table('partidos')
                            ->select('partidos.id','partidos.dep_id','deportes.descripcion as deporte',
                                'partidos.tor_id','torneos.nombre as torneo', 'partidos.fecha',
                                'partidos.lugar', 'partidos.puntaje_ganador','partidos.puntaje_derrotado')
                            ->leftjoin('deportes','deportes.id', '=', 'partidos.dep_id')
                            ->leftjoin('torneos','torneos.id', '=', 'partidos.tor_id')
                            ->whereNull('partidos.deleted_at')
                            ->where('partidos.tor_id','=',$partido->tor_id)
                            ->get();
            $resultado = ['data'=> array('partidos'=>$partidos)];
        return response()->json($resultado, 200);
    	
     }


    }

    public function delete_partido ($id)
    {
        
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }
    	$partido = \App\partido::find($id);
        $torneo = $partido->tor_id;
    	$partido->delete();
    	$partidos = DB::table('partidos')
                            ->select('partidos.id','partidos.dep_id','deportes.descripcion as deporte',
                                'partidos.tor_id','torneos.nombre as torneo', 'partidos.fecha',
                                'partidos.lugar', 'partidos.puntaje_ganador','partidos.puntaje_derrotado')
                            ->leftjoin('deportes','deportes.id', '=', 'partidos.dep_id')
                            ->leftjoin('torneos','torneos.id', '=', 'partidos.tor_id')
                            ->whereNull('partidos.deleted_at')
                            ->where('partidos.tor_id','=',$torneo)
                            ->get();
            $resultado = ['data'=> array('partidos'=>$partidos)];
        return response()->json($resultado, 200);
    }

    //fin partido
    //inicio torneo
    public function get_torneo(){
        $torneos = DB::table('torneos')
                        ->select('torneos.id', 'torneos.dep_id','deportes.descripcion as deporte', 'torneos.nombre', 'torneos.campeon_id', 'torneos.descripcion')
                        ->leftjoin('deportes', 'deportes.id', '=', 'torneos.dep_id')
                        ->whereNull('torneos.deleted_at')
                        ->get();
        
        $resultado = ['data'=> array('torneos'=>$torneos)];
        return response()->json($resultado, 200);
    }

    public function get_torneo_status($id)
    {
        $torneos = DB::select('select partidos.win_id, equipos.nombre as ganador,partidos.puntaje_ganador, partidos.los_id, (select nombre from equipos where id = partidos.los_id) as perdedor, partidos.puntaje_derrotado
                                from partidos
                                left join equipos
                                on partidos.win_id = equipos.id
                                where partidos.tor_id = '.$id.'
                                ');
        $resultado = ['data'=> array('partidos'=>$torneos)];
        return response()->json($resultado, 200);

    }

    public function get_torneo_ranking($id)
    {
        $partidos = DB::select('
                            select partido__equipos.equ_id as equ_id, equipos.nombre, COALESCE(
(select sum(partidos3.puntaje_ganador)
from( select *
    from partidos               
) as partidos3
where partidos3.win_id = partido__equipos.equ_id),
 0)+
  COALESCE(
(select sum(partidos2.puntaje_derrotado)
from( select *
    from partidos               
) as partidos2
where partidos2.los_id = partido__equipos.equ_id), 0) as puntaje
from partidos
left join partido__equipos
on partido__equipos.part_id = partidos.id
left join equipos
on equipos.id = partido__equipos.equ_id
where partidos.tor_id = '.$id.'
group by partido__equipos.equ_id, equipos.nombre
order by puntaje desc
                            ');

        $resultado = ['data'=> array('partidos'=>$partidos)];
        return response()->json($resultado, 200);

    }

    public function get_todos_torneo()
    {
      $torneo = \App\Torneo::select('nombre','descripcion','dep_id')->get();
      

      $resultado = ['torneo'=>$torneo];
      return response()->json($resultado,200);

    }

    public function get_horario_torneo($id)
    {
      $torneo = DB::table('partidos')
                    ->select('partidos.fecha','partidos.lugar','deportes.id as dep_id', 'deportes.descripcion as descripcion_deporte', 'torneos.id as tor_id', 'torneos.nombre as torneo ')
                    ->rightjoin('torneos','torneos.id','=','partidos.tor_id')
                    ->rightjoin('deportes','deportes.id','=','partidos.dep_id')
                    ->where('torneos.id','=',$id)
                    ->whereNull('partidos.deleted_at')
                    ->orderby('partidos.fecha','asc')
                    ->get();
        $resultado = ['data'=> array('partidos'=>$torneo)];
        return response()->json($resultado, 200);
    }

    public function get_partidos_torneo($id)
    {
      $torneo = DB::table('partido__tantos')
                  ->select('partido__tantos.equ_id', DB::raw('sum(valor)'))
                  ->leftjoin('partidos', 'partidos.id','=','partido__tantos.par_id')
                  ->leftjoin('torneos', 'partidos.tor_id', '=', 'torneos.id')
                  ->where('torneos.id', '=', $id)
                  ->whereNull('partidos.deleted_at')
                  ->groupBy('partido__tantos.par_id', 'partido__tantos.equ_id')
                  ->orderBy('partido__tantos.par_id')
                  ->get();
      $resultado = ['data'=> array('resultados'=> $torneo)];
      return response()->json($resultado, 200);

    }

    public function post_torneo(Request $request){
         //validamos

            if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);   
            }

            $validator = Validator::make($request->json()->all(), [
                'dep_id' => 'required|integer',
                'nombre' => 'required|string',
                'descripcion' => 'string'
            ]);
        
            if ($validator->fails()) {
                $resultado = ['message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            $torneo = new \App\Torneo;
            $torneo->dep_id = $request->json('dep_id');
            $torneo->nombre = $request->json('nombre');
            if($request->json('descripcion')!=null){$torneo->descripcion = $request->json('descripcion');}
            $torneo->save();
            $torneos = DB::table('torneos')
                        ->select('torneos.id', 'torneos.dep_id','deportes.descripcion as deporte', 'torneos.nombre', 'torneos.campeon_id', 'torneos.descripcion')
                        ->leftjoin('deportes', 'deportes.id', '=', 'torneos.dep_id')
                        ->whereNull('torneos.deleted_at')
                        ->get();
        
        $resultado = ['data'=> array('torneos'=>$torneos)];
        return response()->json($resultado, 200);
    }

    public function put_torneo ($id, Request $request)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            } 
        $torneo = \App\Torneo::find($id);
        if ($torneo==null) 
        {
            $resultado = ['status' => 'fail',
                          'message' => 'No existe ningun dato'];
            return response()->json($resultado, 400);               
        }
        else 
        {
            $validator = Validator::make($request->json()->all(), [
                'dep_id' => 'integer',
                'nombre' => 'string',
                'campeon_id' => 'integer',
                'descripcion' => 'string',
            ]);
        
            if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

             if($request->json('dep_id')!=null){$torneo->dep_id = $request->json('dep_id');}
             if($request->json('nombre')!=null){$torneo->nombre = $request->json('nombre');}
             if($request->json('campeon_id')!=null){$torneo->campeon_id = $request->json('campeon_id');}
             if($request->json('descripcion')!=null){$torneo->descripcion = $request->json('descripcion');}

             $torneo->save();
             $torneos = DB::table('torneos')
                        ->select('torneos.id', 'torneos.dep_id','deportes.descripcion as deporte', 'torneos.nombre', 'torneos.campeon_id', 'torneos.descripcion')
                        ->leftjoin('deportes', 'deportes.id', '=', 'torneos.dep_id')
                        ->whereNull('torneos.deleted_at')
                        ->get();
        
        $resultado = ['data'=> array('torneos'=>$torneos)];
        return response()->json($resultado, 200);
        }
    }

    public function delete_torneo($id)
    {
        if( Auth::user()->tipo_usuario != "administrador" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);  
            }
        $torneo = \App\Torneo::find($id);

        $torneo->delete();
        $torneos = DB::table('torneos')
                        ->select('torneos.id', 'torneos.dep_id','deportes.descripcion as deporte', 'torneos.nombre', 'torneos.campeon_id', 'torneos.descripcion')
                        ->leftjoin('deportes', 'deportes.id', '=', 'torneos.dep_id')
                        ->whereNull('torneos.deleted_at')
                        ->get();
        
        $resultado = ['data'=> array('torneos'=>$torneos)];
        return response()->json($resultado, 200);

    }




}
