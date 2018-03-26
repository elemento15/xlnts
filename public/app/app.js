var app = angular.module('mainApp', ['ngRoute', 'ui.bootstrap', 'ui.select', 'toastr', 'cp.ngConfirm']);

app.config(function ($routeProvider, $provide, toastrConfig) {
	angular.extend(toastrConfig, {
		autoDismiss: false,
		containerId: 'toast-container',
		maxOpened: 0,    
		newestOnTop: true,
		positionClass: 'toast-bottom-right',
		preventDuplicates: false,
		preventOpenDuplicates: false,
		target: 'body'
	});
	
	$routeProvider
		.when('/',{
				controller: 'HomeController',
				templateUrl: '/partials/home.html'
			})

		.when('/products',{
				controller: 'ProductsController',
				templateUrl: '/partials/products/index.html'
			})
		
		.when('/users',{
				controller: 'UsersController',
				templateUrl: '/partials/users/index.html'
			})
		.when('/users-new',{
				controller: 'UsersController',
				templateUrl: '/partials/users/edit.html'
			})
		.when('/users-edit/:id',{
				controller: 'UsersController',
				templateUrl: '/partials/users/edit.html'
			})

		.when('/groups',{
				controller: 'GroupsController',
				templateUrl: '/partials/groups/index.html'
			})
		.when('/groups-new',{
				controller: 'GroupsController',
				templateUrl: '/partials/groups/edit.html'
			})
		.when('/groups-edit/:id',{
				controller: 'GroupsController',
				templateUrl: '/partials/groups/edit.html'
			})

		.when('/attributes',{
				controller: 'AttributesController',
				templateUrl: '/partials/attributes/index.html'
			})
		.when('/attributes-new',{
				controller: 'AttributesController',
				templateUrl: '/partials/attributes/edit.html'
			})
		.when('/attributes-edit/:id',{
				controller: 'AttributesController',
				templateUrl: '/partials/attributes/edit.html'
			})

		/*.when('/windows',{
				controller: 'WindowsController',
				templateUrl: '/partials/windows/index.html'
			})
		.when('/windows-new',{
				controller: 'WindowsController',
				templateUrl: '/partials/windows/edit.html'
			})
		.when('/windows-edit/:id',{
				controller: 'WindowsController',
				templateUrl: '/partials/windows/edit.html'
			})*/

		.otherwise({ redirectTo: '/' });

	// regular expression definitions
	app.regexpRFC = /^([A-Z,Ñ,&]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z|\d]{3})$/;
	app.regexpEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
});

app.directive('stringToNumber', function() {
	return {
		require: 'ngModel',
		link: function (scope, element, attrs, ngModel) {
			ngModel.$parsers.push(function(value) {
				return '' + value;
			});
			ngModel.$formatters.push(function(value) {
				return  parseFloat(value, 10);
			});
		}
	}
});
