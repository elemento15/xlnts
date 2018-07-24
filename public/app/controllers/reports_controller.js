app.controller('ReportsController', function ($scope, $http, $route, $location, $ngConfirm, $uibModal, toastr) {

	$scope.rpt01 = {
		year: 2018
	};

	$scope.runRpt01 = function () {
		var year = $scope.rpt01.year;
		window.open('reports/monthly_sales/'+ year);
	}

	$scope.$on('$viewContentLoaded', function (view) {
		var today = new Date();
		$scope.rpt01.year = today.getFullYear();
	});

});