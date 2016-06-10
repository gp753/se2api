<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;


use App\Http\Requests;

class SapientiaController extends Controller
{
    public function horarios($semestre, $ano, $carrera){
        $horarios = json_decode(file_get_contents('http://api.sapientia.tk/v1/horarios?semestre='. $semestre .'&ano='. $ano .'&carrera='.$carrera),true);
        $horarios = $horarios['data']['horarios'];
        if ($carrera=='IF') {
            $semestres2=[2,3,4,5,6,7,8,9,10];
        }
        else {
            $semestres2=[1,2,3,4,5,6,7,8];
        }
        
        
        $resultado = ['status' => 'success',
                          'data' => array('horario'=>$horarios, 'semestres'=>$semestres2)];
                return response()->json($resultado, 200);
        
        
    }
}
