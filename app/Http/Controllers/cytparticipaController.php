<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

use Validator;

use Carbon;

use DB;



class cytparticipaController extends Controller
{ 
    //

 public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'agregarDiscusiones',
            'editarDiscusiones',
            'eliminarDiscusiones',
            'agregarDiscusionComentarios',
            'eliminarDiscusionComentarios',
            'agregarProblematicas',
            'editarProblematicas',
            'eliminarProblematicas',
            'agregarProblematicaComentarios',
            'eliminarProblematicaComentarios',
            'crearVotaciones',
            'editarVotaciones',
            'eliminarVotaciones',
            'agregarOpcionVotaciones',
            'editarOpcionVotaciones',
            'eliminarOpcionVotaciones',
            'votar',

           




            ]]);
    }


/*####################################################DISCUSION###################################################*/


/*LISTAR LAS  DISCUSIONES*/

    public function listarDiscusiones(){

            $discusion = DB::table('discusiones')
            ->join('users', 'users.id', '=', 'discusiones.user_id')
            ->select('discusiones.*', 'users.nombre')
            ->whereNull('discusiones.deleted_at')
            ->orderBy('fecha_ultimo_posteo', 'desc')  
            ->get();
        
        $resultado = [
                           'data' => array('Discusiones'=>$discusion)];
                return response()->json($resultado, 200);

    }

        /*VER UNA   DISCUSION*/

    public function verDiscusion($id){
        $discusion = \App\Discusion::find($id);
        $resultado = ['data'=> $discusion];
        return response()->json($resultado, 200);
    }
    

    /*AGREGAR UNA DISCUSION*/

    public function agregarDiscusiones(Request $request){
        if( Auth::user()->tipo_usuario != "alumno" ) {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);   
        }
        else {
            //validamos
            $validator = Validator::make($request->json()->all(), [
                'titulo' => 'required|max:255|string',
                'contenido' => 'required|string',
            ]);
        
            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
            $mytime = Carbon\Carbon::now();
            $mytime = $mytime->toDateTimeString();
            
            //agregamos
            $discusion = new \App\Discusion;
            $discusion->titulo = $request->json('titulo');
            $discusion->contenido = $request->json('contenido');
            $discusion->fecha_ultimo_posteo = $mytime;
            $discusion->user_id = auth::user()->id;
            $discusion->save();



         //obtenemos comentarios
        $discusion = DB::table('discusiones')
            ->join('users', 'users.id', '=', 'discusiones.user_id')
            ->select('discusiones.*', 'users.nombre')
            ->whereNull('discusiones.deleted_at')
            //->where('discusiones.discusion_id',$discusion->id)    
            ->get();
        
        $resultado = ['data' => array('Discusiones'=>$discusion)];
                return response()->json($resultado, 200);



        }
    }
    
        /*EDITAR UNA DISCUSION*/

    public function editarDiscusiones ($id, Request $request) {
            

            $discusion = \App\Discusion::find($id);

                if ($discusion==null) {
                $resultado = [
                              'message' => 'No existe ningun dato'];
                return response()->json($resultado, 400);               
                 }
                 else {
           
  //validamos
            $validator = Validator::make($request->json()->all(), [
                'titulo' => 'max:255|string',
                'contenido' => 'string',
            ]);
        
            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }


            $mytime = Carbon\Carbon::now();
            $mytime = $mytime->toDateTimeString();
//-------------------------------aqui tengo mi duda----------------------------------

  //agregamos
            if ($request->json('titulo')!=null) { $discusion->titulo = $request->json('titulo'); }
            if ($request->json('contenido')!=null) {   $discusion->contenido = $request->json('contenido');}
            $discusion->fecha_ultimo_posteo = $mytime;
            $discusion->save();
           


              //obtenemos comentarios
        $discusion = DB::table('discusiones')
            ->join('users', 'users.id', '=', 'discusiones.user_id')
            ->select('discusiones.*', 'users.nombre')
            ->whereNull('discusiones.deleted_at')
            //->where('discusiones.discusion_id',$discusion->id)    
            ->get();
        
        $resultado = ['data' => array('Discusiones'=>$discusion)];
                return response()->json($resultado, 200);

                } 
    }
    

        /*ELIMINAR UNA DISCUSION*/

    public function eliminarDiscusiones($id){
        //
        $discusion = \App\Discusion::find($id);

        //$user_id = User::find($id)
        if($discusion && (Auth::user()->id == $discusion->user_id))
        {
            $discusion->delete();
                //obtenemos comentarios
        $discusion = DB::table('discusiones')
            ->join('users', 'users.id', '=', 'discusiones.user_id')
            ->select('discusiones.*', 'users.nombre')
            ->whereNull('discusiones.deleted_at')
            //->where('discusiones.discusion_id',$discusion->id)    
            ->get();
        
        $resultado = ['data' => array('Discusiones'=>$discusion)];
                return response()->json($resultado, 200);
        }
        else 
        {
                $resultado = [
                              'message' => 'No tiene permisos para eliminar'];        

                             return response()->json($resultado, 401);
                          }
                            
            
    }



    /*AGREGAR UN COMENTARIO A LA DISCUSION*/


 public function agregarDiscusionComentarios(Request $request, $id){
    
    if( Auth::user()->tipo_usuario != "alumno") {
  
      $resultado = [
                              'message' => 'No tienes permiso para comentar..'];
                return response()->json($resultado, 400);
      }

         $discusion = \App\Discusion::find($id);

      if ($discusion == null){

                 $resultado = [
                              'message' => 'error no existe la discusion'];
                return response()->json($resultado, 400);
            }

      else {
 //validamos
            $validator = Validator::make($request->json()->all(), [
                'contenido' => 'required|string',
            ]);
        
            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
           
           
            //agregamos
            $discusion_Comentario = new \App\Discusion_Comentario;
            $discusion_Comentario->contenido = $request->json('contenido');
            $discusion_Comentario->discusion_id = $discusion->id;
            $discusion_Comentario->user_id = auth::user()->id;
            $discusion_Comentario->save();
           

           //actualizamos fecha_ultimo_posteo
        $discusion->fecha_ultimo_posteo = $discusion_Comentario->created_at;
        $discusion->save();
        
        //obtenemos comentarios
        $comentarios = DB::table('discusion__comentarios')
            ->join('users', 'users.id', '=', 'discusion__comentarios.user_id')
            ->select('discusion__comentarios.*', 'users.nombre')
            ->whereNull('discusion__comentarios.deleted_at')
            ->where('discusion__comentarios.discusion_id',$discusion->id)    
            ->get();
        
        $resultado = [
                           'data' => array('Discusion'=>$discusion, 'comentarios'=>$comentarios)];
                return response()->json($resultado, 200);


      }
     
        
    }

        /*LISTAR COMENTARIOS DE  UNA DISCUSION*/


 public function listarDiscusionComentarios($id){
        
        $discusion = \App\Discusion::find($id);
        if ($discusion==null){
            $resultado = [
                           'message' => 'no existe discusion'];
                return response()->json($resultado, 400);
        }
        
        
        $comentarios = DB::table('discusion__comentarios')
            ->join('users', 'users.id', '=', 'discusion__comentarios.user_id')
            ->select('discusion__comentarios.*', 'users.nombre')
            ->where('discusion__comentarios.discusion_id',$discusion->id)
            ->whereNull('discusion__comentarios.deleted_at')
            ->orderBy('discusion__comentarios.updated_at', 'desc')  
            ->get();
        
         $resultado = [
                           'data' => array('Discusion'=>$discusion, 'comentarios'=>$comentarios)];
                return response()->json($resultado, 200);

    }

    /*ELIMINAR COMENTARIOS DE  UNA DISCUSION*/

    public function eliminarDiscusionComentarios($id){ //un usuario logueado o el administrador puede eliminar el comentario, como baneando a la persona por algun motivo
        //
        $discusion_Comentario = \App\Discusion_Comentario::find($id);
        

        //$user_id = User::find($id)
        if((Auth::user()->id == $discusion_Comentario->user_id))
        {
            $discusion_Comentario->delete();
        $discusion = \App\Discusion::find($discusion_comentario->discusion_id);
        
        $comentarios = DB::table('discusion__comentarios')
            ->join('users', 'users.id', '=', 'discusion__comentarios.user_id')
            ->select('discusion__comentarios.*', 'users.nombre')
            ->where('discusion__comentarios.discusion_id',$discusion->id)
            ->whereNull('discusion__comentarios.deleted_at')
            ->orderBy('discusion__comentarios.updated_at', 'desc')  
            ->get();
        
        $resultado = [
                           'data' => array('Comentarios'=>$comentarios)];
                return response()->json($resultado, 200);
        }
        else 
        {
                $resultado = [
                              'message' => 'No tiene permisos para eliminar'];        

                             return response()->json($resultado, 401);
                          }
                            
            
    }



