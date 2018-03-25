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
		
		.when('/windows',{
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
			})

		.when('/components',{
				controller: 'ComponentsController',
				templateUrl: '/partials/components/index.html'
			})
		.when('/components-new',{
				controller: 'ComponentsController',
				templateUrl: '/partials/components/edit.html'
			})
		.when('/components-edit/:id',{
				controller: 'ComponentsController',
				templateUrl: '/partials/components/edit.html'
			})

		.when('/sectors',{
				controller: 'SectorsController',
				templateUrl: '/partials/sectors/index.html'
			})
		.when('/sectors-new',{
				controller: 'SectorsController',
				templateUrl: '/partials/sectors/edit.html'
			})
		.when('/sectors-edit/:id',{
				controller: 'SectorsController',
				templateUrl: '/partials/sectors/edit.html'
			})

		.when('/activities',{
				controller: 'ActivitiesController',
				templateUrl: '/partials/activities/index.html'
			})
		.when('/activities-new',{
				controller: 'ActivitiesController',
				templateUrl: '/partials/activities/edit.html'
			})
		.when('/activities-edit/:id',{
				controller: 'ActivitiesController',
				templateUrl: '/partials/activities/edit.html'
			})

		.when('/members',{
				controller: 'MembersController',
				templateUrl: '/partials/members/index.html'
			})
		.when('/members-new',{
				controller: 'MembersController',
				templateUrl: '/partials/members/edit.html'
			})
		.when('/members-edit/:id',{
				controller: 'MembersController',
				templateUrl: '/partials/members/edit.html'
			})

		.when('/requirements',{
				controller: 'RequirementsController',
				templateUrl: '/partials/requirements/index.html'
			})
		.when('/requirements-new',{
				controller: 'RequirementsController',
				templateUrl: '/partials/requirements/edit.html'
			})
		.when('/requirements-edit/:id',{
				controller: 'RequirementsController',
				templateUrl: '/partials/requirements/edit.html'
			})

		.when('/projects',{
				controller: 'ProjectsController',
				templateUrl: '/partials/projects/index.html'
			})
		.when('/projects-new',{
				controller: 'ProjectsController',
				templateUrl: '/partials/projects/edit.html'
			})
		.when('/projects-edit/:id',{
				controller: 'ProjectsController',
				templateUrl: '/partials/projects/edit.html'
			})

		.otherwise({ redirectTo: '/' });

	// regular expression definitions
	app.regexpRFC = /^([A-Z,Ñ,&]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z|\d]{3})$/;
	app.regexpEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
});
