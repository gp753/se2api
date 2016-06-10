@extends('layouts.master')
@section('contenido')
<div class="header-placeholder"></div>
<div role="main" ng-controller="PrincipalCtrl" ng-init="inicializar()">
<div cg-busy="{promise:promise,templateUrl:templateUrl,message:message,backdrop:backdrop,delay:delay,minDuration:minDuration}">
   <!-- container --> 
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <section >
               <div class="page-header">
                  <h1>Iniciar sesión</h1>
               </div>
               <div class="row">
                  <div class="col-md-6 show-grid">
                     <div class="alert alert-danger" ng-show="error!=''" > @{{error}}</div>
                     <form role="form" name="iniciarSesionForm" novalidate>
                     <div class="form-group">
                        <label>Email:</label>
                        <input type="email" class="form-control" name="email" ng-model="iniciarSesion.email" required>
                     </div>
                     <div class="form-group">
                        <label>Contraseña:</label>
                        <input type="password" class="form-control" name="contrasena" ng-model="iniciarSesion.contrasena" required>
                     </div>
                     <button class="btn btn-default" ng-disabled="iniciarSesionForm.email.$invalid ||  iniciarSesionForm.contrasena.$invalid" 
                        ng-click="iniciarSesion()">Iniciar sesión</button>
                         <button class="btn btn-info" ng-click="register()">Crear cuenta</button>  
                     </form>
                  </div>
               </div>
            </section>
         </div>
      </div>
   </div>
   <!-- /container -->
</div>
@endsection
@section('javascript')
<script src="js/angular/login.js"></script>
@endsection