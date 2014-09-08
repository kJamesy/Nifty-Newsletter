'use strict';

var sentEmailsControllers = angular.module('sentEmailsApp.controllers', ['ngSanitize', 'ngAnimate']);

sentEmailsControllers.controller('EmailsController', ['$rootScope', '$scope', '$location', '$state', '$stateParams','$window', 'Emails', 'Miscellaneous', 'Custom', function($rootScope, $scope, $location, $state, $stateParams, $window, Emails, Miscellaneous, Custom) {
	
	$scope.emails = Emails.query();
	$scope.emails.$promise.then(function (emails) {
		$scope.emails = emails;
		//Scrollbar 
		angular.element('.scroll-y').mCustomScrollbar({
			theme:"inset-2-dark"
		});

		$scope.active = {};

		var splitPath = $location.path().split('/');
		var id = parseInt( splitPath[splitPath.length - 1] );
		var an_id = parseInt( splitPath[splitPath.length - 2] );

		if ( id > 0 && splitPath[splitPath.length - 2] == 'view') {
			$scope.active[Miscellaneous.getIndexFromId(emails, id)] = 'success';

			$scope.menuidx = Miscellaneous.getIndexFromId(emails, id);
		}

		else if ( an_id > 0  && splitPath[splitPath.length - 1] == 'analytics' ) {
			$scope.active[Miscellaneous.getIndexFromId(emails, an_id)] = 'success';

			$scope.menuidx = Miscellaneous.getIndexFromId(emails, an_id);			
		}

		if ( $location.path() == '' || $location.path() == '/' ) {
			if ( emails.length ) {
				$location.path('/view/' + Miscellaneous.getIdFromIndex(emails, 0));
				$scope.active[0] = 'success';
				$scope.menuidx = 0;
			}

			else {
				// $window.alert('Nothing here');
			}
		}
	});

	$scope.showEmail = function(idx) {
		$scope.active = {};
		$scope.active[idx] = 'success';
		$scope.menuidx = idx;

		$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.emails, idx));
	};

	$scope.forward = function(idx, url) {
		if ( $scope.emails.length ) {
			var id = Miscellaneous.getIdFromIndex($scope.emails, idx);
			$window.location.href = url + '/' + id + '/forward';
		}
		else {
			$window.alert('Nothing to forward');
		}
	};

	$scope.share = function(idx) {
		if ( $scope.emails.length ) {
			var id = Miscellaneous.getIdFromIndex($scope.emails, idx);
			Custom.getShareLink(id).then(function(response) {
				$scope.link = response.link;
				angular.element('#shareEmailModal').modal('show');
			});
		}
		else {
			$window.alert('Nothing here');
		}
	};

	$scope.pdf = function(idx, url) {
		if ( $scope.emails.length ) {
			var id = Miscellaneous.getIdFromIndex($scope.emails, idx);
			$window.open(url + '/' + id, '_blank');
			// Custom.getPDF(id).then(function(response) {
				// alert(response.success);
			// });
		}
		else {
			$window.alert('Nothing here');
		}
	};

	$scope.trash = function(idx) { 
		var num = $scope.emails.length;

		if ( num > 0 ) {

			var id = Miscellaneous.getIdFromIndex($scope.emails, idx);

			if ( num > 1 ) {

				if ( idx < num - 1 ) { //Delete and Go next
					var next = idx;

				    setTimeout( function()
				    {
						Emails.update({ id:id }).$promise.then(function (response) {
					        $scope.emails.splice(idx, 1);
							$scope.active = {};
							$scope.active[next] = 'success';
							$scope.menuidx = next;					
							$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.emails, next));				        
						});				    	

				    }, 100 );
				}
				else { //Delete and Go previous
					var prev = idx - 1;

					Emails.update({ id:id }).$promise.then(function (response) {
						$scope.active = {};
						$scope.active[prev] = 'success';
						$scope.menuidx = prev;					
						$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.emails, prev));
						$scope.emails.splice(idx, 1);
					});						
				}
			}
			else { //delete and die
				Emails.update({ id:id }).$promise.then(function (response) {
					$scope.emails.splice(idx, 1);
					$scope.active = {};
					$scope.active[prev] = 'success';
					$scope.menuidx = 0;					
					$location.path('/view/0');	
				});							
			}
		}
		else {
			$window.alert('Nothing to delete');
		}
		
	};

	$scope.analytics = function(idx) {
		if ( $scope.emails.length ) {
			var id = Miscellaneous.getIdFromIndex($scope.emails, idx);
			$location.path('/view/' + id + '/analytics');
		}
		else {
			$window.alert('Nothing here');
		}
	};

}]);


