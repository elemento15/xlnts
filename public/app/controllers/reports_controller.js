app.controller('ReportsController', function ($scope, $http, $route, $location, $ngConfirm, $uibModal, toastr) {

	var today = new Date();
	var firstDay = new Date();
	firstDay.setDate(1);

	$scope.dateOptions = {
		showButtonBar: false
	};

	$scope.rpt01 = {
		year: today.getFullYear()
	};

	$scope.rpt02 = {
		type_date: 'A',
		show_general: 1,
		date_ini: firstDay,
		date_end: today,
		date_ini_opened: false,
		date_end_opened: false
	};

	$scope.rpt03 = {
		type_rpt: '1',
		date_ini: firstDay,
		date_end: today,
		date_ini_opened: false,
		date_end_opened: false
	};

	// yearly sales
	$scope.runRpt01 = function () {
		var year = $scope.rpt01.year;
		window.open('reports/monthly_sales/'+ year);
	}

	// best clients
	$scope.runRpt02 = function () {
		var rpt = $scope.rpt02;
		
		var opts = '?show_general='+ rpt.show_general;
		opts += '&type_date='+ rpt.type_date;

		if (rpt.type_date == 'R') {
			opts += '&date_ini='+ rpt.date_ini.toISOString().substr(0,10);
			opts += '&date_end='+ rpt.date_end.toISOString().substr(0,10);
		}

		window.open('reports/clients_sales'+ opts);
	}

	// best products
	$scope.runRpt03 = function () {
		var rpt = $scope.rpt03;

		var opts = '?type='+ rpt.type_rpt;
		opts += '&date_ini='+ rpt.date_ini.toISOString().substr(0,10);
		opts += '&date_end='+ rpt.date_end.toISOString().substr(0,10);

		window.open('reports/products_sales'+ opts);
	}

	$scope.openDatePicker = function (opt) {
		switch(opt) {
			case 'rpt02-ini' : 
				$scope.rpt02.date_ini_opened = true;
				break;
			case 'rpt02-end' : 
				$scope.rpt02.date_end_opened = true;
				break;
			case 'rpt03-ini' : 
				$scope.rpt03.date_ini_opened = true;
				break;
			case 'rpt03-end' : 
				$scope.rpt03.date_end_opened = true;
				break;
		}
	}

	$scope.$on('$viewContentLoaded', function (view) {
		// ---
	});

});