app.controller('ClientsController', function ($scope, $http, $route, $location, $ngConfirm, $uibModal,
	                                          ClientService, AttributeService, VisitService, toastr) {
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

	$scope.data = {};

	$scope.dataAttr = [];

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

	$scope.$on('$viewContentLoaded', function (view) {
		$scope.readAttributes();
	});

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