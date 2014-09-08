'use strict';

var EmailsAppServices = angular.module('sentEmailsApp.services', ['ngResource']);

EmailsAppServices.factory('Emails', ['$resource', function($resource){
	return $resource('../emails-resource/sent/:id', {id: '@id'}, {
		'query': { method:'GET', isArray:true },
		'update': { method: 'DELETE'}
	});
}]);

EmailsAppServices.factory('Custom', ['$http', '$q', function ($http, $q) {
	return {
		getShareLink: function(id) {
			var defer = $q.defer();

			$http.get('../emails-custom/share/' + id).success(function (data) {
				defer.resolve(data);
			}).error(function () {
				defer.reject('An error occurred');
			});

			return defer.promise;			
		},

		getPDF: function(id) {
			var defer = $q.defer();

			$http.get('../emails-custom/pdf/' + id).success(function (data) {
				defer.resolve(data);
			}).error(function () {
				defer.reject('An error occurred');
			});

			return defer.promise;			
		},

		//not used
		getData: function (route, param) {
			var defer = $q.defer();

			$http.get('http://localhost:8000/api/' + route + '/' + param).success(function (data) {
				defer.resolve(data);
			}).error(function () {
				defer.reject('An error occurred');
			});

			return defer.promise;
		}
	};
}]);


EmailsAppServices.factory('Miscellaneous', function() {
    return {

    	getIdFromIndex: function(collection, index) {
    		return collection[index].id;
    	},

    	getIndexFromId: function(collection, id) {
    		var ourIndex = null;

			angular.forEach(collection, function(value, index) {
				if ( value.id == id ) {
					ourIndex = index;
					return false;
				}
			});

			return ourIndex;    		
    	},

	 	modeString: function(array) {
		    if (array.length == 0)
		        return null;

		    var modeMap = {},
		        maxEl = array[0],
		        maxCount = 1;

		    for(var i = 0; i < array.length; i++)
		    {
		        var el = array[i];

		        if (modeMap[el] == null)
		            modeMap[el] = 1;
		        else
		            modeMap[el]++;

		        if (modeMap[el] > maxCount)
		        {
		            maxEl = el;
		            maxCount = modeMap[el];
		        }
		        else if (modeMap[el] == maxCount)
		        {
		            maxEl += '&' + el;
		            maxCount = modeMap[el];
		        }
		    }
		    return [maxEl, maxCount];
		},

		numberOfOccurences: function(array) {
			var result = { };

			for(var i = 0; i < array.length; ++i) {
			    if( ! result[array[i]] )
			        result[array[i]] = 0;

			    ++result[array[i]];
			}

			return result;
		}, 

		constructArrayForDataTable: function(array, maxRows) {

			var obj = { };

			for(var i = 0; i < array.length; ++i) {
			    if( !obj[array[i]] )
			        obj[array[i]] = 0;

			    ++obj[array[i]];
			}

			var sorted = [];
			for (var item in obj) {
			    sorted.push([item, obj[item]]);
			}

			sorted.sort(function(a, b) {
				return b[1] - a[1]
			});	

			var finalResult = [];
			var otherTotal = 0;

			if ( maxRows == 0 )
				finalResult = sorted;
			else {
				angular.forEach(sorted, function(value, index) {
					if ( index < maxRows ) {
						finalResult.push(value);
					}
					else {
						angular.forEach(value, function(index2, value2) {
							otherTotal += value2;
						});
					}
				});

				finalResult.push(['Other', otherTotal]);
			}			

			return finalResult;		    		
		}
       
    };
});


var DraftsAppServices = angular.module('draftsApp.services', ['ngResource']);

DraftsAppServices.factory('Drafts', ['$resource', function($resource){
	return $resource('../emails-resource/drafts/:id', {id: '@id'}, {
		'query': { method:'GET', isArray:true },
		'delete' : { method: 'DELETE'}
	});
}]);


DraftsAppServices.factory('Miscellaneous', function() {
    return {

    	getIdFromIndex: function(collection, index) {
    		return collection[index].id;
    	},

    	getIndexFromId: function(collection, id) {
    		var ourIndex = null;

			angular.forEach(collection, function(value, index) {
				if ( value.id == id ) {
					ourIndex = index;
					return false;
				}
			});

			return ourIndex;    		
    	}
       
    };
});


var TrashAppServices = angular.module('trashApp.services', ['ngResource']);

TrashAppServices.factory('Emails', ['$resource', function($resource){
	return $resource('../emails-resource/trash/:id', {id: '@id'}, {
		'query': { method:'GET', isArray:true },
		'update': { method: 'GET'}, //should be PUT but for bug
		'delete' : { method: 'DELETE'}
	});
}]);

TrashAppServices.factory('Miscellaneous', function() {
    return {

    	getIdFromIndex: function(collection, index) {
    		return collection[index].id;
    	},

    	getIndexFromId: function(collection, id) {
    		var ourIndex = null;

			angular.forEach(collection, function(value, index) {
				if ( value.id == id ) {
					ourIndex = index;
					return false;
				}
			});

			return ourIndex;    		
    	}
       
    };
});