/*####################################################PROBLEMATICA###################################################*/

/*LISTAR PROBLEMATICAS*/

    public function listarProblematicas(){


            $problematica = DB::table('problematicas')
            ->join('users', 'users.id', '=', 'problematicas.user_id')
            ->select('problematicas.*', 'users.nombre')
            ->whereNull('problematicas.deleted_at')
            ->orderBy('created_at', 'desc')  
            ->get();
        
        $resultado = [
                           'data' => array('problematicas'=>$problematica)];
                return response()->json($resultado, 200);

    }


/*CREAR PROBLEMATICA*/

    public function crearProblematicas(Request $request){
        if( Auth::user()->tipo_usuario != "administrador") {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 401);   
        }
        else {
            //validamos
            $validator = Validator::make($request->json()->all(), [
                'titulo' => 'required|max:255|string',
                'contenido' => 'required|string',
            ]);
        
            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }


            //validamos que solo un voto por usuario sea hecho


            $mytime = Carbon\Carbon::now();
            $mytime = $mytime->toDateTimeString();
            
            //agregamos
            $problematica = new \App\Problematica;
            $problematica->titulo = $request->json('titulo');
            $problematica->contenido = $request->json('contenido');
            $problematica->fecha = $mytime;
            $problematica->user_id = auth::user()->id;//ojo cambie porque me equivoque al crear la migracion
            $problematica->save();
           /* $resultado = [
                          'data' => $problematica];
                return response()->json($resultado, 200);*/



           //listamos todas las problematicas
        $problematicas = DB::table('problematicas')
            ->join('users', 'users.id', '=', 'problematicas.user_id')
            ->select('problematicas.*', 'users.nombre')
            ->whereNull('problematicas.deleted_at')
            //->where('discusiones.discusion_id',$discusion->id)    
            ->get();
        
        $resultado = ['data' => array('Problematicas'=>$problematicas)];
                return response()->json($resultado, 200);
        }
    }
    
   

