<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.

*/


    //VISTAS
        //alumno
        Route::get('/noticias',function (){return view('noticias');});
        Route::get('/discusiones',function (){return view('discusiones');});
        Route::get('/problematicas',function (){return view('discusiones');});
        Route::get('/votaciones',function (){return view('votaciones');});
        Route::get('/horarios',function (){return view('horarios');});
        //Route::get('/login',function (){return view('login');});
        //Route::get('/horarios',function (){return view('horarios');});
        
        //Route::get('/index',function (){return view('index');});
        
        //administrador
        //noticias
        Route::get('/administrador/noticias',function (){
            if (Auth::Check()){
                if (Auth::User()->tipo_usuario=="administrador"){
                    return view('administrador.noticias');
                }
                else {
                    return redirect('/');
                }
            }
        });
        //votaciones
        Route::get('/administrador/votaciones',function (){
            if (Auth::Check()){
                if (Auth::User()->tipo_usuario=="administrador"){
                    return view('administrador.votaciones');
                }
                else {
                    return redirect('/');
                }
            }
        });
        //problematicaas
        Route::get('/administrador/problematicas',function (){
            if (Auth::Check()){
                if (Auth::User()->tipo_usuario=="administrador"){
                    return view('administrador.problematicas');
                }
                else {
                    return redirect('/');
                }
            }
        });
        
        //deportes
        Route::get('/administrador/deportes',function (){
            if (Auth::Check()){
                if (Auth::User()->tipo_usuario=="administrador"){
                    return view('administrador.deportes');
                }
                else {
                    return redirect('/');
                }
            }
        });
        
        //horarioPersonalizado
        Route::get('/horarioPersonalizado',function (){
            if (Auth::Check()==false){
                return redirect('/');
            }
            else {
               return view('horarioPersonalizado'); 
            }
        });
        
        
        //Route::get('/register',function (){return view('register');});
        //Route::get('/',function (){return view('index');});
        //Route::get('/index',function (){return view('index');});
        //Route::get('/academico',function (){return view('academico');});
        //Route::get('/noticias',function (){return view('noticias');});
        //Route::get('/academico/personalizado',function (){return view('academico.personalizado');});
        //Route::get('/logout',function (){Auth::logout();  return redirect('/');});

    //API
    //RUTAS 
    //  LOGIN           - no hay documentacion 
        Route::get('/api/login/cerrarSesion','LoginController@cerrarSesion');
        Route::post('/api/login/iniciarSesion','LoginController@iniciarSesion');
        Route::post('/api/login/crearCuenta','LoginController@crearCuenta');
        Route::get('/activar/{email}/{token}','LoginController@activarCuenta')
                ->where('email','[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]+')
                ->where('token','[A-Za-z0-9]{10,10}');
    //  VISTAS          - no hay documentacion
        Route::get('/api/vistas/login','VistaController@login');
        Route::get('/api/vistas/academico','VistaController@academico');
        Route::get('/api/vistas/register','VistaController@register');
        Route::get('/api/vistas/discusiones','VistaController@discusiones');
 
    // SAPIENTIA        - no hay documentacion
        Route::get('/api/sapientia/horarios/{semestre}/{ano}/{carrera}','SapientiaController@horarios');
    
    //  ACADEMICO
        //Horario
        Route::get('/api/horarios','HorarioController@listarHorariosPersonalizados');
        Route::get('/api/horarios/personalizados/listaMaterias/{id}/{semestre}/{ano}/{carrera}','HorarioController@listarMateriasHorarioPersonalizado');
        Route::get('/api/horarios/personalizados/{id}','HorarioController@mostrarHorarioPersonalizado')->where('id', '[0-9]+');
        Route::post('/api/horarios/personalizados','HorarioController@agregarHorarioPersonalizado');
        Route::delete('/api/horarios/personalizados/{id}','HorarioController@borrarHorarioPersonalizado')->where('id', '[0-9]+');
        
        //Horario_Detalle
        Route::post('/api/horarios/personalizados/detalle/{id}','HorarioController@agregarHorarioPersonalizadoDetalle')->where('id', '[0-9]+');
        Route::delete('/api/horarios/personalizados/detalle/{id}','HorarioController@borrarHorarioPersonalizadoDetalle')->where('id', '[0-9]+');
    
    //  NOTICIAS
        //Noticia
        Route::get('/api/noticias', 'NoticiaController@listarNoticias');
        Route::post('/api/noticias', 'NoticiaController@agregarNoticia');
        Route::put('/api/noticias/{id}', 'NoticiaController@modificarNoticia')->where('id', '[0-9]+');
        Route::delete('/api/noticias/{id}', 'NoticiaController@eliminarNoticia')->where('id', '[0-9]+');
        
        //Noticia_Comentario
        Route::get('/api/noticias/comentarios/{id}', 'NoticiaController@listarComentarios')->where('id', '[0-9]+');    
        Route::post('/api/noticias/comentarios/{id}', 'NoticiaController@agregarNoticiaComentario')->where('id', '[0-9]+');
        Route::delete('/api/noticias/comentarios/{id}', 'NoticiaController@eliminarNoticiaComentario')->where('id', '[0-9]+');
        
    
    //	DEPORTES
	Route::get('/api/deportes','DeportesController@get_deporte');
        Route::post('api/deportes', 'DeportesController@post_deporte');
        Route::delete('api/deportes/{id}','DeportesController@delete_deporte');
        Route::put('api/deportes/{id}','DeportesController@put_deporte');
		
	Route::get('/api/equipos','DeportesController@get_equipo');
	Route::post('/api/equipos','DeportesController@post_equipo');
        Route::put('/api/equipos/{id}','DeportesController@put_equipo');
        Route::delete('/api/equipos/{id}','DeportesController@delete_equipo');
		
	Route::post('/api/jugador/{jug_id}/equipo/{equ_id}','DeportesController@post_jug_equ');
	Route::delete('/api/jugador/{jug_id}/equipo/{equ_id}','DeportesController@delete_jug_equ');
	Route::post('/api/jugadores','DeportesController@post_jugador');
	Route::put('/api/jugadores/{id}','DeportesController@put_jugador');
        Route::delete('/api/jugadores/{id}','DeportesController@delete_jugador');
        Route::get('/api/jugadores','DeportesController@get_jugador');
		
	Route::get('/api/torneos','DeportesController@get_torneo');
	Route::post('/api/torneos','DeportesController@post_torneo');
	Route::put('/api/torneos/{id}','DeportesController@put_torneo');
	Route::delete('/api/torneos/{id}','DeportesController@delete_torneo');
	Route::get('/api/torneoHorario/{id}','DeportesController@get_horario_torneo');
		
	Route::post('/api/partidos','DeportesController@post_partido');
	Route::put('/api/partidos/{id}','DeportesController@put_partido');
	Route::delete('/api/partidos/{id}','DeportesController@delete_partido');

    //CYTPARTICIPA   

    //  DISCUSION

        Route::post('/api/cytparticipa/agregarDiscusiones','cytparticipaController@agregarDiscusiones');
        Route::put('/api/cytparticipa/editarDiscusiones/{id}','cytparticipaController@editarDiscusiones')->where('id', '[0-9]+');;
        Route::delete('/api/cytparticipa/eliminarDiscusiones/{id}','cytparticipaController@eliminarDiscusiones')->where('id', '[0-9]+');;
          
        //  Discusion_Comentarios 
        Route::post('/api/cytparticipa/agregarDiscusionComentarios/{id}','cytparticipaController@agregarDiscusionComentarios')->where('id', '[0-9]+');;
        Route::delete('/api/cytparticipa/eliminarDiscusionComentarios/{id}','cytparticipaController@eliminarDiscusionComentarios')->where('id', '[0-9]+');;
           
        //  Listar Discusion
        Route::get('/api/cytparticipa/listarDiscusiones','cytparticipaController@listarDiscusiones');
        //  Listar Discusion Comentarios
        Route::get('/api/ceytparticipa/listarDiscusionComentarios/{id}','cytparticipaController@listarDiscusionComentarios')->where('id', '[0-9]+');;



   //  PROBLEMATICA 
        Route::post('/api/cytparticipa/crearProblematicas','cytparticipaController@crearProblematicas');
        Route::put('/api/cytparticipa/editarProblematicas/{id}','cytparticipaController@editarProblematicas')->where('id', '[0-9]+');;
        Route::delete('/api/cytparticipa/eliminarProblematicas/{id}','cytparticipaController@eliminarProblematicas')->where('id', '[0-9]+');;
        
     //  Problematica Comentarios
        Route::post('/api/cytparticipa/agregarProblematicaComentarios/{id}','cytparticipaController@agregarProblematicaComentarios')->where('id', '[0-9]+');;
        Route::delete('/api/cytparticipa/eliminarProblematicaComentarios/{id}','cytparticipaController@eliminarProblematicaComentario')->where('id', '[0-9]+');;

     //  Problematica Listar
        Route::get('/api/cytparticipa/listarProblematicas','cytparticipaController@listarProblematicas');

        //  Problematica Listar Comentarios
        Route::get('/api/cytparticipa/listarProblematicaComentarios/{id}','cytparticipaController@listarProblematicaComentarios')->where('id', '[0-9]+');;


        // VOTACION
        Route::post('/api/cytparticipa/crearVotaciones','cytparticipaController@crearVotaciones');
        Route::put('/api/cytparticipa/editarVotaciones/{id}','cytparticipaController@editarVotaciones')->where('id', '[0-9]+');;
        Route::delete('/api/cytparticipa/eliminarVotaciones/{id}','cytparticipaController@eliminarVotaciones')->where('id', '[0-9]+');;
       
       // votacion Opciones
        Route::post('/api/cytparticipa/agregarOpcionVotaciones/{id}','cytparticipaController@agregarOpcionVotaciones')->where('id', '[0-9]+');;
        Route::put('/api/cytparticipa/editarOpcionVotaciones/{id}','cytparticipaController@editarOpcionVotaciones')->where('id', '[0-9]+');;
        Route::delete('/api/cytparticipa/eliminarOpcionVotaciones/{id}','cytparticipaController@eliminarOpcionVotaciones')->where('id', '[0-9]+');;
        
        // VOTAR
        Route::post('/api/cytparticipa/votar','cytparticipaController@votar');//pasar id de la 
        Route::delete('/api/cytparticipa/eliminarVoto/{id}','cytparticipaController@eliminarVoto')->where('id', '[0-9]+');;//pasar id del comentario

        //  listar votaciones y opciones
        Route::get('/api/cytparticipa/listarVotaciones','cytparticipaController@listarVotaciones');
        Route::get('/api/cytparticipa/listarOpcionVotaciones/{id}','cytparticipaController@listarOpcionVotaciones')->where('id', '[0-9]+');;
    
   

    
    
    
    
    
    
    
    Route::get('/api/partidoResultados/{id}','DeportesController@get_tantos_partido');

   

    Route::post('/api/partidoResultados','DeportesController@post_par_tan');
    Route::put('/api/partido_tanto/{id}','DeportesController@put_par_tan');
    Route::delete('/api/partido_tanto/{id}','DeportesController@delete_par_tan');

  
   
    
    Route::put('/api/partido_equipo/{id}','DeportesController@put_par_equ');
    Route::delete('/api/partido_equipo/{id}','DeportesController@delete_par_equ');

    Route::post('/api/amonestaciones','DeportesController@post_amonestacion');
    Route::delete('/api/amonestacion/{id}','DeportesController@delete_amonestacion');
    Route::get('/api/amonestaciones/jugador/{id}','DeportesController@get_jug_amonestacion');
    Route::get('/api/amonestaciones/equipo/{id}','DeportesController@get_equ_amonestacion');
    Route::get('/api/amonestaciones/partido/{id}','DeportesController@get_par_amonestacion');

    Route::get('/api/equipos_jugadores/{id}','DeportesController@get_equ_jug');

    Route::post('/api/partido/{par_id}/equipo/{equ_id}','DeportesController@post_par_equ');

    Route::get('/api/torneo/{id}/partidos','DeportesController@get_torneo_status');
    
    Route::get('/api/torneo/{id}/ranking','DeportesController@get_torneo_ranking');

    Route::get('/api/torneos/{id}','DeportesController@get_partidos_torneo_list'); //lista los partidos de un torneo
    Route::get('/api/partido/{id}/equipo','DeportesController@get_par_equipos');
    Route::get('/api/torneo/{id}/equipos','DeportesController@get_equipos_torneo');
    Route::get('/api/torneo/{id}/goleadores','DeportesController@get_goleadores');
    Route::get('/api/torneo/datos','DeportesController@get_torneo_datos');
    