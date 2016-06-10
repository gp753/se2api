@extends('layouts.principal')
@section('contenido')

<div class="row" >
    <div class="col-lg-12">
    <h1 class="page-header">
    Horarios
</h1>
</div>
</div>

<div class="row"  ng-init="error='';mensaje='';">
   <div class="col-lg-6">
      <div class="alert alert-danger" ng-show="error!=''">
         @{{error}}
      </div>
      <div class="alert alert-success" ng-show="mensaje!=''">
         @{{mensaje}}
      </div>
      <form role="form">
         <div class="form-group">
            <label>Carrera</label>
            <select class="form-control" ng-model="carrera" ng-options="x.descripcion for x in carreras">
            </select>
         </div>
         <div class="form-group">
            <label>Periodo</label>
            <select ng-model="periodo" ng-options="' semestre ' + x.semestre_actual +' del '+ x.ano_actual for x in periodos" class="form-control"> </select>

         </div>
         <button class="btn btn-default" ng-click="mostrar()">Mostrar horario</button>
      </form>
       
   </div>
</div>
<hr>
<div class="row" ng-repeat="x in semestres">
   <div class="col-lg-12">
       <h2>Semestre @{{x}} - @{{carrera.descripcion}}</h2>
      <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Lunes</th>
                                        <th>Martes</th>
                                        <th>Miercoles</th>
                                        <th>Jueves</th>
                                        <th>Viernes</th>
                                        <th>Sabados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td ng-repet="x3 in dias">
                        <div  ng-repeat="x2 in horario | filter:{semestre:x,dia:'1'}">
                                          <h5>@{{x2.nombre_materia}}</h5>
                                          <h6>@{{x2.inicio}}-@{{x2.fin}}</h6>
                                          <h6>@{{x2.profesor}}</h6>
                                          <h6>@{{x2.aula}}</h6>
                                       </div>
                                        </td>
                                        <td>
                        <div  ng-repeat="x2 in horario | filter:{semestre:x,dia:'2'}">
                                          <h5>@{{x2.nombre_materia}}</h5>
                                          <h6>@{{x2.inicio}}-@{{x2.fin}}</h6>
                                          <h6>@{{x2.profesor}}</h6>
                                          <h6>@{{x2.aula}}</h6>
                                       </div>
                                        </td>
                                        <td>
                        <div  ng-repeat="x2 in horario | filter:{semestre:x,dia:'3'}">
                                          <h5>@{{x2.nombre_materia}}</h5>
                                          <h6>@{{x2.inicio}}-@{{x2.fin}}</h6>
                                          <h6>@{{x2.profesor}}</h6>
                                          <h6>@{{x2.aula}}</h6>
                                       </div>
                                        </td>
                                        <td>
                        <div  ng-repeat="x2 in horario | filter:{semestre:x,dia:'4'}">
                                          <h5>@{{x2.nombre_materia}}</h5>
                                          <h6>@{{x2.inicio}}-@{{x2.fin}}</h6>
                                          <h6>@{{x2.profesor}}</h6>
                                          <h6>@{{x2.aula}}</h6>
                                       </div>
                                        </td>
                                        <td>
                        <div  ng-repeat="x2 in horario | filter:{semestre:x,dia:'5'}">
                                          <h5>@{{x2.nombre_materia}}</h5>
                                          <h6>@{{x2.inicio}}-@{{x2.fin}}</h6>
                                          <h6>@{{x2.profesor}}</h6>
                                          <h6>@{{x2.aula}}</h6>
                                       </div>
                                        </td>
                                                                        <td>
                        <div  ng-repeat="x2 in horario | filter:{semestre:x,dia:'6'}">
                                          <h5>@{{x2.nombre_materia}}</h5>
                                          <h6>@{{x2.inicio}}-@{{x2.fin}}</h6>
                                          <h6>@{{x2.profesor}}</h6>
                                          <h6>@{{x2.aula}}</h6>
                                       </div>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
       
   </div>
</div>
@endsection
@section('javascript')
<script src="js/angular/horarios.js"></script>
@endsection