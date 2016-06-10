@extends('layouts.principal')
@section('contenido')
<div class="row" >
   <div class="col-lg-12">
      <h1 class="page-header">
         Noticias
      </h1>
   </div>
</div>
<div class="row" ng-hide="leyendo==true">
   <div class="col-sm-12" ng-repeat="x in noticias">
      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title">@{{x.titulo}}</h3>
         </div>
         <div class="panel-body">
            <div class="row">
               <div class="col-sm-4">
                  <div class="text-left">
                     <image>
                     <img src="images/imagen.jpg" alt="imagen.jpg" >
                  </div>
               </div>
               <div class="col-sm-8">
                  @{{x.contenido.slice(0,300)}}...
               </div>
            </div>
            <hr>
            <div class="row">
               <div class="col-sm-6">
                  <div class="text-left">
                     <b>Publicado por </b> @{{x.nombre}}
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="text-right">
                     <a href="#" ng-click="cargarNoticia(x.id)">Leer noticia completa <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row" ng-show="leyendo==true">
   <div class="col-lg-12">
      <div class="jumbotron">
         <h1>@{{noticia.titulo}}</h1>
         <p>@{{noticia.contenido}}</p>
      </div>
   </div>
   <div class="col-lg-12">
      <h1>Comentarios</h1>
      `   
   </div>
   <div class="col-sm-12" ng-repeat="x in comentarios">
      <h4>@{{x.nombre}} dice :</h4>
      <p>@{{x.contenido}}</p>
      <p>@{{x.created_at}}</p>
      <hr>
   </div>
   <hr >
   <div class="col-lg-12">
      <div class="alert alert-danger" ng-show="error!=''">
         @{{error}}
      </div>
      <div class="alert alert-success" ng-show="mensaje!=''">
         @{{mensaje}}
      </div>
      <form role="form">
         <div class="form-group">
            <label>Comentar noticia</label>
            <textarea class="form-control" rows="3" ng-model="comentario"></textarea>
         </div>
         <button class="btn btn-default" ng-click="comentar(noticia.id,comentario)">Comentar</button>
         <button class="btn btn-info" ng-click="leyendo=false">Volver a noticias</button>
      </form>
   </div>
</div>
@endsection
@section('javascript')
<script src="../js/angular/noticias.js"></script>
@endsection