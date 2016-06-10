<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

use Validator;

use Carbon;

use DB;



class NoticiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
           'agregarNoticia',
            'modificarNoticia',
            'eliminarNoticia',
            'agregarNoticiaComentario',
            'eliminarNoticiaComentario'
            ]]);
    }
    
    //NOTICIA
    public function listarNoticias(){
        $noticias = DB::table('noticias')
            ->join('users', 'users.id', '=', 'noticias.user_id')
            ->select('noticias.*', 'users.nombre')
            ->whereNull('noticias.deleted_at')
            ->orderBy('fecha_ultimo_posteo', 'desc')  
            ->get();
        $resultado = ['status' => 'success',
                           'data' => array('noticias'=>$noticias)];
                return response()->json($resultado, 200);
    }
    public function agregarNoticia(Request $request){
        
        //validamos que sea administrador
        if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 401); 
        }
        
        //validamos
        $validator = Validator::make($request->json()->all(), [
            'titulo' => 'required|max:255|string',
            'contenido' => 'required|string',
            ]);
        
        if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                           'message' => 'formato de entrada incorrecto'];
                return response()->json($resultado, 400);
        }
            
        $hora = Carbon\Carbon::now();
        $hora = $hora->toDateTimeString();
            
        //agregamos
        $noticia = new \App\Noticia;
        $noticia->user_id = Auth::User()->id;
        $noticia->titulo = $request->json('titulo');
        $noticia->contenido = $request->json('contenido');
        $noticia->fecha_ultimo_posteo = $hora;
        $noticia->save();
        //listamos
        $noticias = DB::table('noticias')
            ->join('users', 'users.id', '=', 'noticias.user_id')
            ->select('noticias.*', 'users.nombre')
            ->whereNull('noticias.deleted_at')
            ->orderBy('fecha_ultimo_posteo', 'desc')  
            ->get();
        $resultado = ['data' => array('noticia'=>$noticia, 'noticias'=>$noticias)];
        return response()->json($resultado, 200);
        
    }
    public function modificarNoticia(Request $request, $id){
        //validamos que exista la noticia
        $noticia = \App\Noticia::find($id);
        if ($noticia==null){
            $resultado = ['status' => 'fail',
                              'message' => 'no existe noticia'];
                return response()->json($resultado, 400); 
        }
        
        //validamos que sea administrador
        if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 401); 
        }
        
        //validamos
        $validator = Validator::make($request->json()->all(), [
            'titulo' => 'required|max:255|string',
            'contenido' => 'required|string',
            ]);
        
        if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                           'message' => 'formato de entrada incorrecto'];
                return response()->json($resultado, 400);
        }
            
        $hora = Carbon\Carbon::now();
        $hora = $hora->toDateTimeString();
            
        //modificamos
        $noticia->user_id = Auth::User()->id;
        $noticia->titulo = $request->json('titulo');
        $noticia->contenido = $request->json('contenido');
        $noticia->save();
        //listar
        $noticias = DB::table('noticias')
            ->join('users', 'users.id', '=', 'noticias.user_id')
            ->select('noticias.*', 'users.nombre')
            ->whereNull('noticias.deleted_at')
            ->orderBy('fecha_ultimo_posteo', 'desc')  
            ->get();
        $resultado = ['data' => array('noticia'=>$noticia, 'noticias'=>$noticias)];
        return response()->json($resultado, 200);
        
    }
    public function eliminarNoticia($id){
        //validamos que exista la noticia
        $noticia = \App\Noticia::find($id);
        if ($noticia==null){
            $resultado = ['status' => 'fail',
                              'message' => 'no existe noticia'];
                return response()->json($resultado, 400); 
        }
        
        //validamos que sea administrador
        if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 401); 
        }
        
        $noticia->delete();
        $noticias = DB::table('noticias')
            ->join('users', 'users.id', '=', 'noticias.user_id')
            ->select('noticias.*', 'users.nombre')
            ->whereNull('noticias.deleted_at')
            ->orderBy('fecha_ultimo_posteo', 'desc')  
            ->get();
        $resultado = ['data' => array('noticia'=>$noticia, 'noticias'=>$noticias)];
        return response()->json($resultado, 200);
        
    }
    
    
    //NOTICIA_COMENTARIO
    public function listarComentarios($id){
        
        $noticia = \App\Noticia::find($id);
        if ($noticia==null){
            $resultado = ['status' => 'fail',
                           'message' => 'no existe noticia'];
                return response()->json($resultado, 400);
        }
        
        
        $noticias = DB::table('noticia__comentarios')
            ->join('users', 'users.id', '=', 'noticia__comentarios.user_id')
            ->select('noticia__comentarios.*', 'users.nombre')
            ->where('noticia__comentarios.noticia_id',$noticia->id)
            ->whereNull('noticia__comentarios.deleted_at')
            ->orderBy('noticia__comentarios.updated_at', 'desc')  
            ->get();
        
        $resultado = ['status' => 'success',
                           'data' => array('comentarios'=>$noticias, 'noticia'=>$noticia)];
                return response()->json($resultado, 200);
    }
    public function agregarNoticiaComentario(Request $request, $id) {
        //validamos que exista la noticia
        $noticia = \App\Noticia::find($id);
        if ($noticia==null){
            $resultado = ['status' => 'fail',
                              'message' => 'no existe noticia'];
                return response()->json($resultado, 400); 
        }
        
        //validamos que sea alumno
        if (Auth::User()->tipo_usuario != "alumno"){
            $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 401); 
        }
        
        
        //validamos
        $validator = Validator::make($request->json()->all(), [
             'contenido' => 'required|string',
            ]);
        
        if ($validator->fails()) {
                $resultado = ['status' => 'fail',
                           'message' => 'formato de entrada incorrecto'];
                return response()->json($resultado, 400);
        }
        
        //agregamos comentario
        $noticia_comentario = new \App\Noticia_Comentario;
        $noticia_comentario->noticia_id = $noticia->id;
        $noticia_comentario->user_id = Auth::User()->id;
        $noticia_comentario->contenido = $request->json('contenido');
        $noticia_comentario->save();
        
        //actualizamos fecha_ultimo_posteo
        $noticia->fecha_ultimo_posteo = $noticia_comentario->created_at;
        $noticia->save();
        
        //obtenemos comentarios
        $comentarios = DB::table('noticia__comentarios')
            ->join('users', 'users.id', '=', 'noticia__comentarios.user_id')
            ->select('noticia__comentarios.*', 'users.nombre')
            ->whereNull('noticia__comentarios.deleted_at')
            ->where('noticia__comentarios.noticia_id',$noticia->id)    
            ->get();
        
        $resultado = ['status' => 'success',
                           'data' => array('noticia'=>$noticia, 'comentarios'=>$comentarios)];
                return response()->json($resultado, 200);
        
    }
    public function eliminarNoticiaComentario($id){
        
        $noticia_comentario = \App\Noticia_Comentario::find($id);
        
        //validamos que exista comentario
        if ($noticia_comentario==null){
            $resultado = ['status' => 'fail',
                              'message' => 'no existe comentario'];
                return response()->json($resultado, 400); 
        }
        
        //validamos que sea administrador o que sea el usuario que escribio el comentario
        if (Auth::User()->id != $noticia_comentario->user_id){
            if( Auth::user()->tipo_usuario != "administrador") {
                $resultado = ['status' => 'fail',
                              'message' => 'no autorizado'];
                return response()->json($resultado, 401); 
            }
        }
        
        //obtenemos noticia
        $noticia = \App\Noticia::find($noticia_comentario->noticia_id);
        
        //eliminamos
        $noticia_comentario->delete();
        
        //actualizamos fecha_ultimo_posteo
        $hora = Carbon\Carbon::now();
        $hora = $hora->toDateTimeString();
        
        $noticia->fecha_ultimo_posteo = $hora;
        $noticia->save();
        
        //obtenemos comentarios
        $comentarios = DB::table('noticia__comentarios')
            ->join('users', 'users.id', '=', 'noticia__comentarios.user_id')
            ->select('noticia__comentarios.*', 'users.nombre')
            ->whereNull('deleted_at')
            ->where('noticia__comentarios.noticia_id',$noticia->id)  
            ->get();
        
        $resultado = ['status' => 'success',
                           'data' => array('noticia'=>$noticia, 'comentarios'=>$comentarios)];
                return response()->json($resultado, 200);
        
        
        
    }
    
    
    
    
    

    
}
