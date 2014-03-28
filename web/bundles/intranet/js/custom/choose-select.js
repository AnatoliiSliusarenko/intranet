Intranet.controller('ChooseSelectController', ['$scope', function($scope){
	officeUsersList = $('#officeUsersList');
	usersList = $('#usersList');
	
	$scope.users = window.USERS;
	$scope.officeUsers = window.OFFICE_USERS;
	
	(function(){
		officeUsersIds = _.map($scope.officeUsers, function(e){return e.id;});
		$scope.users = _.filter($scope.users, function(e){return !_.contains(officeUsersIds, e.id)});
	})();
	
	$scope.addToMembers = function(e){
		e.preventDefault();
		
		var usersListSelected = _.map(usersList.find(":selected"), function(opt){return parseInt(opt.value)});
		
		_.map(usersListSelected, function(id){
			user = _.findWhere($scope.users, {'id': id});
			$scope.officeUsers.push(user);
		});
		
		$scope.users = _.filter($scope.users, function(e){return !_.contains(usersListSelected, e.id)});
	}
	
	$scope.removeFromMembers = function(e){
		e.preventDefault();
		
		var officeUsersListSelected = _.map(officeUsersList.find(":selected"), function(opt){return parseInt(opt.value)});
	
		_.map(officeUsersListSelected, function(id){
			user = _.findWhere($scope.officeUsers, {'id': id});
			$scope.users.push(user);
		});
		
		$scope.officeUsers = _.filter($scope.officeUsers, function(e){return !_.contains(officeUsersListSelected, e.id)});
	}
	
	$scope.makeAllSelected = function(){
		$.each(officeUsersList.find("option"), function(i, v){v.selected = true;});
	}
	
}]);