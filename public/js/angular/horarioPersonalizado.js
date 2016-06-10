var app = angular.module('app', []);
app.controller('PrincipalCtrl', function($scope,$http) {
    $scope.cargando = false;
    $scope.cargandoEstado = function(estado) {
        $scope.cargando = estado;
    };
    
    $scope.dias = ['1','2','3','4','5','6'];
    $scope.creando = false;
    
    $scope.cargandoEstado(true);
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
    };
    
    $scope.crear = function () {
        $scope.error = '';
        $scope.mensaje = '';
        $scope.creando = true;
        $scope.cargandoEstado(true);
       $http({
        method : "POST",
        url : "/api/horarios/personalizados",
        data : {titulo : $scope.titulo,
                semestre : $scope.periodo.semestre_actual,
                ano: $scope.periodo.ano_actual,
                carrera: $scope.carrera.id}
        }).then(function mySucces(response) {
            $scope.materias = response.data.data.materias;
            $scope.carrera = $scope.carrera.id;
            $scope.ano_actual = $scope.periodo.ano_actual;
            $scope.semestre_actual = $scope.periodo.semestre_actual;
            $scope.id = response.data.data.horario.id;
            $scope.cargandoEstado(false);
            $scope.creando = true;
        }, function myError(response) {
            $scope.cargandoEstado(false);
        });  
    }; 
    
     $scope.agregar = function () {
        $scope.error = '';
        $scope.mensaje = '';
        $scope.cargandoEstado(true);
       $http({
        method : "POST",
        url : "/api/horarios/personalizados/detalle/" + $scope.id,
        data : {
                codigo_materia : $scope.materia.codigo_materia,
                ano_actual : $scope.ano_actual,
                semestre_actual : $scope.semestre_actual,
                carrera : $scope.carrera}
        }).then(function mySucces(response) {
            $scope.materias = response.data.data.lista;
            $scope.horario = response.data.data.horario_detalle;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            
            $scope.error = response.data.data.message;
            $scope.cargandoEstado(false);
        });  
    }; 
    
    $scope.eliminar = function (id) {
        $scope.error = '';
        $scope.mensaje = '';
        $scope.cargandoEstado(true);
       $http({
        method : "DELETE",
        url : "/api/horarios/personalizados/detalle/" + id,
        }).then(function mySucces(response) {
            $scope.materias = response.data.data.lista;
            $scope.horario = response.data.data.horario_detalle;
            $scope.cargandoEstado(false);
        }, function myError(response) {
            
            $scope.error = response.data.data.message;
            $scope.cargandoEstado(false);
        });  
    };
    
});

app.controller('AgregarCtrl', function($scope) {
    $scope.cargandoEstado(true);

});

app.controller('ModificarCtrl', function($scope) {
    $scope.cargandoEstado(true);

});