/*EDITAR PROBLEMATICA*/

    public function editarProblematicas ($id, Request $request) {
            

            $problematica = \App\Problematica::find($id);

                if ($problematica==null) {
                $resultado = [
                              'message' => 'No existe ningun dato'];
                return response()->json($resultado, 400);               
                 }
                 else {
           
  //validamos
            $validator = Validator::make($request->json()->all(), [
                'titulo' => 'max:255|string',
                'contenido' => 'string',
            ]);
        
            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }


            $mytime = Carbon\Carbon::now();
            $mytime = $mytime->toDateTimeString();
//-------------------------------aqui tengo mi duda----------------------------------

  //agregamos
            if ($request->json('titulo')!=null) { $problematica->titulo = $request->json('titulo'); }
            if ($request->json('contenido')!=null) {   $problematica->contenido = $request->json('contenido');}
            $problematica->fecha = $mytime;
            $problematica->save();
            /*$resultado = ['status' => 'Problematica Editada Satisfactoriamente',
                          'data' => $problematica];
                return response()->json($resultado, 200);*/



           //listamos todas las problematicas
        $problematicas = DB::table('problematicas')
            ->join('users', 'users.id', '=', 'problematicas.user_id')
            ->select('problematicas.*', 'users.nombre')
            ->whereNull('problematicas.deleted_at')
            //->where('discusiones.discusion_id',$discusion->id)    
            ->get();
        
        $resultado = ['data' => array('Problematicas'=>$problematicas)];
                return response()->json($resultado, 200);

                } 
    }
    
    /*ELIMINAR UNA PROBLEMATICA*/
    public function eliminarProblematicas($id){
        //
        $problematica = \App\Problematica::find($id);

        //$user_id = User::find($id)
        if($problematica && (Auth::user()->tipo_usuario == "administrador"))
        {
            $problematica->delete();
             //listamos todas las problematicas
        $problematicas = DB::table('problematicas')
            ->join('users', 'users.id', '=', 'problematicas.user_id')
            ->select('problematicas.*', 'users.nombre')
            ->whereNull('problematicas.deleted_at')
            //->where('discusiones.discusion_id',$discusion->id)    
            ->get();
        
        $resultado = ['data' => array('Problematicas'=>$problematicas)];
                return response()->json($resultado, 200);
        }
        else 
        {
                $resultado = [
                              'message' => 'No tiene permisos para eliminar'];        

                             return response()->json($resultado, 400);
                          }
                            
            
    }

