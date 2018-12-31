app.controller('SalesController', function ($scope, $http, $route, $location, $ngConfirm, SaleService, toastr) {
	this.index = '/sales';
	this.title = {
		new:  'Nueva Venta',
		edit: 'Ver Venta'
	}

	this.validation = function () {
		var data = $scope.data;
		var invalid = false;
		
		/*if (! data.name) {
			invalid = toastr.warning('Nombre requerido', 'Validaciones');
		}

		if (data.name && data.name.length < 5){
			invalid = toastr.warning('Nombre demasiado corto', 'Validaciones');
		}*/

		return (invalid) ? false : data;
	}

	// model data
	$scope.data = {
		id:  0,
		sale_date: '',
		total: 0,
		comments: ''
	};

	$scope.filters = {
		//active: '1'
	};

	BaseController.call(this, $scope, $route, $location, $ngConfirm, SaleService, toastr);
});