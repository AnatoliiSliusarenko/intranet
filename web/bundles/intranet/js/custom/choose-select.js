angular.module('Intranet', [])
.config(['$interpolateProvider', '$httpProvider', function ($interpolateProvider, $httpProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
}])
.controller('ChooseSelectController', ['$scope', function($scope){
	officeUsersList = $('#officeUsersList');
	usersList = $('#usersList');
	
	$scope.users = window.USERS;
	$scope.officeUsers = window.OFFICE_USERS;
	
	$scope.addToMembers = function(e){
		e.preventDefault();
		var usersListSelected = _.map(usersList.find(":selected"), function(opt){return opt.value});
		console.log(usersListSelected);
	}
	
	$scope.removeFromMembers = function(e){
		e.preventDefault();
		var officeUsersListSelected = _.map(officeUsersList.find(":selected"), function(opt){return opt.value});
	}
	
}]);