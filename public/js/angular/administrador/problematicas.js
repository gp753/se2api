var app = angular.module('app', []);

app.controller('PrincipalCtrl', function($scope,$http) {
    $scope.cargando = false;
    
    $scope.cargandoEstado = function(estado) {
        $scope.cargando = estado;
    };
    
    $scope.actualizarProblematicas = function (problematicas){
        $scope.problematicas = problematicas;
    };
    
    $http({
        method : "GET",
        url : "/api/cytparticipa/listarProblematicas",
        }).then(function mySucces(response) {
            $scope.problematicas = response.data.data.problematicas;
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
        url : "/api/cytparticipa/crearProblematicas",
        data : { titulo: $scope.titulo,
                contenido: $scope.contenido}
        }).then(function mySucces(response) {
            $scope.actualizarProblematicas(response.data.data.Problematicas);
            $scope.mensaje = 'Problematica agregada.';
            $scope.titulo = '';
            $scope.contenido = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.error = response.data.message + ' (' + response.status + ')'
            $scope.cargandoEstado(false);
        });  
    };

});

app.controller('ModificarCtrl', function($scope, $http) {
    
    $scope.iniciarModificar = function (x) {
        $scope.modificando=true;
        $scope.titulo = x.titulo;
        $scope.contenido = x.contenido;
        $scope.id = x.id;
    };
    
    $scope.modificar = function () {
      $scope.error = '';
      $scope.mensaje = '';
      $scope.cargandoEstado(true);
      $http({
        method : "PUT",
        url : "/api/cytparticipa/editarProblematicas/" + $scope.id,
        data : { titulo: $scope.titulo,
                contenido: $scope.contenido }
        }).then(function mySucces(response) {
            $scope.actualizarProblematicas(response.data.data.Problematicas);
            $scope.modificando = false;
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
        url : "/api/cytparticipa/eliminarProblematicas/" + id,
        }).then(function mySucces(response) {
            $scope.actualizarProblematicas(response.data.data.Problematicas);
            $scope.modificando = false;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')'
            $scope.cargandoEstado(false);
        });  
    };
  
  
});
