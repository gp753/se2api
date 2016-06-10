var app = angular.module('app', []);

app.controller('PrincipalCtrl', function($scope,$http,$location) {
    $scope.cargando = false;
    $scope.mensaje = '';
    $scope.error = '';
    
    $scope.cargandoEstado = function(estado) {
        $scope.cargando = estado;
    };
    
    $scope.actualizarNoticias = function (noticias){
        $scope.noticias = noticias;
    };
    
    $http({
        method : "GET",
        url : "/api/noticias",
        }).then(function mySucces(response) {
            $scope.actualizarNoticias(response.data.data.noticias);
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
        
    $scope.cargarNoticia = function(id) {
        $scope.leyendo = true;
        $scope.cargandoEstado(true);
        $http({
        method : "GET",
        url : "/api/noticias/comentarios/" + id,
        }).then(function mySucces(response) {
            $scope.noticia = response.data.data.noticia;
            $scope.comentarios = response.data.data.comentarios;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    }
    $scope.comentar = function(id, contenido) {
        $scope.leyendo = true;
        $scope.cargandoEstado(true);
        $http({
        method : "POST",
        url : "/api/noticias/comentarios/" + id,
        data : {contenido:contenido}
        }).then(function mySucces(response) {
            $scope.noticia = response.data.data.noticia;
            $scope.comentarios = response.data.data.comentarios;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.error = response.data.message;
            $scope.cargandoEstado(false);
        }); 
    }
    
});

app.controller('AgregarCtrl', function($scope,$http) {
    $scope.cargandoEstado(false);
    $scope.agregar = function (titulo, contenido) {
      $scope.a_error = '';
      $scope.a_mensaje = '';
      $scope.cargandoEstado(true);
      $http({
        method : "POST",
        url : "/api/noticias",
        data : { titulo: titulo,
                contenido: contenido }
        }).then(function mySucces(response) {
            $scope.actualizarNoticias(response.data.data.noticias);
            $scope.a_mensaje = 'Noticia agregada.';
            $scope.a_titulo = '';
            $scope.a_contenido = '';
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
        $scope.id = x.id;
    };
    
    $scope.modificar = function () {
      $scope.error = '';
      $scope.mensaje = '';
      $scope.cargandoEstado(true);
      $http({
        method : "PUT",
        url : "/api/noticias/" + $scope.id,
        data : { titulo: $scope.titulo,
                contenido: $scope.contenido }
        }).then(function mySucces(response) {
            $scope.actualizarNoticias(response.data.data.noticias);
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
        url : "/api/noticias/" + id,
        }).then(function mySucces(response) {
            $scope.actualizarNoticias(response.data.data.noticias);
            $scope.modificando = false;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.a_error = response.data.message + ' (' + response.status + ')'
            $scope.cargandoEstado(false);
        });  
    };
  
  
});
