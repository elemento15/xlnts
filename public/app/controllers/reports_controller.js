app.controller('ReportsController', function ($scope, $http, $route, $location, $ngConfirm, $uibModal, toastr) {

	$scope.rpt01 = {
		year: 2018
	};

	$scope.rpt02 = {
		show_general: 1
	};

	// yearly sales
	$scope.runRpt01 = function () {
		var year = $scope.rpt01.year;
		window.open('reports/monthly_sales/'+ year);
	}

	// best clients
	$scope.runRpt02 = function () {
		var show_general = $scope.rpt02.show_general;
		window.open('reports/clients_sales?show_general=' + show_general);
	}

	$scope.$on('$viewContentLoaded', function (view) {
		var today = new Date();
		$scope.rpt01.year = today.getFullYear();
	});

});