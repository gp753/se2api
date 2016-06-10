@extends('layouts.administrador')
@section('contenido')
<div class="row" >
   <div class="col-lg-12">
      <h1 class="page-header">
         Deportes
      </h1>
   </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="navbar navbar-default">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li ng-click="vista='deportes'"><a href="#">Deportes</a></li>
                                <li ng-click="vista='equipos'"><a href="#">Equipos</a></li>
                                <li ng-click="vista='jugadores'"><a href="#">Jugadores</a></li>
                                <li ng-click="vista='jugadores_equipos'"><a href="#">Jugadores y equipos</a></li>
                            </ul>
                        </div>
                        <!--/.nav-collapse -->
                    </div>
                </div>
    </div>  
</div>


<div class="row" ng-controller="DeporteCtrl" ng-hide="vista!='deportes'">
   <div class="col-lg-12">
      <div class="alert alert-danger" ng-show="error!=''">
         @{{error}}
      </div>
      <div class="alert alert-success" ng-show="mensaje!=''">
         @{{mensaje}}
      </div>
   </div>
   <div class="col-lg-12" ng-hide="modificando==true">
      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title"> Gestionar deportes </h3>
         </div>
         <div class="panel-body">
            <div class="table-responsive">
               <table class="table table-bordered table-hover table-striped">
                  <thead>
                     <tr>
                        <th>Nombre</th>
                        <th>Operaciones</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr ng-repeat="x in deportes">
                        <td>@{{x.descripcion}}</td>
                        <td >
                           <button class="btn btn btn-danger" ng-click="eliminar(x.id)"><i class="fa fa-fw fa-delete"></i> Eliminar </button>
                        </td>
                     </tr>
                     <tr>
                        <td><input class="form-control" ng-model="descripcion"></td>
                        <td >
                           <button class="btn btn btn-default" ng-click="agregar()"> Agregar deporte </button>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>



<div class="row" ng-controller="EquipoCtrl" ng-hide="vista!='equipos'">
   <div class="col-lg-12">
      <div class="alert alert-danger" ng-show="error!=''">
         @{{error}}
      </div>
      <div class="alert alert-success" ng-show="mensaje!=''">
         @{{mensaje}}
      </div>
   </div>
   <div class="col-lg-12" ng-hide="modificando==true">
      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title"> Gestionar equipos </h3>
         </div>
         <div class="panel-body">
            <div class="table-responsive">
               <table class="table table-bordered table-hover table-striped">
                  <thead>
                     <tr>
                        <th>Nombre</th>
                        <th>Operaciones</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr ng-repeat="x in equipos">
                        <td>@{{x.nombre}}</td>
                        <td >
                           <button class="btn btn btn-danger" ng-click="eliminar(x.id)"><i class="fa fa-fw fa-delete"></i> Eliminar </button>
                        </td>
                     </tr>
                     <tr>
                        <td>
                            <input class="form-control" ng-model="descripcion">
                            <select class="form-control" ng-model="deporte" ng-options="x.descripcion for x in deportes">
                            </select>
                        </td>
                        <td >
                           <button class="btn btn btn-default" ng-click="agregar()"> Agregar Equipo </button>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row" ng-controller="JugadorCtrl" ng-hide="vista!='jugadores'">
   <div class="col-lg-12">
      <div class="alert alert-danger" ng-show="error!=''">
         @{{error}}
      </div>
      <div class="alert alert-success" ng-show="mensaje!=''">
         @{{mensaje}}
      </div>
   </div>
   <div class="col-lg-12" ng-hide="modificando==true">
      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title"> Gestionar jugadores </h3>
         </div>
         <div class="panel-body">
            <div class="table-responsive">
               <table class="table table-bordered table-hover table-striped">
                  <thead>
                     <tr>
                        <th>Nombre</th>
                        <th>Operaciones</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr ng-repeat="x in jugadores">
                        <td>@{{x.nombre}}</td>
                        <td >
                           <button class="btn btn btn-danger" ng-click="eliminar(x.id)"><i class="fa fa-fw fa-delete"></i> Eliminar </button>
                        </td>
                     </tr>
                     <tr>
                        <td>
                            <input class="form-control" ng-model="nombre">
                        </td>
                        <td >
                           <button class="btn btn btn-default" ng-click="agregar()"> Agregar jugador </button>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row" ng-controller="JugadorEquipoCtrl" ng-hide="vista!='jugadores_equipos'">
   <div class="col-lg-12">
      <div class="alert alert-danger" ng-show="error!=''">
         @{{error}}
      </div>
      <div class="alert alert-success" ng-show="mensaje!=''">
         @{{mensaje}}
      </div>
   </div>
   <div class="col-lg-12" ng-hide="modificando==true">
      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title"> Gestionar jugadores y equipos </h3>
         </div>
         <div class="panel-body">
             <div class="col-lg-6">
      <div class="alert alert-danger" ng-show="error!=''">
            @{{error}}
      </div>
       <div class="alert alert-success" ng-show="mensaje!=''">
            @{{mensaje}}
       </div> 
      <form role="form">
         <div class="form-group">
            <label>Equipo</label>
            <select class="form-control" ng-model="equipo" ng-options="x.nombre for x in equipos" ng-change="listarJugadores(x.id)">
            </select>
         </div>
         <div class="form-group">
            <label>Jugadores</label>
            <select class="form-control" ng-model="jugador" ng-options="x.nombre for x in jugadores" >
            </select>
         </div>
          <button class="btn btn-default" ng-click="agregar()">Agregar</button>
      </form>
   </div>
            <div class="table-responsive">
               <table class="table table-bordered table-hover table-striped">
                  <thead>
                     <tr>
                        <th>Nombre</th>
                        <th>Operaciones</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr ng-repeat="x in jugadores">
                        <td>@{{x.nombre}}</td>
                        <td >
                           <button class="btn btn btn-danger" ng-click="eliminar(x.id)"><i class="fa fa-fw fa-delete"></i> Eliminar </button>
                        </td>
                     </tr>
                     <tr>
                        <td>
                            <input class="form-control" ng-model="nombre">
                        </td>
                        <td >
                           <button class="btn btn btn-default" ng-click="agregar()"> Agregar jugador </button>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>


@endsection
@section('javascript')
<script src="../js/angular/administrador/deportes.js"></script>
@endsection
