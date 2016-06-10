var app = angular.module('app', []);
app.controller('PrincipalCtrl', function($scope,$http) {
    $scope.cargando = false;
    $scope.cargandoEstado = function(estado) {
        $scope.cargando = estado;
    };
    
    $scope.dias = ['1','2','3','4','5','6'];
    
    $http({
        method : "GET",
        url : "/api/vistas/academico",
        }).then(function mySucces(response) {
            $scope.carreras = response.data.data.carreras;
            $scope.periodos = response.data.data.periodos;
            $scope.horarios = response.data.data.horarios;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        }); 
    $scope.mostrar = function () {
        
       $http({
        method : "GET",
        url : "/api/sapientia/horarios/"+ $scope.periodo.semestre_actual +"/"+ $scope.periodo.ano_actual +"/"+ $scope.carrera.id +"",
        }).then(function mySucces(response) {
            $scope.horario = response.data.data.horario;
            $scope.semestres = response.data.data.semestres;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            $scope.cargandoEstado(false);
        });  
    }    
});

app.controller('AgregarCtrl', function($scope) {
    $scope.cargandoEstado(true);

});

app.controller('ModificarCtrl', function($scope) {
    $scope.cargandoEstado(true);

});