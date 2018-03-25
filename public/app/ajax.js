app.factory('UserService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('users/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('users/'+ data.id, data) : $http.post('users', data);
		},
		read     : function(data) {
			return $http.get('users?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('users/'+ data.id);
		},
		activate : function(data) {
			return $http.post('users/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('users/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('RoleService', ['$http', function($http) {
	return {
		read     : function(data) {
			return $http.get('roles?'+ jQuery.param(data), data);
		}
	} 
}]);