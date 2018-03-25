app.controller('GroupsController', function ($scope, $http, $route, $location, $ngConfirm, GroupService, toastr) {
	this.index = '/groups';
	this.title = {
		new:  'Nuevo Grupo',
		edit: 'Editar Grupo'
	}

	this.validation = function () {
		var data = $scope.data;
		var invalid = false;
		
		if (! data.name) {
			invalid = toastr.warning('Nombre requerido', 'Validaciones');
		}

		if (data.name && data.name.length < 5){
			invalid = toastr.warning('Nombre demasiado corto', 'Validaciones');
		}

		return (invalid) ? false : data;
	}

	// model data
	$scope.data = {
		id:  0,
		name: '',
		comments: ''
	};

	$scope.filters = {
		active: '1'
	};

	BaseController.call(this, $scope, $route, $location, $ngConfirm, GroupService, toastr);
});