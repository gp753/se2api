<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

class VistaController extends Controller
{
    public function login () {
        
   $login = Auth::Check();
   
   $paises = array( ['id'=>'PY','descripcion'=>'Paraguay'],
                    ['id'=>'AR','descripcion'=>'Argentina'],
                    ['id'=>'BR','descripcion'=>'Brasil'],
                    ['id'=>'IT','descripcion'=>'Italia']);
       
   $documentos = array( ['id'=>'CI','descripcion'=>'Cédula de identidad'],
                    ['id'=>'DNI','descripcion'=>'D.N.I.'],
                    ['id'=>'PAS','descripcion'=>'Pasaporte']);
   
   

   $resultado = ['status' => 'success',
                          'data' => array('paises'=>$paises, 'documentos'=>$documentos, 'login'=>$login)];
                return response()->json($resultado, 200);
   }
   
   public function academico(){
       $login = Auth::Check();
       
       //lista de carreras de sapientia
       $carreras = array( ['id'=>'IF','descripcion'=>'Ingeniería Informática'],
                    ['id'=>'AS','descripcion'=>'Análisis de sistemas']);
       
        //semestres de sapientia
       $periodos = json_decode(file_get_contents('http://api.sapientia.tk/v1/semestres'),true);
       
       $horarios = null;
       if (Auth::Check()==true){
           $horarios = \App\Horario::where('user_id',Auth::User()->id)->get();
       }
        

       
       $resultado = ['status' => 'success',
                          'data' => array('login'=>$login, 
                              'carreras'=>$carreras, 
                              'periodos'=>$periodos['data']['horario'],
                              'horarios'=>$horarios)];
                return response()->json($resultado, 200);
       
       
   
                
                
   }
   
   public function register () {
        

       Auth::logout();
 
   
   $paises = array( ['id'=>'PY','descripcion'=>'Paraguay'],
                    ['id'=>'AR','descripcion'=>'Argentina'],
                    ['id'=>'BR','descripcion'=>'Brasil'],
                    ['id'=>'IT','descripcion'=>'Italia']);
       
   $documentos = array( ['id'=>'CI','descripcion'=>'Cédula de identidad'],
                    ['id'=>'DNI','descripcion'=>'D.N.I.'],
                    ['id'=>'PAS','descripcion'=>'Pasaporte']);
   
   

   $resultado = ['status' => 'success',
                          'data' => array('paises'=>$paises, 'documentos'=>$documentos)];
                return response()->json($resultado, 200);
   }
   
   public function discusiones () {
       $user=null;
       $administrador=false;
       if (Auth::Check()){
           $user = Auth::User()->id;
           if (Auth::User()->tipo_usuario=='administrador'){
               $administrador=true;
           }
       }
       
       $resultado = ['data' => array('login'=>Auth::Check(),
                                     'user'=>$user,
                                    'administrador'=>$administrador)];
                return response()->json($resultado, 200);
   }
   
}
