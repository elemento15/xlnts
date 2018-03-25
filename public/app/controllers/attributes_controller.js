app.controller('AttributesController', function ($scope, $http, $route, $location, $ngConfirm, AttributeService, toastr) {
	this.index = '/attributes';
	this.title = {
		new:  'Nuevo Atributo',
		edit: 'Editar Atributo'
	}

	this.validation = function () {
		var data = $scope.data;
		var invalid = false;
		
		if (data.min > data.max) {
			invalid = toastr.warning('Valor minimo debe ser menor a máximo', 'Validaciones');
		}

		if (! data.max && data.max != 0){
			invalid = toastr.warning('Valor máximo requerido', 'Validaciones');
		}

		if (! data.min && data.min != 0){
			invalid = toastr.warning('Valor mínimo requerido', 'Validaciones');
		}

		if (! data.name) {
			invalid = toastr.warning('Nombre requerido', 'Validaciones');
		}

		return (invalid) ? false : data;
	}

	// model data
	$scope.data = {
		id:  0,
		name: '',
		min: 0,
		max: 0
	};

	$scope.filters = {
		active: '1'
	};

	BaseController.call(this, $scope, $route, $location, $ngConfirm, AttributeService, toastr);
});