sentEmailsControllers.controller('EmailController', ['$scope', '$location', '$stateParams', 'Emails', 'Miscellaneous', function($scope, $location, $stateParams, Emails, Miscellaneous) {

	$scope.email = null;
	var url = "../../"; //angular.element('.view-email-body').data('domain');

	$scope.emails.$promise.then(function (emails) {
		var email = null;
		var ourIndex = Miscellaneous.getIndexFromId(emails, $stateParams.id);
		$scope.email = emails[ourIndex];
		$scope.email.url = url + 'email/backend-show/' + $stateParams.id;

		//Recipients to display
		$scope.showReadMore = false;
		$scope.numToShow = 10;
		var largeNum = 999999;

		$scope.showMe = function(num) {
			if ( parseInt(num) + 1 > $scope.numToShow ) {
				$scope.remaining = $scope.email.analytics.length - $scope.numToShow;
				$scope.showReadMore = true;
				return false;
			}
			else
				return true;
		};

		$scope.showOthers = function() {
			$scope.numToShow = largeNum;
			$scope.showReadMore = false;
		}
	});

	window.resizeIframe = function(obj) {
    	{
        	obj.style.height = 0;
    	};

    	{
        	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    	}
	};	

}]);


sentEmailsControllers.controller('AnalyticsController', ['$scope', '$location', '$stateParams', 'Emails', 'googleChartApiPromise', 'Miscellaneous', function($scope, $location, $stateParams, Emails, googleChartApiPromise, Miscellaneous) {

	$scope.statusChart = {}; $scope.browsersChart = {}; $scope.clicksChart = {}; $scope.countriesChart = {}; 
	// $scope.statusChart.type = 'ColumnChart'; $scope.statusChart.type = 'PieChart'; $scope.clicksChart.type = 'PieChart'; $scope.countriesChart.type = 'PieChart';

	var status = []; var browsers = []; var clicks = []; var countries = [];

	$scope.emails.$promise.then(function (emails) {
		var ourIndex = Miscellaneous.getIndexFromId(emails, $stateParams.id);
		var email = emails[ourIndex];

		angular.forEach(email.analytics, function(analytic, index) {
			status.push(analytic.status);
			if ( analytic.client_name )
				browsers.push(analytic.client_name);
			if ( analytic.country )
				countries.push(analytic.country);
		});		

		angular.forEach(email.clicks, function(click, index) {
			clicks.push(click.url);
		});		

		googleChartApiPromise.then(function () {
	        var table1 = new google.visualization.DataTable();
	        table1.addColumn("string", "Status");
	        table1.addColumn("number", "Number");
	        table1.addRows( Miscellaneous.constructArrayForDataTable(status,0) );
	        $scope.statusChart = {
	            type: "PieChart", //ColumnChart
	            options: { 
	            	title: 'Email Status',
				    displayExactValues: true,
				    vAxis: {
			      		title: "Number",
				      	gridlines: {
				        	count: 2,
				        	color: 'transparent'
				      	}
				    },
				    hAxis: {
			      		title: "Status"
				    }	            	
	            },
	            data: table1
	        };
	    
	        var table2 = new google.visualization.DataTable();
	        table2.addColumn("string", "Status");
	        table2.addColumn("number", "Number");
	        table2.addRows( Miscellaneous.constructArrayForDataTable(browsers,0) );
	        $scope.browsersChart = {
	            type: "PieChart",
	            options: { 
	            	title: 'Recipients\' Browsers',
				    displayExactValues: true,
				    vAxis: {
			      		title: "Number",
				      	gridlines: {
				        	count: 10
				      	}
				    },
				    hAxis: {
			      		title: "Browser(Client)"
				    }	            	
	            },
	            data: table2
	        };

	        var table3 = new google.visualization.DataTable();
	        table3.addColumn("string", "Status");
	        table3.addColumn("number", "Number");
	        table3.addRows( Miscellaneous.constructArrayForDataTable(clicks,15) );
	        $scope.clicksChart = {
	            type: "PieChart",
	            options: { 
	            	title: 'Clicks',
				    displayExactValues: true,
				    vAxis: {
			      		title: "Number",
				      	gridlines: {
				        	count: 10
				      	}
				    },
				    hAxis: {
			      		title: "Link"
				    }	            	
	            },
	            data: table3
	        };

	        var table4 = new google.visualization.DataTable();
	        table4.addColumn("string", "Status");
	        table4.addColumn("number", "Number");
	        table4.addRows( Miscellaneous.constructArrayForDataTable(countries,0) );
	        $scope.countriesChart = {
	            type: "PieChart",
	            options: { 
	            	title: 'Recipients\' Countries',
				    displayExactValues: true,
				    vAxis: {
			      		title: "Number",
				      	gridlines: {
				        	count: 10
				      	}
				    },
				    hAxis: {
			      		title: "Country"
				    }	            	
	            },
	            data: table4
	        };

		});

	});
}]);


