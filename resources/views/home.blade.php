@extends('layouts.app')

@section('content')
<div id="app">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="#/">ExeLentes</a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <!--<li><a href="#/users">Usuarios</a></li>-->
                    <li><a href="#/clients">Clientes</a></li>
                    <li><a href="#/products">Productos</a></li>
                    <li><a href="#/movements">Movimientos</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Catálogos <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#/groups">Grupos</a></li>
                            <li><a href="#/attributes">Atributos</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#/movement-concepts">Conceptos de Movimientos</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#/users">Usuarios</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="#/configuration">Configuración</a>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid cls-main-container">
      <div data-ng-view></div>
    </div>
</div>
@endsection