/*AGREGAR UN COMENTARIO A LA PROBLEMATICA*/

public function agregarProblematicaComentarios(Request $request, $id){

    if( Auth::user()->tipo_usuario != "alumno") {
  
      $resultado = [
                              'message' => 'No puedes comentar esto, debes loguearte..'];
                return response()->json($resultado, 400);
      }

    
      $problematica = \App\Problematica::find($id);

        if ($problematica == null){

                 $resultado = [
                              'message' => 'error no existe la problematica'];
                return response()->json($resultado, 400);
            }

     //validamos
            $validator = Validator::make($request->json()->all(), [
                'contenido' => 'required|string',
            ]);
        
            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
           


        $problematica_Comentario = \App\Problematica_Comentario::where('user_id',Auth::User()->id)
        ->where('problematica_id', $request->json('problematica_id'))
        ->count();

        //verificamos si para una opcion en especifica ya voto..

        if (/*$votacion_opcion->votacion_id!=$votacion->id ||*/ $problematica_Comentario>=1) {
            $resultado = [
                              'message' => 'Ya has votado. No lo puedes volver a hacer'];
                return response()->json($resultado, 400);  
        }



           
            //agregamos
            $problematica_Comentario = new \App\Problematica_Comentario;
            $problematica_Comentario->contenido = $request->json('contenido');
            $problematica_Comentario->problematica_id = $id;
            $problematica_Comentario->user_id = auth::user()->id;
            $problematica_Comentario->save();
           /* $resultado = [
                          'data' => $problematica_Comentario];
                return response()->json($resultado, 200);*/




                           //actualizamos fecha_ultimo_posteo
        //$problematica->fecha_ultimo_posteo = $problematica_Comentario->created_at;
       // $problematica->save();
        
        //obtenemos comentarios
        $comentarios = DB::table('problematica__comentarios')
            ->join('users', 'users.id', '=', 'problematica__comentarios.user_id')
            ->select('problematica__comentarios.*', 'users.nombre')
            ->whereNull('problematica__comentarios.deleted_at')
            ->where('problematica__comentarios.problematica_id',$problematica->id)    
            ->get();
        
        $resultado = [
                           'data' => array('Problematica'=>$problematica, 'comentarios'=>$comentarios)];
                return response()->json($resultado, 200);


      
     
        
    }