var draftsControllers = angular.module('draftsApp.controllers', ['ngSanitize', 'ngAnimate']);

draftsControllers.controller('DraftsController', ['$scope', '$location', '$stateParams','$window', 'Drafts', 'Miscellaneous', function($scope, $location, $stateParams, $window, Drafts, Miscellaneous) {
	
	$scope.drafts = Drafts.query();
	$scope.drafts.$promise.then(function (drafts) {
		$scope.drafts = drafts;

		//Scrollbar 
		angular.element('.scroll-y').mCustomScrollbar({
			theme:"inset-2-dark"
		});

		$scope.active = {};

		var splitPath = $location.path().split('/');
		var id = parseInt( splitPath[splitPath.length - 1] );

		if ( id > 0 && splitPath[splitPath.length - 2] == 'view') {
			$scope.active[Miscellaneous.getIndexFromId(drafts, id)] = 'success';

			$scope.menuidx = Miscellaneous.getIndexFromId(drafts, id);
		}

		if ( $location.path() == '' || $location.path() == '/' ) {
			if ( drafts.length ) {
				$location.path('/view/' + Miscellaneous.getIdFromIndex(drafts, 0));
				$scope.active[0] = 'success';
				$scope.menuidx = 0;
			}

			else {
				// $window.alert('Nothing here');
			}
		}

	});

	$scope.show = function(idx) {
		$scope.active = {};
		$scope.active[idx] = 'success';
		$scope.menuidx = idx;

		$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.drafts, idx));
	};

	$scope.edit = function(idx, url) {
		if ( $scope.drafts.length ) {
			var id = Miscellaneous.getIdFromIndex($scope.drafts, idx);
			$window.location.href = url + '/' + id + '/edit';
		}
		else {
			$window.alert('Nothing to edit');
		}
	};

	$scope.destroy = function(idx) { 
		var num = $scope.drafts.length;

		if ( num > 0 ) {

			var id = Miscellaneous.getIdFromIndex($scope.drafts, idx);

			if ( num > 1 ) {

				if ( idx < num - 1 ) { //Delete and Go next
					var next = idx;

				    setTimeout( function()
				    {
						Drafts.delete({ id:id }).$promise.then(function (response) {
					        $scope.drafts.splice(idx, 1);
							$scope.active = {};
							$scope.active[next] = 'success';
							$scope.menuidx = next;					
							$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.drafts, next));				        
						});				    	

				    }, 100 );
				}
				else { //Delete and Go previous
					var prev = idx - 1;

					Drafts.delete({ id:id }).$promise.then(function (response) {
						$scope.active = {};
						$scope.active[prev] = 'success';
						$scope.menuidx = prev;					
						$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.drafts, prev));
						$scope.drafts.splice(idx, 1);
					});						
				}
			}
			else { //delete and die
				Drafts.delete({ id:id }).$promise.then(function (response) {
					$scope.drafts.splice(idx, 1);
					$scope.active = {};
					$scope.active[prev] = 'success';
					$scope.menuidx = 0;					
					$location.path('/view/0');	
				});							
			}
		}
		else {
			$window.alert('Nothing to delete');
		}
		
	};	

}]);


draftsControllers.controller('DraftController', ['$scope', '$location', '$stateParams', 'Drafts', 'Miscellaneous', function($scope, $location, $stateParams, Drafts, Miscellaneous) {

	$scope.draft = null;
	var url = "../../";

	$scope.drafts.$promise.then(function (drafts) {
		var draft = null;
		var ourIndex = Miscellaneous.getIndexFromId(drafts, $stateParams.id);
		$scope.draft = drafts[ourIndex];
		$scope.draft.url = url + 'draft/backend-show/' + $stateParams.id;
	});

	window.resizeIframe = function(obj) {
    	{
        	obj.style.height = 0;
    	};

    	{
        	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    	}
	};	

}]);


var trashControllers = angular.module('trashApp.controllers', ['ngSanitize', 'ngAnimate']);

