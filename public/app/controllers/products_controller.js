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

	$scope.isAdmin = (window.mainApp.roleCode == 'ADM');

	$scope.data = {};

	$scope.filters = {
		active: null
	};

	$scope.showView = false; // F=Form | A=Attributes | false

	$scope.groupsList = [];
	$scope.attributesList = [];
	$scope.attributesProducts = [];

	$scope.clearModel = function () {
		$scope.data = {
			id:  0,
			description: '',
			type: 'P',
			group: null,
			group_id: '',
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
		var attrProd = [];
		
		if ($scope.isAdmin) {
			$scope.data = $scope.getRecord(record.id);

			// pass all the attributes, setting those related to product that are checked
			$scope.attributesList.forEach(function (item) {
				attrProd.push({
					attribute_id: item.id, 
					name: item.name,
					min: item.min,
					max: item.max,
					steps: item.steps,
					description: item.description,
					checked: $scope.getCheckedAttr(item.id, $scope.data.attributes)
				});
			});

			$scope.attributesProducts = attrProd;
			$scope.showView = 'A';
		}
	}

	$scope.changeAttrSelection = function (attribute) {
		attribute.checked = !attribute.checked;
	}

	$scope.saveAttributes = function () {
		var data = {
			id: $scope.data.id,
			attributes: $scope.attributesProducts
		};

		ProductService.saveAttributes(data)
			.success(function(response) {
				toastr.success('Registro guardado');
				$scope.read();
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

	$scope.getCheckedAttr = function (attr_id, data) {
		var attr = data.filter(function (item) {
			return (attr_id == item.attribute_id);
		});

		return (attr.length) ? attr[0]['checked'] : false;
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
				items: function () {
					return {
						// search: search || ''
					};
				}
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
		if ($scope.isAdmin) {
			$scope.data = $scope.getRecord(id);
			$scope.showView = 'F'; // form
		}
	}
});