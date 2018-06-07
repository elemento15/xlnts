app.controller('ClientsController', function ($scope, $http, $route, $location, $ngConfirm, $uibModal, $timeout,
	                                          ClientService, AttributeService, VisitService, ProductService,
	                                          toastr) {
	this.index = '/clients';
	this.title = {};

	$scope.validation = function () {
		var data = $scope.data;
		var invalid = false;
		
		if (! data.name) {
			invalid = toastr.warning('Nombre requerido', 'Validaciones');
		}

		return (invalid) ? false : data;
	}

	$scope.screen = 'CLIENTS'; // CLIENTS | SALES

	$scope.data = {};

	$scope.dataAttr = [];

	$scope.sale = {
		comments: '',
		products: []
	};

	$scope.totals = {
		subtotal: 0,
		iva_amount: 0,
		total: 0
	}

	// search product
	$scope.product = {
		id: 0,
		description: '',
		group: '',
		quantity: '',
		price: 0,
		type: ''
	};

	$scope.filters = {
		active: null
	};

	$scope.selectedClient = null;

	$scope.attributesList = [];
	$scope.visitsList = [];


	$scope.clearModel = function () {
		$scope.data = {
			id:  0,
			name: '',
			rfc: '',
			email: '',
			phone: '',
			mobile: '',
			birthday: '',
			comments: ''
		};
	}

	$scope.openForm = function (product) {
		var title = (product) ? 'Editar' : 'Nuevo';
		$scope.clearModel();

		$scope.modalForm = $uibModal.open({
			ariaLabelledBy: 'modal-title',
			ariaDescribedBy: 'modal-body',
			templateUrl: '/partials/clients/modal_form.html',
			controller: function ($scope) {
				$scope.title = title;
			},
			controllerAs: '$ctrl',
			scope: $scope
		});
	}

	$scope.openVisit = function (visit) {
		var attribute;
		$scope.dataAttr = [];

		$scope.attributesList.forEach(function (item) {
			attribute = (visit) ? $scope.getVisitAttribute(item.id, visit.visit_attributes) : {};

			$scope.dataAttr.push({
				id: attribute.id || 0,
				attribute_id: item.id,
				name: item.name,
				min: item.min,
				max: item.max,
				value: parseFloat(attribute.value) || 0
			});
		});

		if ($scope.selectedClient) {
			$scope.modalVisit = $uibModal.open({
				ariaLabelledBy: 'modal-title',
				ariaDescribedBy: 'modal-body',
				templateUrl: '/partials/clients/modal_visit.html',
				controller: function ($scope) {
					$scope.client = $scope.selectedClient;
					$scope.visit = visit || null;
				},
				controllerAs: '$ctrl',
				scope: $scope
			});
		}
	}

	$scope.saveVisit = function (visit) {
		var value;
		var success = true;
		var client_id = $scope.selectedClient.id;
		
		// validate ranges of attributes
		$scope.dataAttr.forEach(function (item, index) {
			value = parseFloat(item.value);

			if (! value || value < parseFloat(item.min) || value > parseFloat(item.max)) {
				success = false;
				toastr.warning('El valor de '+ item.name +' debe ser entre '+ item.min +' y '+ item.max);
			}
		});

		if (success) {
			var data = {
				id: (visit) ? visit.id : 0,
				client_id: client_id,
				type: 'VIS',
				visit_attributes: $scope.dataAttr
			};

			VisitService.save(data)
				.success(function(response) {
					$scope.modalVisit.dismiss();
					toastr.success('Visita guardada');

					// update visits list
					$scope.readVisits(client_id);
				})
				.error(function(response) {
					toastr.error(response.msg || 'Error en el servidor');
				});
		}
	}

	$scope.readAttributes = function () {
		AttributeService.read({ filters: [{ field: 'active', value: 1 }] })
			.success(function (response) {
				$scope.attributesList = response;
			}).error(function (response) {
				toastr.error(response.msg || 'Error en el servidor');
			});
	}

	$scope.getVisitAttribute = function (id, attributes) {
		var obj = {};

		attributes.forEach(function (item) {
			if (item.attribute_id == id) {
				obj = {
					id: item.id,
					value: item.value
				};
			}
		});

		return obj;
	}

	$scope.setScreen = function (screen) {
		if ($scope.selectedClient) {
			$scope.screen = screen;
		}
	}

	$scope.selectClient = function (rec) {
		if (!$scope.selectedClient || $scope.selectedClient.id != rec.id) {
			$scope.selectedClient = rec;
			$scope.readVisits(rec.id);
		}
	}

	$scope.readVisits = function (client_id) {
		VisitService.read({
			page: 1,
			filters: [{ field: 'client_id', value: client_id}],
			search: ''
		}).success(function (response) {
			$scope.visitsList = response.data;
			/*$scope.list = response.data;
			$scope.setPagination(response, pagination);*/
		}).error(function (response) {
			toastr.error(response.msg || 'Error en el servidor');
		});
	}

	$scope.afterRead = function () {
		$scope.selectedClient = null;
		$scope.visitsList = [];
	}

	$scope.enterDescription = function (evt) {
		if (evt.keyCode == 13) {
			this.searchProduct();
		}
	}

	$scope.enterQuantity = function (evt) {
		if (evt.keyCode == 13) {
			$('.btnAdd').focus();
		}
	}

	$scope.searchProduct = function () {
		var data = { description: this.product.description };
		
		if (! data.description) {
			return false;
		}

		ProductService.searchProduct(data)
			.success(function (response) {
				if (response.success) {
					// found one match, set product 
					if (response.total == 1) {
						$scope.setProduct(response.product);
						$scope.focusQuantity();

					} else {
						$scope.openSearch(data.description);
					}
				} else {
					// TODO: Catch error
				}
			}).error(function (response) {
				toastr.error(response.msg || 'Error en el servidor');
			});
	}

	$scope.setProduct = function (product) {
		$scope.product = {
			id: product.id,
			description: product.description,
			group: product.group.name,
			quantity: 1,
			price: product.price,
			type: product.type
		};
	}

	$scope.focusQuantity = function () {
		$timeout(function () {
			$('[ng-model="product.quantity"]').select();
		}, 100);
	}

	$scope.openSearch = function (search) {
		var modal = $uibModal.open({
			ariaLabelledBy: 'modal-title',
			ariaDescribedBy: 'modal-body',
			templateUrl: '/partials/_components/modalProducts.html',
			controller: 'ModalProductsSearch',
			controllerAs: '$ctrl',
			resolve: {
				items: function () {
					return {
						search: search || '',
						type: false // P = Products, S = Services, null/false = All
					};
				}
			}
		});

		modal.result.then(function (product) {
			if (product) {
				$scope.setProduct(product);
				$scope.focusQuantity();
			}
		});
	}

	$scope.addProduct = function () {
		var product = $scope.product;

		if (! product.id) {
			$scope.clearProduct();
			return false;
		}
		
		// calculate detail total
		product.total = product.price * product.quantity;

		$scope.sale.products.push(product);
		$scope.calculateTotals();
		$scope.clearProduct();
	}

	$scope.clearProduct = function () {
		$scope.product = {
			id: 0,
			description: '',
			group: '',
			quantity: ''
		};

		$('[ng-model="product.description"]').select();
	}

	$scope.deleteProduct = function (key) {
		$scope.sale.products.splice(key, 1);
		$scope.calculateTotals();
	}

	$scope.calculateTotals = function () {
		var subtotal = 0;

		$scope.sale.products.forEach(function(item) {
			subtotal += item.total;
		});

		$scope.totals = {
			subtotal: subtotal,
			iva_amount: 0,
			total: subtotal
		};
	}
	

	$scope.$on('$viewContentLoaded', function (view) {
		$scope.readAttributes();
	});



	BaseController.call(this, $scope, $route, $location, $ngConfirm, ClientService, toastr);
	
	// ==================
	// Override functions
	// ==================

	$scope.new = function () {
		$scope.openForm(false);
	}

	$scope.save = function (modal = true) {
		var data = this.validation();

		if (data) {
			ClientService.save(data)
				.success(function(response) {
					toastr.success('Cliente guardado');
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
		ClientService.get({
			id : id
		}).success(function(response) {
			$scope.openForm(response);
			$scope.data = response;
		}).error(function(response) {
			toastr.error(response.msg || 'Error en el servidor');
		});
	}
});