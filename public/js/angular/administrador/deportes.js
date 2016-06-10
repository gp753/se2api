var app = angular.module('app', []);

app.controller('PrincipalCtrl', function($scope,$http) {
    $scope.cargando = false;
    $scope.error = '';
    $scope.mensaje = '';
    $scope.vista = 'deportes';
    
    //scopes principales
    //deportes
    $scope.actualizarDeportes = function (deportes){
        $scope.deportes = deportes;
    };
    //equipos
    $scope.actualizarEquipos = function (equipos){
        $scope.equipos = equipos;
    };
    //jugadores
    $scope.actualizarJugadores = function (jugadores){
        $scope.jugadores = jugadores;
    };

    //cargando
    $scope.cargandoEstado = function(estado) {
        $scope.cargando = estado;
    };
    
    //vistas
    $scope.establecerVista = function (vista) {
        $scope.vista = vista;
    };

    $scope.cargandoEstado(true);
    $http({
        method : "GET",
        url : "/api/deportes"
        }).then(function mySucces(response) {
            $scope.deportes = response.data.data;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    $scope.cargandoEstado(true);
    $http({
        method : "GET",
        url : "/api/equipos"
        }).then(function mySucces(response) {
            $scope.equipos = response.data.data.equipos;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    $scope.cargandoEstado(true);
    $http({
        method : "GET",
        url : "/api/jugadores"
        }).then(function mySucces(response) {
            $scope.jugadores = response.data.data.jugadores;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        });     
    
});

app.controller('DeporteCtrl', function($scope, $http) {
    $scope.agregar = function() {
        $scope.cargandoEstado(true);
        $http({
        method : "POST",
        url : "/api/deportes",
        data : {descripcion: $scope.descripcion}
        }).then(function mySucces(response) {
            $scope.deportes = response.data.data.deporte;
            $scope.descripcion = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    };
    $scope.eliminar = function(id) {
        $scope.cargandoEstado(true);
        $http({
        method : "DELETE",
        url : "/api/deportes/" + id,
        data : {descripcion: $scope.descripcion}
        }).then(function mySucces(response) {
            $scope.deportes = response.data.data.deporte;
            $scope.descripcion = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    };
});

app.controller('EquipoCtrl', function($scope, $http) {
    $scope.agregar = function() {
        $scope.cargandoEstado(true);
        $http({
        method : "POST",
        url : "/api/equipos",
        data : {nombre: $scope.descripcion,
                dep_id: $scope.deporte.id}
        }).then(function mySucces(response) {
            $scope.actualizarEquipos(response.data.data.equipos);
            $scope.descripcion = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    };
    $scope.eliminar = function(id) {
        $scope.cargandoEstado(true);
        $http({
        method : "DELETE",
        url : "/api/equipos/" + id
        }).then(function mySucces(response) {
            $scope.equipos = response.data.data.equipos;
            $scope.descripcion = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    };
});

app.controller('JugadorCtrl', function($scope, $http) {
    $scope.agregar = function() {
        $scope.cargandoEstado(true);
        $http({
        method : "POST",
        url : "/api/jugadores",
        data : {nombre: $scope.nombre,
                usu_id: null}
        }).then(function mySucces(response) {
            $scope.actualizarJugadores(response.data.data.jugadores);
            $scope.nombre = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    };
    $scope.eliminar = function(id) {
        $scope.cargandoEstado(true);
        $http({
        method : "DELETE",
        url : "/api/jugadores/" + id
        }).then(function mySucces(response) {
            $scope.actualizarJugadores(response.data.data.jugadores);
            $scope.descripcion = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    };
});



app.controller('JugadorEquipoCtrl', function($scope, $http) {
    $scope.agregar = function() {
        $scope.cargandoEstado(true);
        $http({
        method : "POST",
        url : "/api/jugadores",
        data : {nombre: $scope.nombre,
                usu_id: null}
        }).then(function mySucces(response) {
            $scope.actualizarJugadores(response.data.data.jugadores);
            $scope.nombre = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    };
    $scope.eliminar = function(id) {
        $scope.cargandoEstado(true);
        $http({
        method : "DELETE",
        url : "/api/jugadores/" + id
        }).then(function mySucces(response) {
            $scope.actualizarJugadores(response.data.data.jugadores);
            $scope.descripcion = '';
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    };
});


