app.controller('MovementConceptsController', function ($scope, $http, $route, $location, $ngConfirm, MovementConceptService, toastr) {
	this.index = '/movement-concepts';
	this.title = {
		new:  'Nuevo Concepto',
		edit: 'Editar Concepto'
	}

	this.validation = function () {
		var data = $scope.data;
		var invalid = false;
		
		if (! data.name) {
			invalid = toastr.warning('Nombre requerido', 'Validaciones');
		}

		if (! data.type){
			invalid = toastr.warning('Tipo requerido', 'Validaciones');
		}

		return (invalid) ? false : data;
	}

	// model data
	$scope.data = {
		id:  0,
		name: '',
		type: ''
	};

	$scope.filters = {
		active: '1',
		is_auto: '0'
	};

	BaseController.call(this, $scope, $route, $location, $ngConfirm, MovementConceptService, toastr);
});