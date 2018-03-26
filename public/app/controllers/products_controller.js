app.controller('ProductsController', function ($scope, $http, $route, $location, $ngConfirm, $uibModal,
	                                           ProductService, GroupService, AttributeService, toastr) {
	this.index = '/products';
	this.title = {};

	$scope.validation = function () {
		var data = $scope.data;
		var invalid = false;
		
		if (! data.price || parseFloat(data.price)  < 0) {
			invalid = toastr.warning('Precio requerido', 'Validaciones');
		}

		if (! data.group_id) {
			invalid = toastr.warning('Grupo requerido', 'Validaciones');
		}

		if (! data.description) {
			invalid = toastr.warning('Nombre requerido', 'Validaciones');
		}

		if (data.description && data.description.length < 5){
			invalid = toastr.warning('Nombre demasiado corto', 'Validaciones');
		}

		return (invalid) ? false : data;
	}

	$scope.data = {};

	$scope.filters = {
		active: null
	};

	$scope.showView = false; // F=Form | A=Attributes | false

	$scope.groupsList = [];
	$scope.attributesList = [];

	$scope.clearModel = function () {
		$scope.data = {
			id:  0,
			description: '',
			type: 'P',
			group: null,
			price: 0,
			has_attributes: false,
			comments: ''
		};
	}

	$scope.getGroups = function () {
		var filters = [{ field: 'active', value: 1 }];

		GroupService.read({ filters: filters })
			.success(function (response) {
				$scope.groupsList = response;
			}).error(function (response) {
				toastr.error(response.msg || 'Error en el servidor');
			});
	}

	$scope.getAttributes = function () {
		var filters = [{ field: 'active', value: 1 }];

		AttributeService.read({ filters: filters })
			.success(function (response) {
				$scope.attributesList = response;
			}).error(function (response) {
				toastr.error(response.msg || 'Error en el servidor');
			});
	}

	$scope.afterRead = function () {
		$scope.showView = false;
	}

	$scope.getRecord = function (id) {
		var filter = $scope.list.filter(function (item) {
			return (item.id == id);
		});
		
		return angular.copy(filter[0]);
	}

	$scope.closeView = function () {
		$scope.showView = false;
	}

	$scope.showAttributes = function (record) {
		$scope.data = $scope.getRecord(record.id);
		$scope.showView = 'A';
	}
	
	$scope.$on('$viewContentLoaded', function (view) {
		$scope.getGroups();
		$scope.getAttributes();
	});

	BaseController.call(this, $scope, $route, $location, $ngConfirm, ProductService, toastr);
	
	// ==================
	// Override functions
	// ==================

	$scope.new = function () {
		$scope.clearModel();

		$scope.modalForm = $uibModal.open({
			ariaLabelledBy: 'modal-title',
			ariaDescribedBy: 'modal-body',
			templateUrl: '/partials/products/modal_form.html',
			controller: function ($scope) {
				$scope.title = 'Nuevo Producto/Servicio';
			},
			controllerAs: '$ctrl',
			scope: $scope,
			resolve: {
				/*items: function () {
					return {
						search: search || ''
					};
				}*/
			}
		});
	}

	$scope.save = function (modal = true) {
		var data = this.validation();

		if (data) {
			ProductService.save(data)
				.success(function(response) {
					toastr.success('Producto/Servicio guardado');
					$scope.read();
					if (modal) {
						$scope.modalForm.dismiss();
					}
				})
				.error(function(response) {
					if (response.errors) {
						response.errors.forEach(function (item) {
							toastr.error(item);
						});
					} else {
						toastr.error(response.msg || 'Error en el servidor');
					}
				});
		}
	}

	$scope.view = function (id) {
		$scope.data = $scope.getRecord(id);
		$scope.showView = 'F'; // form
	}
});