app.controller('UsersController', function ($scope, $http, $route, $location, $ngConfirm, UserService, RoleService, toastr) {
	this.index = '/users';
	this.title = {
		new:  'Nuevo Usuario',
		edit: 'Editar Usuario'
	}

	this.validation = function () {
		var data = $scope.data;
		var invalid = false;

        if (!data.role_id) {
			invalid = toastr.warning('Seleccione un rol', 'Validaciones');
        }

        if (! data.email) {
			invalid = toastr.warning('Email requerido', 'Validaciones');
		}

		if (data.email && ! app.regexpEmail.test(data.email)) {
			invalid = toastr.warning('Formato de email inválido', 'Validaciones');
        }
		
		if (! data.name) {
			invalid = toastr.warning('Nombre requerido', 'Validaciones');
		}

		if (data.name && data.name.length < 10){
			invalid = toastr.warning('Nombre demasiado corto', 'Validaciones');
		}

		return (invalid) ? false : data;
	}

	// model data
	$scope.data = {
		id:  0,
		name: '',
		email: '',
		// role_id: '',
		role: '',
		password: ''
	};

	$scope.filters = {
		active: '1'
	};

	$scope.rolesList = [];

	$scope.getRoles = function () {
		RoleService.read({ filters: {} })
			.success(function (response) {
				$scope.rolesList = response;
			}).error(function (response) {
				toastr.error(response.msg || 'Error en el servidor');
			});
	}

	$scope.getRoles();

	BaseController.call(this, $scope, $route, $location, $ngConfirm, UserService, toastr);
});