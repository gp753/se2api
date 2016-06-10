<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Validator;

class TransparenciaController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'post',
            'put',
            'get',
            ]]);
    }
    
    public function get(){
        $actividad = \App\Actividad::all()->where('deleted_at',null);//select('id','descripcion','detalle','fecha_inicio','fecha_fin')->get();
        
        $data = array('actividades'=>$actividad);
        
        $resultado = ['data'=>$data,'message'=>'Listado de todas las actividades'];
        return response()->json($resultado,200);
    }
    
    public function getBalance($id){
        if($actividad = \App\Actividad::find($id)){
           
            $movimiento = new \App\Movimiento;
            
            //lista todos los movimientos
            $ingresos = $movimiento::where('actividad_id',$id)->where('tipo','I')->where('deleted_at',null)
                                        ->select('fecha','descripcion','tipo','monto')->get();
                                                   
            $egresos = $movimiento::where('actividad_id',$id)->where('tipo','E')->where('deleted_at',null)
                                        ->select('fecha','descripcion','tipo','monto')->get();
            
            /*Realiza el balance por cada actividad*/
            
            //suma todos los montos de ingresos por actividad
            $totIngresos = $movimiento::where('actividad_id',$id)
                                      ->where('tipo','I')
                                      ->where('deleted_at',null)
                                      ->get()->sum('monto');
            
            //suma todos los egresos por activiad
            $totEgresos = $movimiento::where('actividad_id',$id)
                                     ->where('tipo','E')
                                     ->where('deleted_at',null)
                                     ->get()->sum('monto');
            //hace el arqueo
            //if($totIngresos){
                $neto = $totIngresos - $totEgresos;
           // }else{$neto = 0;}
            
            
            $balance = array('haber'=>$totIngresos,
                              'debe'=>$totEgresos,
                              'neto'  =>$neto);
            
            
            $mov = array('ingresos'=>$ingresos, 'egresos'=>$egresos);
            $data = array ('actividad'=>$actividad,'movimientos'=>$mov,'balance'=>$balance );
            
            $resultado = ['data' => $data, 
                          'message'=>''];
                        
            return response()->json($resultado, 200);
            
        }else{
            //$data = array('message'=> 'Id solicitado no existe')
            $resultado = ['data'=>'','message'=> 'Id solicitado no existe'];
            return response()->json($resultado, 400);
        }
    }
        
    public function post(Request $request){
        if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['data'=>'',
                          'message'=>"no autorizado"];
            return response()->json($resultado, 401);   
        }
        else {
            //validamos
            $validator = Validator::make($request->json()->all(), [
                'descripcion' => 'required|string',
                'detalle' => 'required|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            
           
           /*validamos los parametros*/
            if ($validator->fails()) {
                
                $resultado = ['data' => ' ',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
           
            
            $actividad = new \App\Actividad;
            $actividad->descripcion = $request->json('descripcion');
            $actividad->detalle = $request->json('detalle');//
           
            $actividad->fecha_inicio = $request->json('fecha_inicio');//$fecha; //no recibe este parametro ??
            $actividad->fecha_fin = $request->json('fecha_fin');//$fecha; //no recibe este parametro ??
            $actividad->user_id = Auth::user()->id;
            $actividad->save();
            
            $actividades = $actividad::all()->where('deleted_at',null);//select('id','descripcion','detalle','fecha_inicio','fecha_fin')->get();
            
            $data = array('actividades'=>$actividades,
                          'actividad'=>$actividad);
            
            $resultado = ['data' =>$data,'message' => 'success'];
                return response()->json($resultado, 200);
        }
    }
    
    public function put ($id, Request $request) {
        if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['data'=>'',
                          'message'=>"no autorizado"];
            return response()->json($resultado, 401);      
        }
        else {
            $validator = Validator::make($request->json()->all(), [
                'descripcion' => 'required|string',
                'detalle' => 'required|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
        
            /*validamos los parametros*/
            if ($validator->fails()) {
                
                $resultado = ['data' => ' ',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400);
            }
            
           if( $actividad = \App\Actividad::find($id)){
                $actividad->descripcion = $request->json('descripcion');
                $actividad->detalle = $request->json('detalle');
                $actividad->fecha_inicio = $request->json('fecha_inicio');
                $actividad->fecha_fin = $request->json('fecha_fin');
                $actividad->save();
                
                $actividades = $actividad::all()->where('deleted_at',null);//select('id','descripcion','detalle','fecha_inicio','fecha_fin')->get();
            
                $data = array('actividades'=>$actividades,'actividad'=>$actividad);
                $resultado = ['data' =>$data,'message' => 'succes'];
                
                return response()->json($resultado, 200);
           }else{
                $resultado = ['data' => ' ',
                              'message' => 'Formato de entrada incorrecto'];
                return response()->json($resultado, 400); 
           }
        }
    }
    
    public function delete($id){
       if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['data'=>'',
                          'message'=>"no autorizado"];
            return response()->json($resultado, 401);    
        }else{
            if($actividad = \App\Actividad::where('deleted_at',null)->find($id)){
                $actividad->delete();
            
                $actividades = \App\Actividad::all()->where('deleted_at',null);//select('id','descripcion','detalle','fecha_inicio','fecha_fin')->get();
            
                $data = array('actividad'=>$actividades);
                $resultado = ['data' =>$data,'message' => 'success'];
                return response()->json($resultado, 200);
            }else{
                $resultado = ['data' => ' ',
                              'message' => 'Id no encontrado o Borrado'];
                return response()->json($resultado, 400); 
            }
        }
    } 
		

    /*Movimientos*/
    public function getMovimiento($id){
        if($actividad = \App\Actividad::find($id)){
           
            $movimiento = new \App\Movimiento;
            
            //lista todos los movimientos
            $ingresos = $movimiento::where('actividad_id',$id)->where('tipo','I')->where('deleted_at',null)
                                        ->select('fecha','descripcion','tipo','monto')->get();
                                                   
            $egresos = $movimiento::where('actividad_id',$id)->where('tipo','E')->where('deleted_at',null)
                                        ->select('fecha','descripcion','tipo','monto')->get();
            
            
            $mov = array('ingresos'=>$ingresos, 'egresos'=>$egresos);
            $data = array ('actividad'=>$actividad,'movimientos'=>$mov );
            
            
            
            $resultado = ['data' => $data,'message' => 'succes'];
                return response()->json($resultado, 200);
        }
    }

    
    public function postMovimiento(Request $request){
        if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['data'=>'',
                          'message'=>"no autorizado"];
            return response()->json($resultado, 401);     
        }
        else {
            //validamos
            $validator = Validator::make($request->json()->all(), [
                'actividad_id'=>'required|integer',
                'tipo' => 'required|string',
                'fecha' => 'required|date',
                'descripcion' => 'required|string',
                'monto' => 'required|numeric',
            ]);
        
        
            if ($validator->fails()) {
                $resultado = ['data' => ' ',
                             'message' => 'Formato de entrada incorrecto'];
                            return response()->json($resultado, 400);  
            }
            
           // return json_encode($request->json()->all());
            //agregamos
           
            $movimiento = new \App\Movimiento;
            
            $movimiento->actividad_id = $request->json('actividad_id');
            $movimiento->tipo = $request->json('tipo');
                       
            $movimiento->fecha = $request->json('fecha');
            $movimiento->descripcion = $request->json('descripcion');
            $movimiento->monto = $request->json('monto');
            $movimiento->user_id = Auth::user()->id;
            $movimiento->save();
            
            $movimientos = $movimiento::where('deleted_at',null)
                                        ->select('id','actividad_id','fecha','descripcion','tipo','monto')->get();
            
            $data = array('movimientos'=>$movimientos,'movimiento'=>$movimiento);
            $resultado = ['data' => $data,'mesage' => 'success'];
                return response()->json($resultado, 200);
        }
    }
    
    public function putMovimiento ($id, Request $request) {
        if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['data'=>'',
                          'message'=>"no autorizado"];
            return response()->json($resultado, 401);      
        }
        else {
            $validator = Validator::make($request->json()->all(), [
                'actividad_id'=>'required|integer',
                'tipo' =>'required|string',
                'fecha' => 'required|date',
                'descripcion' => 'required|string',
                'monto' => 'required|numeric',
            ]);
        
           if( $movimiento = \App\Movimiento::find($id)){
                $movimiento->actividad_id = $request->json('actividad_id');
                $movimiento->tipo = $request->json('tipo');
                $movimiento->fecha = $request->json('fecha');
                $movimiento->descripcion = $request->json('descripcion');
                $movimiento->monto = $request->json('monto');
                $movimiento->save();
                $movimientos = $movimiento::where('deleted_at',null)
                                            ->select('id','actividad_id','fecha','descripcion','tipo','monto')->get();
            
                $data = array('movimientos'=>$movimientos,'movimiento'=>$movimiento);
                $resultado = ['data' => $data,'mesage' => 'success'];
                return response()->json($resultado, 200);
                
           }else{
               $resultado = ['data' => ' ',
                           'message' => 'Datos no encontrado'];
                return response()->json($resultado, 400);   
           }
        }
    }
    
    public function deleteMovimiento($id){
       if( Auth::user()->tipo_usuario != "administrador") {
            $resultado = ['data'=>'',
                          'message'=>"no autorizado"];
            return response()->json($resultado, 401);      
        }else{
            if($movimiento = \App\Movimiento::find($id)){
                $movimiento->delete();
                
                $movimientos = $movimiento::where('deleted_at',null)
                                            ->select('id','actividad_id','fecha','descripcion','tipo','monto')->get();
            
                $data = array('movimientos'=>$movimientos);
                $resultado = ['data' => $data,'mesage' => 'success'];
                return response()->json($resultado, 200);
                
            }else{
                 $resultado = ['data' => ' ',
                               'message' => 'Datos no encontrado'];
                return response()->json($resultado, 400); 
            }
        }
    }   
}

