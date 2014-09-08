'use strict';

var sentEmailsFilters = angular.module('sentEmailsApp.filters', []);

sentEmailsFilters.filter('dateToISO', function() {
  	return function(input) {
  		if ( input ) {
		    var output = input.replace(/(.+) (.+)/, "$1T$2Z");
	    	return output;
	    }
  	};
});