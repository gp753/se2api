@extends('layouts.administrador')
@section('contenido')
<div class="row" >
   <div class="col-lg-12">
      <h1 class="page-header">
         Problematicas
      </h1>
   </div>
</div>
<div class="row" ng-controller="AgregarCtrl" ng-init="error='';mensaje=''">
   <div class="col-lg-6">
      <div class="alert alert-danger" ng-show="error!=''">
            @{{error}}
      </div>
       <div class="alert alert-success" ng-show="mensaje!=''">
            @{{mensaje}}
       </div> 
      <form role="form">
         <div class="form-group">
            <label>Título</label>
            <input class="form-control" ng-model="titulo">
         </div>
         <div class="form-group">
            <label>Contenido</label>
            <textarea class="form-control" rows="3" ng-model="contenido"></textarea>
         </div>
          <button class="btn btn-default" ng-click="agregar()">Agregar</button>
      </form>
   </div>
</div>
<hr>

<div class="row" ng-controller="ModificarCtrl" ng-init="error='';mensaje='';modificando=false;">
    <div class="col-lg-12">
        <div class="alert alert-danger" ng-show="error!=''">
            @{{error}}
      </div>
        <div class="alert alert-success" ng-show="mensaje!=''">
            @{{mensaje}}
      </div>
    </div>
    
    <div class="col-lg-12" ng-show="modificando==true">
        <form role="form" >
         <div class="form-group">
            <label>Título</label>
            <input class="form-control" ng-model="titulo">
         </div>
         <div class="form-group">
            <label>Contenido</label>
            <textarea class="form-control" rows="3" ng-model="contenido"></textarea>
         </div>
          <button class="btn btn-default" ng-click="modificar()">Modificar</button>
          <button class="btn btn-danger" ng-click="modificando=false">Cerrar</button>
        </form>
    </div>

    <div class="col-lg-12" ng-hide="modificando==true">
        
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-newspaper-o fa-fw"></i> Noticias </h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Titulo</th>
                                                <th>Autor</th>
                                                <th>Fecha</th>
                                                <th>Operaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="x in problematicas">
                                                <td>@{{x.titulo}}</td>
                                                <td>@{{x.nombre}}</td>
                                                <td>@{{x.created_at}}</td>
                                                <td >
                                                    <button class="btn btn btn-primary" ng-click="iniciarModificar(x)"><i class="fa fa-fw fa-edit"></i> Modificar </button>
                                                    <button class="btn btn btn-danger" ng-click="eliminar(x.id)"><i class="fa fa-fw fa-delete"></i> Eliminar </button>
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
<script src="../js/angular/administrador/problematicas.js"></script>
@endsection