/*ELIMINAR UN COMENTARIO DE LA  PROBLEMATICA*/


        public function eliminarProblematicaComentarios($id){ //un usuario logueado o el administrador puede eliminar el comentario, como baneando a la persona por algun motivo
        //
        $problematica_Comentario = \App\Problematica_Comentario::find($id);

        //$user_id = User::find($id)
        if($problematica_Comentario && (Auth::user()->tipo_usuario == "administrador"))
        {
            $problematica_Comentario->delete();
             //obtenemos comentarios
        $comentarios = DB::table('problematica__comentarios')
            ->join('users', 'users.id', '=', 'problematica__comentarios.user_id')
            ->select('problematica__comentarios.*', 'users.nombre')
            ->whereNull('problematica__comentarios.deleted_at')
            ->where('problematica__comentarios.problematica_id',$problematica->id)    
            ->get();
        
        $resultado = [
                           'data' => array('Problematica'=>$problematica, 'comentarios'=>$comentarios)];
                return response()->json($resultado, 200);
        }
        else 
        {
                $resultado = [
                              'message' => 'No tiene permisos para eliminar'];        

                             return response()->json($resultado, 400);
                          }
                            
            
    }

/*LISTAR TODOS LOS COMENTARIOS DE UNA  PROBLEMATICA*/

    public function listarProblematicaComentarios($id){
        
        $problematica = \App\Problematica::find($id);
        if ($problematica==null){
            $resultado = [
                           'message' => 'no existe Problematica'];
                return response()->json($resultado, 400);
        }
        
        
        $comentarios = DB::table('problematica__comentarios')
            ->join('users', 'users.id', '=', 'problematica__comentarios.user_id')
            ->select('problematica__comentarios.*', 'users.nombre')
            ->where('problematica__comentarios.problematica_id',$problematica->id)
            ->whereNull('problematica__comentarios.deleted_at')
            ->orderBy('problematica__comentarios.updated_at', 'desc')  
            ->get();
        
   $resultado = [
                           'data' => array('Comentarios'=>$comentarios)];
                return response()->json($resultado, 200);
    }



/*####################################################VOTACION###################################################*/



    /*LISTAR UNA VOTACION*/

     public function listarVotaciones(){

            $votacion = DB::table('votaciones')
            ->join('users', 'users.id', '=', 'votaciones.user_id')
            ->select('votaciones.*', 'users.nombre')
            ->whereNull('votaciones.deleted_at')
            ->orderBy('created_at', 'desc')  
            ->get();
        
        $resultado = [
                           'data' => array('Votaciones'=>$votacion)];
                return response()->json($resultado, 200);  

    }


        /*CREAR UNA VOTACION*/

    public function crearVotaciones(Request $request){
        if( Auth::user()->tipo_usuario != "administrador") {
             $resultado = [
                              'message' => 'No tienes permiso para esta acción'];
            return response()->json($resultado, 400);   
        }
        else {
            




            //validamos
            $validator = Validator::make($request->json()->all(), [
                'titulo' => 'required|string',
                'contenido' => 'required|string',
                'fecha_inicio'=> 'required|date_format:Y-m-d|after:today',
                'fecha_fin'=>'required|date_format:Y-m-d|after:fecha_inicio',
            ]);
        

            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
            $mytime = Carbon\Carbon::now();
            $mytime = $mytime->toDateTimeString();
             
         
            //agregamos
            $votacion = new \App\Votacion;
            
            //verificamos si la fecha ingresada es mayor a la fecha actual.. asi solo se agrega
            
            $votacion->titulo = $request->json('titulo');
            $votacion->contenido = $request->json('contenido');
            $votacion->fecha_inicio = $request->json('fecha_inicio');
            $votacion->fecha_fin = $request->json('fecha_fin');
            $votacion->user_id = auth::user()->id;
            $votacion->save();
            /*$resultado = [
                          'data' => $votacion];
                return response()->json($resultado, 200);*/

  $votacion = DB::table('votaciones')
            ->join('users', 'users.id', '=', 'votaciones.user_id')
            ->select('votaciones.*', 'users.nombre')
            ->whereNull('votaciones.deleted_at')
            ->orderBy('created_at', 'desc')  
            ->get();
        
        $resultado = [
                           'data' => array('Votaciones'=>$votacion)];
                return response()->json($resultado, 200);  



         
        }
    }
    

