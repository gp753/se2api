var app = angular.module('app', []);

app.controller('PrincipalCtrl', function($scope,$http) {
    $scope.cargando = false;
    
    $scope.cargandoEstado = function(estado) {
        $scope.cargando = estado;
    };
    
    $scope.actualizarVotaciones = function (votaciones){
        $scope.votaciones = votaciones;
    };
    
    $http({
        method : "GET",
        url : "/api/cytparticipa/listarVotaciones",
        }).then(function mySucces(response) {
            $scope.votaciones = response.data.data.Votaciones;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    
});

app.controller('AgregarCtrl', function($scope,$http) {
    $scope.cargandoEstado(false);
    $scope.agregar = function () {
      $scope.error = '';
      $scope.mensaje = '';
      $scope.cargandoEstado(true);
      $http({
        method : "POST",
        url : "/api/cytparticipa/crearVotaciones",
        data : { titulo: $scope.titulo,
                contenido: $scope.contenido,
                fecha_inicio: $scope.fecha_inicio,
                fecha_fin: $scope.fecha_fin}
        }).then(function mySucces(response) {
            $scope.actualizarVotaciones(response.data.data.votaciones);
            $scope.mensaje = 'Votacion agregada.';
            $scope.titulo = '';
            $scope.contenido = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')'
            $scope.cargandoEstado(false);
        });  
    };

});

app.controller('ModificarCtrl', function($scope, $http) {
    
    $scope.iniciarModificar = function (x) {
        $scope.modificando=true;
        $scope.titulo = x.titulo;
        $scope.contenido = x.contenido;
        $scope.fecha_inicio = x.fecha_inicio;
        $scope.fecha_fin = x.fecha_fin;
        $scope.id = x.id;
        $http({
        method : "GET",
        url : "/api/cytparticipa/listarOpcionVotaciones/" + $scope.id,
        }).then(function mySucces(response) {
            $scope.opciones = response.data.data.Opciones;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')'
            $scope.cargandoEstado(false);
        }); 
        
    };
    
    $scope.modificar = function () {
      $scope.error = '';
      $scope.mensaje = '';
      $scope.cargandoEstado(true);
      $http({
        method : "PUT",
        url : "/api/cytparticipa/editarVotaciones/" + $scope.id,
        data : { titulo: $scope.titulo,
                contenido: $scope.contenido,
                fecha_inicio : $scope.fecha_inicio,
                fecha_fin : $scope.fecha_fin}
        }).then(function mySucces(response) {
            $scope.votaciones = response.data.data.Votaciones;
            $scope.modificando = false;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')'
            $scope.cargandoEstado(false);
        });  
    };
    
    $scope.agregarOpcion = function () {
      $scope.error = '';
      $scope.mensaje = '';
      $scope.cargandoEstado(true);
      $http({
        method : "POST",
        url : "/api/cytparticipa/agregarOpcionVotaciones/" + $scope.id,
        data : { descripcion: $scope.descripcion,
                valor: $scope.valor }
        }).then(function mySucces(response) {
            $scope.opciones = response.data.data.opciones;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')'
            $scope.cargandoEstado(false);
        });  
    };
    
    $scope.eliminar = function (id) {
      $scope.error = '';
      $scope.mensaje = '';
      $scope.cargandoEstado(true);
      $http({
        method : "DELETE",
        url : "/api/cytparticipa/eliminarVotaciones/" + id,
        }).then(function mySucces(response) {
            $scope.actualizarVotaciones(response.data.data.Votaciones);
            $scope.modificando = false;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')'
            $scope.cargandoEstado(false);
        });  
    };
  
  
});
