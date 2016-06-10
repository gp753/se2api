var app = angular.module('app', []);

app.controller('PrincipalCtrl', function($scope,$http,$location) {
    $scope.cargando = false;
    $scope.mensaje = '';
    $scope.error = '';
    $scope.votando = false;
    
    
    function cargandoEstado (estado) {
        $scope.cargando = estado;
    };
    
    $scope.actualizarDiscusiones = function (discusiones){
        $scope.discusiones = discusiones;
    };
    
    cargandoEstado(true);
    
    $http({
        method : "GET",
        url : "/api/cytparticipa/listarVotaciones",
        }).then(function mySucces(response) {
            $scope.votaciones = response.data.data.Votaciones;
            cargandoEstado(false);
        }, function myError(response) {
            cargandoEstado(false);
        }); 


        
    $scope.cargarVotacion = function(id) {
        $scope.votando = true;
        cargandoEstado(true);
        $http({
        method : "GET",
        url : "/api/cytparticipa/listarOpcionVotaciones/'" + id,
        }).then(function mySucces(response) {
            $scope.opciones = response.data.data.Discusion;
            $scope.comentarios = response.data.data.comentarios;
            cargandoEstado(false);
        }, function myError(response) {
            cargandoEstado(false);
        }); 
    }
    $scope.comentar = function(id, contenido) {
        $scope.leyendo = true;
        cargandoEstado(true);
        $http({
        method : "POST",
        url : "/api/cytparticipa/agregarDiscusionComentarios/" + id,
        data : {contenido:contenido}
        }).then(function mySucces(response) {
            $scope.discusion = response.data.data.Discusion;
            $scope.comentarios = response.data.data.comentarios;
            $scope.comentario = '';
            cargandoEstado(false);
        }, function myError(response) {
            $scope.error = response.data.message;
            cargandoEstado(false);
        }); 
    }
    


$scope.eliminar = function (id) {
      $scope.error = '';
      $scope.mensaje = '';
      cargandoEstado(true);
      $http({
        method : "DELETE",
        url : "/api/cytparticipa/eliminarDiscusiones/" + id,
        }).then(function mySucces(response) {
            $scope.discusiones = response.data.data.Discusiones;
            $scope.modificando = false;
            $scope.leyendo = false;
            cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')';
            cargandoEstado(false);
        });  
    };
    
$scope.agregar = function (id) {
      $scope.error = '';
      $scope.mensaje = '';
      cargandoEstado(true);
      $http({
        method : "POST",
        url : "/api/cytparticipa/agregarDiscusiones",
        data : {titulo:$scope.titulo, contenido:$scope.contenido}
        }).then(function mySucces(response) {
            $scope.discusiones = response.data.data.Discusiones;
            $scope.titulo = '';
            $scope.contenido = '';
            cargandoEstado(false);
        }, function myError(response) {
            $scope.error = response.data.message + ' (' + response.status + ')';
            cargandoEstado(false);
        });  
    };
 
 $scope.eliminarComentario = function (id) {
      $scope.error = '';
      $scope.mensaje = '';
      cargandoEstado(true);
      $http({
        method : "DELETE",
        url : "/api/cytparticipa/eliminarDiscusionComentarios/" + id,
        }).then(function mySucces(response) {
            $scope.comentarios = response.data.data.Comentarios;
            cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')';
            cargandoEstado(false);
        });  
    };

});