app.factory('WindowService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('windows/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('windows/'+ data.id, data) : $http.post('windows', data);
		},
		read     : function(data) {
			return $http.get('windows?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('windows/'+ data.id);
		},
		activate : function(data) {
			return $http.post('windows/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('windows/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('ComponentService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('components/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('components/'+ data.id, data) : $http.post('components', data);
		},
		read     : function(data) {
			return $http.get('components?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('components/'+ data.id);
		},
		activate : function(data) {
			return $http.post('components/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('components/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('SectorService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('sectors/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('sectors/'+ data.id, data) : $http.post('sectors', data);
		},
		read     : function(data) {
			return $http.get('sectors?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('sectors/'+ data.id);
		},
		activate : function(data) {
			return $http.post('sectors/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('sectors/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('ActivityService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('activities/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('activities/'+ data.id, data) : $http.post('activities', data);
		},
		read     : function(data) {
			return $http.get('activities?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('activities/'+ data.id);
		},
		activate : function(data) {
			return $http.post('activities/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('activities/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('RequirementService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('requirements/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('requirements/'+ data.id, data) : $http.post('requirements', data);
		},
		read     : function(data) {
			return $http.get('requirements?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('requirements/'+ data.id);
		},
		activate : function(data) {
			return $http.post('requirements/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('requirements/'+ data.id +'/deactivate');
		}
	} 
}]);

app.factory('MemberService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('members/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('members/'+ data.id, data) : $http.post('members', data);
		},
		read     : function(data) {
			return $http.get('members?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('members/'+ data.id);
		},
		activate : function(data) {
			return $http.post('members/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('members/'+ data.id +'/deactivate');
		},
		search_name : function (name) {
			return $http.post('members/search_name', name);
		}
	} 
}]);

app.factory('ProjectService', ['$http', function($http) {
	return {
		get      : function (data) {
			return $http.get('projects/'+ data.id);
		},
		save     : function (data) {
			return (data.id) ? $http.patch('projects/'+ data.id, data) : $http.post('projects', data);
		},
		read     : function(data) {
			return $http.get('projects?'+ jQuery.param(data), data);
		},
		delete   : function(data) {
			return $http.delete('projects/'+ data.id);
		},
		activate : function(data) {
			return $http.post('projects/'+ data.id +'/activate');
		},
		deactivate : function(data) {
			return $http.post('projects/'+ data.id +'/deactivate');
		},
		doc_received : function (data) {
			return $http.post('projects/'+ data.id +'/doc_received', data);
		}
	} 
}]);