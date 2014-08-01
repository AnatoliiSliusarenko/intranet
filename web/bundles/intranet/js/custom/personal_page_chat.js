
Intranet.controller('PersonalChatController',['$http', '$scope', '$personalPageChat', '$paginator', function($http, $scope, $personalPageChat, $paginator) {
	
	$scope.paginator = $paginator;
	
	$scope.postsPerPage = 10;
	$scope.paginator.postsPerPage = $scope.postsPerPage;
	
	$scope.$watch('paginator.curPageId', function(){
		var offset = $scope.paginator.postsPerPage*($scope.paginator.curPageId - 1);
		var limit = $scope.paginator.postsPerPage;
		$personalPageChat.init($paginator, $http)
		$personalPageChat.getPosts();
		console.log("curPageId: paginator--->", $scope.paginator);
	});
	
	console.log('=========>>>> PersonalController was loaded');

  }]);