/*EDITAR UNA VOTACION*/

    public function editarVotaciones ($id, Request $request) {
            

                              
                if (Auth::user()->tipo_usuario == "administrador") { //verificamos que es el administrador.
                         

                          $votacion = \App\Votacion::find($id);
                         if ($votacion==null) {
                                $resultado = [
                                              'message' => 'No existe ningun dato'];
                                return response()->json($resultado, 400);               
                                            }
                        else {
                           
                  //validamos
                            $validator = Validator::make($request->json()->all(), [
                                'titulo' => 'max:255|string',
                                'contenido' => 'string',
                                'fecha_inicio'=> 'required|date_format:Y-m-d|after:today',
                                'fecha_fin'=>'required|date_format:Y-m-d|after:fecha_inicio',

                            ]);
                        
                            if ($validator->fails()) {
                                $resultado = [
                                              'message' => 'Formato de entrada incorrecto'];
                                return response()->json($resultado, 400);
                            }


                            $mytime = Carbon\Carbon::now();
                            $mytime = $mytime->toDateTimeString();
                //-------------------------------aqui tengo mi duda----------------------------------

                  //agregamos
                            if ($request->json('titulo')!=null) { $votacion->titulo = $request->json('titulo'); }
                            if ($request->json('contenido')!=null) {   $votacion->contenido = $request->json('contenido');}
                            if ($request->json('fecha_inicio')!=null){ $votacion->fecha_inicio = $request->json('fecha_inicio');} 
                            if ($request->json('fecha_fin')!=null) { $votacion->fecha_fin= $request->json('fecha_fin'); } 
                                $votacion->save();
                                 $votacion = DB::table('votaciones')
					            ->join('users', 'users.id', '=', 'votaciones.user_id')
					            ->select('votaciones.*', 'users.nombre')
					            ->whereNull('votaciones.deleted_at')
					            ->orderBy('created_at', 'desc')  
					            ->get();
					        
					        $resultado = [
					                           'data' => array('Votaciones'=>$votacion)];
					                return response()->json($resultado, 200);  
					                            } 
					                }else {
					                            $resultado = [
					                                              'message' => 'No puedes editar esta votacion'];
					                                return response()->json($resultado, 400);

                }
    }
              
        /*ELIMINAR UNA VOTACION*/

    public function eliminarVotaciones($id){
        //
        $votacion = \App\Votacion::find($id);

        //$user_id = User::find($id)
        if($votacion && (Auth::user()->tipo_usuario == "administrador"))
        {
            $votacion->delete();
             $votacion = DB::table('votaciones')
            ->join('users', 'users.id', '=', 'votaciones.user_id')
            ->select('votaciones.*', 'users.nombre')
            ->whereNull('votaciones.deleted_at')
            ->orderBy('created_at', 'desc')  
            ->get();
        
        $resultado = [
                           'data' => array('Votaciones'=>$votacion)];
                return response()->json($resultado, 200);  
        }
        else 
        {
                $resultado = [
                              'message' => 'No tiene permisos para eliminar'];        

                             return response()->json($resultado, 401);
                          }
                            
            
    }




