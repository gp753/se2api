<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
  display: none !important;
}
        
    </style>    
    
    
</head>

<body ng-app="app" ng-controller="PrincipalCtrl" ng-cloak>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <a class="navbar-brand" href="index.html">CECYT  <i ng-show="cargando==true" class="fa fa-refresh fa-spin fa-x fa-fw"></i> </a>
            </div>
            <!-- Top Menu Items -->
            @if (Auth::Check()==true)
            <ul class="nav navbar-right top-nav">
                @if (Auth::User()->tipo_usuario="administrador")
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lock"></i> Administracion del sitio <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/administrador/noticias">Noticias</a>
                        </li>
                        <li>
                            <a href="/administrador/problematicas">Problematicas</a>
                        </li>
                        <li>
                            <a href="/administrador/votaciones">Votaciones</a>
                        </li>
                        <li>
                            <a href="/administrador/deportes">Deportes</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/logout"><i class="fa fa-fw fa-power-off"></i> Cerrar sesión</a>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> @if (Auth::Check()==true) {{Auth::User()->nombre}} @endif <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> mi</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/logout"><i class="fa fa-fw fa-power-off"></i> Cerrar sesión</a>
                        </li>
                    </ul>
                </li>
                
            </ul>
            @else
            <ul class="nav navbar-right top-nav">
                <li>
                    <a href="/login"><i class="fa fa-fw fa-power-off"></i> Iniciar sesión</a>
                </li>
            </ul>
            @endif
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="/"><i class="fa fa-fw fa-home"></i> Principal</a>
                    </li>
                    <li>
                        <a href="/noticias"><i class="fa fa-fw fa-newspaper-o"></i> Noticias</a>
                    </li>
                    @if (Auth::Check()==false)
                    <li>
                        <a href="/horarios"><i class="fa fa-book"></i> Académico</a>
                    </li>
                    @else
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#academico"><i class="fa fa-fw fa-arrows-v"></i> Académico <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="academico" class="collapse">
                            <li>
                                <a href="/horarios">Horarios de clases</a>
                            </li>
                            <li>
                                <a href="/horarioPersonalizados">Crear horario personalizado</a>
                            </li>
                            <li>
                                <a href="/horariosPersonalizadosConsultar"">Consultar horario personalizado</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li>
                        <a href="/transparencia"><i class="fa fa-fw fa-eye"></i> Transparencia </a>
                    </li>
                    <li>
                        <a href="/deportes"><i class="fa fa-fw fa-bicycle"></i> Deportes</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#cecytparticipa"><i class="fa fa-fw fa-comments"></i> CECyT Participa <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="cecytparticipa" class="collapse">
                            <li>
                                <a href="/problematicas">Problemáticas</a>
                            </li>
                            <li>
                                <a href="/discusiones">Discusiones</a>
                            </li>
                            <li>
                                <a href="/votaciones">Votaciones</a>
                            </li>
                        </ul>
                    </li>
                    <li class="active">
                        <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
                    </li>
                    <li>
                        <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                
                @yield('contenido')
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    
    <!-- AngularJs -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
    
    @yield('javascript')

</body>

</html>