app.controller('ConfigurationController', function ($scope, $http, $route, $location, $ngConfirm, $uibModal, 
	                                                ConfigurationService, toastr) {
	
	$scope.data = {
		iva: 0
	}

	$scope.validation = function () {
		var data = $scope.data;
		var invalid = false;
		
		if (! data.iva || data.iva == 0) {
			invalid = toastr.warning('Iva requerido', 'Validaciones');
		} else if (data.iva <= 0 || data.iva > 30) {
			invalid = toastr.warning('Defina un valor de IVA entre 1 y 30', 'Validaciones');
		}

		return (invalid) ? false : data;
	}

	$scope.read = function () {
		ConfigurationService.read()
		  .success(function (response) {
			$scope.data = response;
		}).error(function (response) {
			toastr.error(response.msg || 'Error en el servidor');
		});
	}

	$scope.close = function () {
		// return to index
		location.href = '/#';
	}

	$scope.save = function () {
		var data = $scope.validation();

		if (!data) {
			return false;
		}

		ConfigurationService.save(data)
			.success(function(response) {
				toastr.success('Configuración guardada');
			})
			.error(function(response) {
				console.log(response);
				if (response.errors) {
					response.errors.forEach(function (item) {
						toastr.error(item);
					});
				} else {
					toastr.error(response.msg || 'Error en el servidor');
				}
			});
	}

	$scope.$on('$viewContentLoaded', function (view) {
		$scope.read(); // index view
	});

});