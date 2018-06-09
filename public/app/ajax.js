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
		},
		saveAttributes : function (data) {
			return $http.post('products/'+ data.id +'/attributes', data);
		},
		searchProduct : function (data) {
			return $http.post('products/search-product', data);
		}
	} 
}]);

app.factory('MovementConceptService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('movement_concepts/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('movement_concepts/'+ data.id, data) : $http.post('movement_concepts', data);
		},
		read     : function(data) {
			return $http.get('movement_concepts?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('movement_concepts/'+ data.id);
		},
		activate : function(data) {
			return $http.post('movement_concepts/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('movement_concepts/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('MovementService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('movements/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('movements/'+ data.id, data) : $http.post('movements', data);
		},
		read     : function(data) {
			return $http.get('movements?'+ jQuery.param(data), data);
		},
		cancel : function (data) {
			return $http.post('movements/'+ data.id +'/cancel');
		}
	} 
}]);

app.factory('ClientService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('clients/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('clients/'+ data.id, data) : $http.post('clients', data);
		},
		read     : function(data) {
			return $http.get('clients?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('clients/'+ data.id);
		},
		activate : function(data) {
			return $http.post('clients/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('clients/'+ data.id +'/deactivate');
		},
		saveVisit : function(data) {
			return $http.post('clients/'+ data.id +'/visit', data)
		}
	}
}]);

app.factory('VisitService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('visits/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('visits/'+ data.id, data) : $http.post('visits', data);
		},
		read     : function(data) {
			return $http.get('visits?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('visits/'+ data.id);
		}
	}
}]);

app.factory('SaleService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('sales/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('sales/'+ data.id, data) : $http.post('sales', data);
		},
		read     : function(data) {
			return $http.get('sales?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('sales/'+ data.id);
		}
	}
}]);