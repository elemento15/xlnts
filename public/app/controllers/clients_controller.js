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

	$scope.newVisit = function (product) {
		$scope.dataAttr = [];

		if ($scope.selectedClient) {
			$scope.modalVisit = $uibModal.open({
				ariaLabelledBy: 'modal-title',
				ariaDescribedBy: 'modal-body',
				templateUrl: '/partials/clients/modal_visit.html',
				controller: function ($scope) {
					$scope.client = $scope.selectedClient;
					$scope.attributes = $scope.attributesList;
					$scope.date = new Date();
				},
				controllerAs: '$ctrl',
				scope: $scope
			});
		}
	}

	$scope.saveVisit = function () {
		var success = true;
		var attrVal;
		var attrSave = [];
		var client_id = $scope.selectedClient.id;
		
		// validate ranges of attributes
		$scope.attributesList.forEach(function (item, index) {
			attrVal = $scope.dataAttr[index];

			if (! attrVal || attrVal < item.min || attrVal > item.max) {
				success = false;
				toastr.warning('El valor de '+ item.name +' debe ser entre '+ item.min +' y '+ item.max);
			}
			
			// create the attribute values to save
			attrSave.push({
				id: item.id,
				name: item.name,
				value: attrVal
			});
		});

		if (success) {
			var data = {
				client_id: client_id,
				type: 'VIS',
				visitAttributes: attrSave
			};

			VisitService.save(data)
				.success(function(response) {
					$scope.modalVisit.dismiss();
					toastr.success('Visita guardada');

					// update visits list
					$scope.readVisits(client_id);
				})
				.error(function(response) {
					console.log(response);
					// TODO: Catch errors
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

	$scope.$on('$viewContentLoaded', function (view) {
		$scope.readAttributes();
	});

	$scope.selectClient = function (rec) {
		$scope.selectedClient = rec;
		$scope.readVisits(rec.id);
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