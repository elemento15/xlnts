app.controller('ClientsController', function ($scope, $http, $route, $location, $ngConfirm, $uibModal, $timeout,
	                                          ClientService, AttributeService, VisitService, ProductService,
	                                          SaleService, ConfigurationService, toastr) {
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
		id: 0,
		client_id: 0,
		has_invoice: 0,
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
		total: 0,
		type: '',
		attributes: null
	};

	$scope.filters = {
		active: null,
		is_general: 0
	};

	$scope.selectedClient = null;

	$scope.attributesList = [];
	$scope.visitsList = [];

	// property when editing the product in list
	$scope.editing = {
		quantity: null
	};

	$scope.configs = {
		iva: 0
	};


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
		var attributes;
		$scope.dataAttr = [];

		$scope.attributesList.forEach(function (item) {
			attributes = (visit) ? $scope.getVisitAttributes(item.id, visit.visit_attributes) : {};

			$scope.dataAttr.push({
				id: attributes.id || 0,
				attribute_id: item.id,
				name: item.name,
				min: item.min,
				max: item.max,
				steps: item.steps,
				description: item.description,
				left_value: parseFloat(attributes.left_value) || 0,
				right_value: parseFloat(attributes.right_value) || 0
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
		var left_value, right_value;
		var success = true;
		var client_id = $scope.selectedClient.id;
		
		// validate ranges of attributes
		$scope.dataAttr.forEach(function (item, index) {
			left_value = parseFloat(item.left_value);
			right_value = parseFloat(item.right_value);

			// validations for left eye
			if (left_value < parseFloat(item.min) || left_value > parseFloat(item.max)) {
				success = false;
				toastr.warning(item.name +' de OJO IZQ. debe ser entre '+ item.min +' y '+ item.max);
			} else if (left_value % parseFloat(item.steps) != 0) {
				success = false;
				toastr.warning(item.name +' de OJO IZQ. debe ser multiplo de '+ item.steps);
			}

			// validations for right_eye
			if (right_value < parseFloat(item.min) || right_value > parseFloat(item.max)) {
				success = false;
				toastr.warning(item.name +' de OJO DER. debe ser entre '+ item.min +' y '+ item.max);
			} else if (right_value % parseFloat(item.steps) != 0) {
				success = false;
				toastr.warning(item.name +' de OJO DER. debe ser multiplo de '+ item.steps);
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

	$scope.getVisitAttributes = function (id, attributes) {
		var obj = {};

		attributes.forEach(function (item) {
			if (item.attribute_id == id) {
				obj = {
					id: item.id,
					left_value: item.left_value,
					right_value: item.right_value
				};
			}
		});

		return obj;
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

	$scope.clearSale = function () {
		$scope.sale = {
			id: 0,
			client_id: 0,
			has_invoice: 0,
			comments: '',
			products: []
		};
	}

	$scope.showSale = function () {
		if ($scope.selectedClient) {
			$scope.sale.client_id = $scope.selectedClient.id;
			$scope.screen = 'SALES';
		}
	}

	$scope.closeSale = function() {
		$scope.clearSale();
		$scope.screen = 'CLIENTS';
	}

	$scope.setInvoice = function (opt) {
		$scope.sale.has_invoice = opt;
		$scope.calculateTotals();
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
			type: product.type,
			attributes: product.attributes
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

		// initialize left and right values for attributes
		product.attributes.forEach(function(item) {
			item.left_value = 0;
			item.right_value = 0;
		});

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

	$scope.showDetailQuantity = function (product) {
		// if (!$scope.isNew()) return false;

		$scope.editing.quantity = product.quantity;
		product._editing = true;

		// set focus
		$timeout(function () {
			$("#productsList input[ng-model='editing.quantity']").select();
		}, 100);
	}

	$scope.keyUpDetailQuantity = function (evt, product) {
		var code = evt.keyCode;
		var editing = $scope.editing.quantity;

		if (code == 27) { // escape
			$scope.editing.quantity = 0;
			product._editing = false;
		}

		if (code == 13) { // enter
			if (! isNaN(editing) && editing > 0 && editing < 500000) {
				product.quantity = editing;
				product.total = product.quantity * product.price;
			}
			product._editing = false;
		}

		$scope.calculateTotals();
	}

	$scope.blurQuantityDetail = function (product) {
		var editing = $scope.editing.quantity;

		if (! isNaN(editing) && editing > 0 && editing < 500000) {
			product.quantity = editing;
			product.total = product.quantity * product.price;
		}

		product._editing = false;
		$scope.calculateTotals();
	}

	$scope.calculateTotals = function () {
		var sale = $scope.sale;
		var subtotal = 0;
		var iva_amount = 0;

		sale.products.forEach(function(item) {
			subtotal += item.total;
		});

		// set iva_amount if sale has_invoice
		iva_amount = (sale.has_invoice) ? subtotal * ($scope.configs.iva / 100) : 0;

		$scope.totals = {
			subtotal: subtotal,
			iva_amount: iva_amount,
			total: subtotal + iva_amount
		};
	}

	$scope.showProductAttrLabel = function (attributes) {
		var label = 'NO';
		var validation = $scope.validateProductAttributes(attributes);

		if (validation.checked > 0) {
			label = (validation.invalid > 0) ? 'PEND' : 'OK';
		}

		return label;
	}

	$scope.validateProductAttributes = function (attributes) {
		var checked = 0;
		var invalid = 0;

		attributes.forEach(function (item) {
			if (item.checked) {
				var attr = item.attribute;
				checked++;
				item.left_invalid = false;
				item.right_invalid = false;

				if (item.left_value < attr.min || item.left_value > attr.max || item.left_value % attr.steps != 0) {
					invalid++;
					item.left_invalid = true;
				}

				if (item.right_value < attr.min || item.right_value > attr.max || item.right_value % attr.steps != 0) {
					invalid++;
					item.right_invalid = true;
				}
			}
		});

		return { checked: checked, invalid: invalid };
	}

	$scope.showAttributesForm = function (product) {
		$scope.modalVisit = $uibModal.open({
			ariaLabelledBy: 'modal-title',
			ariaDescribedBy: 'modal-body',
			templateUrl: '/partials/clients/modal_attributes.html',
			controller: function ($scope) {
				$scope.product = product;
			},
			controllerAs: '$ctrl',
			scope: $scope
		});
	}

	$scope.saveSale = function () {
		var invalid = 0;
		
		$scope.sale.products.forEach(function (product) {
			var validate = $scope.validateProductAttributes(product.attributes);
			invalid += validate.invalid;
		});

		if (! invalid) {
			SaleService.save($scope.sale)
				.success(function(response) {
					toastr.success('Venta Guardada');
					$scope.closeSale();
					$scope.readVisits($scope.selectedClient.id);
				})
				.error(function(response) {
					toastr.error(response.msg || 'Error en el servidor');
				});
		} else {
			toastr.warning('Hay atributos invalidos en los productos');
		}
	}

	$scope.getLastVisitAttr = function (product) {
		var client_id = $scope.selectedClient.id;

		$ngConfirm({
			title: 'Leer atributos',
			content: '¿Desea obtener los atributos de la última visita?',
			type: 'blue',
			buttons: {
				ok: {
					text: 'Aceptar',
					btnClass: 'btn-primary',
					action: function () {
						var data = {
							id: client_id
						};

						ClientService.lastVisit(data)
							.success(function(response) {
								var visitAttr = response.visit_attributes || null;

								if (visitAttr) {
									// set the last visit attributes into theproduct attributes
									product.attributes.forEach(function (item) {
										visitAttr.forEach(function (attr) {
											if (item.attribute_id == attr.attribute_id) {
												item.left_value = parseFloat(attr.left_value);
												item.right_value = parseFloat(attr.right_value);
											}
										});
									});
								}
							})
							.error(function(response) {
								toastr.error(response.msg || 'Error en el servidor');
							});
					}
				},
				close: {
					text: 'Omitir',
					btnClass: 'btn-default'
				}
			}
		});
	}

	$scope.readConfigurations = function () {
		ConfigurationService.read()
			.success(function (response) {
				$scope.configs = response;
			}).error(function (response) {
				toastr.error(response.msg || 'Error en el servidor');
			});
	}
	

	$scope.$on('$viewContentLoaded', function (view) {
		$scope.readAttributes();
		$scope.readConfigurations();
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