/*OPCIONES DE VOTACION*/

    public function agregarOpcionVotaciones(Request $request,$id){
       

    if(Auth::user()->tipo_usuario != "administrador") {
  
      $resultado = [
                              'message' => 'No tienes permiso para crear items..'];
                return response()->json($resultado, 400);
      }

      $votacion= \App\Votacion::find($id);

        
//verificamos que exista la votacion requerida previamente
    if ($votacion!=null) {
        
        //verificamos que es el administrador.
        if( Auth::user()->tipo_usuario != "administrador") {
             $resultado = [
                              'message' => 'No tienes permisos suficientes'];
            return response()->json($resultado, 400);   
        }
        else {
            //validamos
            $validator = Validator::make($request->json()->all(), [
                'descripcion' => 'required|max:255|string',
                'valor' => 'required|string',
          
            ]);
        
            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

            //agregamos
            $votacion_Opcion = new \App\Votacion_Opcion;
            $votacion_Opcion->descripcion = $request->json('descripcion');
            $votacion_Opcion->valor = $request->json('valor');
            $votacion_Opcion->votacion_id=$id;
            $votacion_Opcion->save();
            /*$resultado = [
                          'data' => $votacion_Opcion];
                return response()->json($resultado, 200);*/
            
          
                           //actualizamos fecha_ultimo_posteo
        $votacion->created_at = $votacion_Opcion->created_at;
        $votacion->save();
        
        //obtenemos opciones
        $votacion_Opcion = DB::table('votacion__opciones')
         
            ->whereNull('votacion__opciones.deleted_at')
            ->where('votacion__opciones.votacion_id',$votacion->id)    
            ->get();
        
        $resultado = [
                           'data' => array('Votacion'=>$votacion, 'opciones'=>$votacion_Opcion)];
                return response()->json($resultado, 200);

         
        }

    }
//Si no existe la votacion dice que no existe tal cosa...
      else{
          $resultado = [
                              'message' => 'No existe la votacion requerida'];
                return response()->json($resultado, 400);
      }  
    }
    
/*EDITAR UNA OPCION DE VOTACION*/

 public function editarOpcionVotaciones ($id, Request $request) {
            	

             //verificamos que es el administrador.       
                if (Auth::user()->tipo_usuario == "administrador") { 
                          		$votacion_Opcion = \App\Votacion_Opcion::find($id);
                         

                         if ($votacion_Opcion==null) {
                                $resultado = [
                                              'message' => 'No existe ningun dato'];
                                return response()->json($resultado, 400);               
                                            }
                        else {
                           
                  //validamos
                            $validator = Validator::make($request->json()->all(), [
                                'descripcion' => 'max:255|string',
                                'valor' => 'string',
                            ]);
                        
                            if ($validator->fails()) {
                                $resultado = [
                                              'message' => 'Formato de entrada incorrecto'];
                                return response()->json($resultado, 400);
                            }


                              $votacion= \App\Votacion::find($id);

                  //agregamos
                            if ($request->json('descripcion')!=null) { $votacion_Opcion->descripcion = $request->json('descripcion'); }
                            if ($request->json('valor')!=null) {   $votacion_Opcion->valor = $request->json('valor');}
                            $votacion_Opcion->votacion_id = $id;
                                $votacion_Opcion->save();
                              //obtenemos opciones
						        $votacion_Opcion = DB::table('votacion__opciones')
						         
						            ->whereNull('votacion__opciones.deleted_at')
						            ->where('votacion__opciones.votacion_id',$votacion->id)    
						            ->get();
						        
						        $resultado = [
						                           'data' => array('Votacion'=>$votacion, 'opciones'=>$votacion_Opcion)];
						                return response()->json($resultado, 200);

                            } 
                }else {
                            $resultado = [
                                              'message' => 'No puedes editar esta votacion_Opcion'];
                                return response()->json($resultado, 400);

                }
    }



    /*ELIMINAR UNA OPCION DE VOTACION*/

  public function eliminarOpcionVotaciones($id){
        //
        $votacion_Opcion = \App\Votacion_Opcion::find($id);

        //$user_id = User::find($id)
        if($votacion_Opcion && (Auth::user()->tipo_usuario == "administrador"))
        {
            $votacion_Opcion->delete();
        
            $votacion_Opcion = DB::table('votacion__opciones')
         
            ->whereNull('votacion__opciones.deleted_at')
            ->where('votacion__opciones.votacion_id',$votacion->id)    
            ->get();
        
        $resultado = [
                           'data' => array('opciones'=>$votacion_Opcion)];
                return response()->json($resultado, 200);

        }
        else 
        {
                $resultado = [
                              'message' => 'No tiene permisos para eliminar'];        

                             return response()->json($resultado, 400);
                          }
                            
            
    }


    public function listarOpcionVotaciones($id){
        
        $votacion = \App\Votacion::find($id);
        if ($votacion==null){
            $resultado = [
                           'message' => 'no existe Votacion disponible'];
                return response()->json($resultado, 400);
        }
        
        
        $opciones = DB::table('votacion__opciones')
            ->where('votacion__opciones.votacion_id',$votacion->id)
            ->whereNull('votacion__opciones.deleted_at')
            ->orderBy('votacion__opciones.updated_at', 'desc')  
            ->get();
        
        $resultado = [
                           'data' => array('Opciones'=>$opciones)];
                return response()->json($resultado, 200);
    }



