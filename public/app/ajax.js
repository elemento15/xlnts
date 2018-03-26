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

app.factory('GroupService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('groups/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('groups/'+ data.id, data) : $http.post('groups', data);
		},
		read     : function(data) {
			return $http.get('groups?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('groups/'+ data.id);
		},
		activate : function(data) {
			return $http.post('groups/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('groups/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('AttributeService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('attributes/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('attributes/'+ data.id, data) : $http.post('attributes', data);
		},
		read     : function(data) {
			return $http.get('attributes?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('attributes/'+ data.id);
		},
		activate : function(data) {
			return $http.post('attributes/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('attributes/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('ProductService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('products/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('products/'+ data.id, data) : $http.post('products', data);
		},
		read     : function(data) {
			return $http.get('products?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('products/'+ data.id);
		},
		activate : function(data) {
			return $http.post('products/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('products/'+ data.id +'/deactivate');
		}
	} 
}]);