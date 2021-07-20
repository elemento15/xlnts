<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ng-app="mainApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/angular-toastr.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/angular-confirm.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/ui-bootstrap-2.5.0-csp.css" />
    <link rel="stylesheet" type="text/css" href="/css/select.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/styles.css" />

    <!-- Scripts -->
    <script src="/libs/jquery.js"></script>
    <script src="/libs/bootstrap.min.js"></script>
    <script src="/libs/angular.min.js"></script>
    <script src="/libs/angular-route.min.js"></script>
    <!-- https://github.com/Foxandxss/angular-toastr -->
    <script src="/libs/angular-toastr.tpls.min.js"></script>
    <!-- https://craftpip.github.io/angular-confirm/ -->
    <script src="/libs/angular-confirm.min.js"></script>
    <!-- https://github.com/angular-ui/ui-select/wiki -->
    <script src="/libs/select.min.js"></script>
    <script src="/libs/ui-bootstrap-tpls-2.5.0.min.js"></script>
</head>
<body>
    @yield('content')
    
    <br>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}"></script> -->

    <!-- general scripts -->
    <script src="/app/app.js"></script>
    <script src="/app/ajax.js"></script>

    <!-- components -->
    <script src="/app/components/modal_products_search.js"></script>

    <!-- controllers -->
    <script src="/app/controllers/base_controller.js"></script>
    <script src="/app/controllers/home_controller.js"></script>
    <script src="/app/controllers/configuration_controller.js"></script>
    <script src="/app/controllers/users_controller.js"></script>
    <script src="/app/controllers/groups_controller.js"></script>
    <script src="/app/controllers/attributes_controller.js"></script>
    <script src="/app/controllers/products_controller.js"></script>
    <script src="/app/controllers/movement_concepts_controller.js"></script>
    <script src="/app/controllers/movements_controller.js"></script>
    <script src="/app/controllers/sales_controller.js"></script>
    <script src="/app/controllers/clients_controller.js"></script>
    <script src="/app/controllers/reports_controller.js"></script>

    <script type="text/javascript">
        window.mainApp = {
            roleCode: '{{ Session::get("userRoleCode") }}',
            roleName: '{{ Session::get("userRoleName") }}'
        };
    </script>
</body>
</html>