/*VOTAR*/


    public function votar(Request $request){
      

       //solo tipo alumno
        if( Auth::user()->tipo_usuario != "alumno") {
             $resultado = [
                              'message' => 'No tienes permisos suficientes'];
            return response()->json($resultado, 400);   
        }



            //validamos
            $validator = Validator::make($request->json()->all(), [
                'votacion_opcion_id' => 'required|integer',
                'user_id' => 'required|integer',
                'votacion_id' => 'required|integer',
          
            ]);
        
            if ($validator->fails()) {
                $resultado = [
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }

        //obtenemos votacion
        $votacion=\App\Votacion::find ($request->json('votacion_id'));
        //obtenemos votacion opcion
        $votacion_opcion=\App\Votacion_Opcion::find ($request->json('votacion_opcion_id'));
        //si el usuario ya voto
        $votacion_voto = \App\Votacion_Voto::where('user_id',Auth::User()->id)
        ->where('votacion_id', $request->json('votacion_id'))
        ->count();


        //existen ambos
        if ($votacion==null || $votacion_opcion==null) {
            $resultado = [
                              'message' => 'No existe la votacion que estas buscando'];
                return response()->json($resultado, 400);        

            }
        //verificamos si para una opcion en especifica ya voto..

        if (/*$votacion_opcion->votacion_id!=$votacion->id ||*/ $votacion_voto>=1) {
            $resultado = [
                              'message' => 'Ya has votado. No lo puedes volver a hacer'];
                return response()->json($resultado, 400);  
        }




            //agregamos
            $votacion_Opcion_voto = new \App\Votacion_Voto;
            $votacion_Opcion_voto->votacion_opcion_id = $request->json('votacion_opcion_id');
            $votacion_Opcion_voto->user_id = $request->json('user_id');
            $votacion_Opcion_voto->votacion_id= $request->json('votacion_id');
            $votacion_Opcion_voto->save();
     
   //obtenemos cantidad de opciones de Votacion Opcion
 //obtenemos comentarios
        $votacion_voto = DB::table('votacion__votos')
            ->select('votacion_opcion_id', db::raw('count(votacion__votos.votacion_id)'))
            ->whereNull('votacion__votos.deleted_at')
            ->where('votacion__votos.votacion_id','=',$votacion->id)
            ->groupby('votacion_opcion_id')    
            ->get();
        
        $resultado = [
                           'data' => array('Votacion'=>$votacion, 'Resultados'=>$votacion_voto)];
                return response()->json($resultado, 200);
        
    }


/*eliminar voto por parte del administrador*/

    public function eliminarVoto($id){
        //
        $votacion_Voto = \App\Votacion_Voto::find($id);

        if($votacion_Voto && (Auth::user()->tipo_usuario =='administrador'))
        {
            $votacion_Voto->delete();
            $resultado = [
                          'Voto' => $votacion_Voto];
                return response()->json($resultado, 200);
        }
        else 
        {
                $resultado = [
                              'message' => 'No tiene permisos para eliminar'];        

                             return response()->json($resultado, 400);
                          }
                            
            
    }





















}
