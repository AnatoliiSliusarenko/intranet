Intranet.controller('TasksController', ['$scope', '$http', function($scope, $http){
	alert('TasksController was loaded!');
	
	$scope.tasks = [];
	
	$scope.urlsPostsGet = JSON_URLS.tasksGet;
	
	function getTasks()
	{
		$http({
			method: "GET", 
			url: $scope.urlsPostsGet
			  })
		.success(function(response){
			console.log(response);
		})
	}
	
	getTasks();
	
}]);