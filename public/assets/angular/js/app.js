'use strict';

var sentEmailsApp = angular.module('sentEmailsApp', ['ui.router', 'sentEmailsApp.controllers', 'sentEmailsApp.services', 'sentEmailsApp.filters', 'googlechart',]);

sentEmailsApp.config(['$stateProvider', function($stateProvider) {

	$stateProvider.state('home', {
		url: '/'
	});	

	$stateProvider.state('view', {
		url: '/view/:id',
		controller: 'EmailController',
		templateUrl: '../../assets/angular/partials/view-email.html'
	});

	$stateProvider.state('view.analytics', {
		url: '/analytics',
		controller: 'AnalyticsController',
		templateUrl: '../../assets/angular/partials/view-analytics.html'
	});

	$stateProvider.state("otherwise", { 
		redirectTo: '/'
	});	

}]);


var draftsApp = angular.module('draftsApp', ['ui.router', 'draftsApp.controllers', 'draftsApp.services', 'sentEmailsApp.filters']);

draftsApp.config(['$stateProvider', function($stateProvider) {

	$stateProvider.state('home', {
		url: '/'
	});	

	$stateProvider.state('view', {
		url: '/view/:id',
		controller: 'DraftController',
		templateUrl: '../../assets/angular/partials/view-draft.html'
	});

	$stateProvider.state("otherwise", { 
		redirectTo: '/'
	});	

}]);


var trashApp = angular.module('trashApp', ['ui.router', 'trashApp.controllers', 'trashApp.services', 'sentEmailsApp.filters']);

trashApp.config(['$stateProvider', function($stateProvider) {

	$stateProvider.state('home', {
		url: '/'
	});	

	$stateProvider.state('view', {
		url: '/view/:id',
		controller: 'ViewTrashController',
		templateUrl: '../../assets/angular/partials/view-email.html'
	});

	$stateProvider.state("otherwise", { 
		redirectTo: '/'
	});	

}]);
