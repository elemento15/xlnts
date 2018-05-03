app.controller('MovementsController', function ($scope, $http, $route, $location, $ngConfirm, $timeout, 
	                                            MovementService, MovementConceptService, ProductService, toastr) {
	this.index = '/movements';
	this.title = {
		new:  'Nuevo Movimiento',
		edit: 'Ver Movimiento'
	}

	this.validation = function () {
		var data = $scope.data;
		var invalid = false;
		
		if (! data.movement_concept_id) {
			invalid = toastr.warning('Seleccione un concepto', 'Validaciones');
		}

		return (invalid) ? false : data;
	}

	var now = new Date();

	// model data
	$scope.data = {
		id:  0,
		mov_date: now.toISOString(),
		movement_concept_id: '',
		type: '',
		cancel_date: '',
		cancel_info: '',
		comments: '',
		products: []
	};

	$scope.filters = {
		active: '1'
	};

	// search product
	$scope.product = {
		id: 0,
		description: '',
		group: '',
		quantity: ''
	};

	// temporal editing quantity value
	$scope.editing = {
		quantity: 0
	}

	$scope.conceptsList = [];

	$scope.isNew = function () {
		return ($scope.data.id) ? false : true;
	}

	$scope.getConcepts = function () {
		var filters = [
			{ field: 'active', value: 1 },
			{ field: 'type', value: $scope.data.type },
			{ field: 'is_auto', value: 0 }
		];

		MovementConceptService.read({ filters: filters })
			.success(function (response) {
				$scope.conceptsList = response;
			}).error(function (response) {
				toastr.error(response.msg || 'Error en el servidor');
			});
	}

	$scope.changeType = function () {
		$scope.data.movement_concept_id = '';
		$scope.getConcepts();
	}

	$scope.cancel = function (id) {
		$ngConfirm({
			title: 'Cancelar',
			content: '¿Desea cancelar el movimiento seleccionado?',
			type: 'red',
			buttons: {
				ok: {
					text: 'Aceptar',
					btnClass: 'btn-red',
					action: function () {
						MovementService.cancel({
							id: id
						}).success(function (response) {
							toastr.warning('Registro cancelado');
							$scope.paginate('first', true);
						}).error(function (response) {
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

		ProductService.searchProduct(data)
			.success(function (response) {
				if (response.success) {
					// found one match, set product 
					if (response.total == 1) {
						$scope.product = {
							id: response.product.id,
							description: response.product.description,
							group: response.product.group.name,
							quantity: 1
						};

						// set focus to quantity
						$timeout(function () {
							$('[ng-model="product.quantity"]').select();
						}, 100);

					} else {
						// TODO: open search modal...
					}
				} else {
					// TODO: Catch error
				}
			}).error(function (response) {
				toastr.error(response.msg || 'Error en el servidor');
			});
	}

	$scope.addProduct = function () {
		var product = $scope.product;

		if (! product.id) {
			$scope.clearProduct();
			return false;
		}

		$scope.data.products.push(product);
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

	$scope.showDetailQuantity = function (product) {
		if (!$scope.isNew()) return false;

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
			}
			product._editing = false;
		}
	}

	$scope.blurQuantityDetail = function (product) {
		var editing = $scope.editing.quantity;

		if (! isNaN(editing) && editing > 0 && editing < 500000) {
			product.quantity = editing;
		}

		product._editing = false;
	}

	$scope.deleteProduct = function (key) {
		$scope.data.products.splice(key, 1);
	}


	$scope.$on('$viewContentLoaded', function (view) {
		$scope.getConcepts();
	});

	BaseController.call(this, $scope, $route, $location, $ngConfirm, MovementService, toastr);
});