trashControllers.controller('TrashController', ['$rootScope', '$scope', '$location', '$stateParams','$window', 'Emails', 'Miscellaneous', function($rootScope, $scope, $location, $stateParams, $window, Emails, Miscellaneous) {
	
	$scope.emails = Emails.query();
	$scope.emails.$promise.then(function (emails) {
		$scope.emails = emails;

		//Scrollbar 
		angular.element('.scroll-y').mCustomScrollbar({
			theme:"inset-2-dark"
		});

		$scope.active = {};

		var splitPath = $location.path().split('/');
		var id = parseInt( splitPath[splitPath.length - 1] );

		if ( id > 0 && splitPath[splitPath.length - 2] == 'view') {
			$scope.active[Miscellaneous.getIndexFromId(emails, id)] = 'success';

			$scope.menuidx = Miscellaneous.getIndexFromId(emails, id);
		}

		if ( $location.path() == '' || $location.path() == '/' ) {
			if ( emails.length ) {
				$location.path('/view/' + Miscellaneous.getIdFromIndex(emails, 0));
				$scope.active[0] = 'success';
				$scope.menuidx = 0;
			}

			else {
				// $window.alert('Nothing here');
			}
		}

	});

	$scope.showEmail = function(idx) {
		$scope.active = {};
		$scope.active[idx] = 'success';
		$scope.menuidx = idx;

		$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.emails, idx));
	};

	$scope.recycle = function(idx) { 
		var num = $scope.emails.length;

		if ( num > 0 ) {

			var id = Miscellaneous.getIdFromIndex($scope.emails, idx);

			if ( num > 1 ) {

				if ( idx < num - 1 ) { //Restore and Go next
					var next = idx;

				    setTimeout( function()
				    {
						Emails.update({ id:id }).$promise.then(function (response) {
					        $scope.emails.splice(idx, 1);
							$scope.active = {};
							$scope.active[next] = 'success';
							$scope.menuidx = next;					
							$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.emails, next));				        
					        // $scope.$apply();
						});				    	

				    }, 100 );
				}
				else { //Restore and Go previous
					var prev = idx - 1;

					Emails.update({ id:id }).$promise.then(function (response) {
						$scope.active = {};
						$scope.active[prev] = 'success';
						$scope.menuidx = prev;					
						$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.emails, prev));
						$scope.emails.splice(idx, 1);
					});						
				}
			}
			else { //Restore and die
				Emails.update({ id:id }).$promise.then(function (response) {
					$scope.emails.splice(idx, 1);
					$scope.active = {};
					$scope.active[prev] = 'success';
					$scope.menuidx = 0;					
					$location.path('/view/0');	
				});							
			}
		}
		else {
			$window.alert('Nothing to restore');
		}
	};	


	$scope.destroy = function(idx) { 
		var num = $scope.emails.length;

		if ( num > 0 ) {

			var id = Miscellaneous.getIdFromIndex($scope.emails, idx);

			if ( num > 1 ) {

				if ( idx < num - 1 ) { //Destroy and Go next
					var next = idx;

				    setTimeout( function()
				    {
						Emails.delete({ id:id }).$promise.then(function (response) {
					        $scope.emails.splice(idx, 1);
							$scope.active = {};
							$scope.active[next] = 'success';
							$scope.menuidx = next;					
							$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.emails, next));				        
					        // $scope.$apply();
						});				    	

				    }, 100 );
				}
				else { //Destroy and Go previous
					var prev = idx - 1;

					Emails.delete({ id:id }).$promise.then(function (response) {
						$scope.active = {};
						$scope.active[prev] = 'success';
						$scope.menuidx = prev;					
						$location.path('/view/' + Miscellaneous.getIdFromIndex($scope.emails, prev));
						$scope.emails.splice(idx, 1);
					});						
				}
			}
			else { //Destroy and die
				Emails.delete({ id:id }).$promise.then(function (response) {
					$scope.emails.splice(idx, 1);
					$scope.active = {};
					$scope.active[prev] = 'success';
					$scope.menuidx = 0;					
					$location.path('/view/0');	
				});							
			}
		}
		else {
			$window.alert('Nothing to delete');
		}
	};	

}]);

trashControllers.controller('ViewTrashController', ['$scope', '$location', '$stateParams', 'Emails', 'Miscellaneous', function($scope, $location, $stateParams, Emails, Miscellaneous) {

	$scope.email = null;
	var url = "../../";

	$scope.emails.$promise.then(function (emails) {
		var email = null;
		var ourIndex = Miscellaneous.getIndexFromId(emails, $stateParams.id);
		$scope.email = emails[ourIndex];
		$scope.email.url = url + 'email/backend-show/' + $stateParams.id;

		//Recipients to display
		$scope.showReadMore = false;
		$scope.numToShow = 10;
		var largeNum = 999999;

		$scope.showMe = function(num) {
			if ( parseInt(num) + 1 > $scope.numToShow ) {
				$scope.remaining = $scope.email.analytics.length - $scope.numToShow;
				$scope.showReadMore = true;
				return false;
			}
			else
				return true;
		};

		$scope.showOthers = function() {
			$scope.numToShow = largeNum;
			$scope.showReadMore = false;
		}		
	});

	window.resizeIframe = function(obj) {
    	{
        	obj.style.height = 0;
    	};

    	{
        	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    	}
	};	

}]);