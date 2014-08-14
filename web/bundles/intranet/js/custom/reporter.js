Intranet.controller('ReporterController', ['$scope', '$http', '$modal', function($scope, $http, $modal){
	console.log('ReporterController was loaded!');
	
	$scope.filter = {
			query: 'type1'
	}
	
	$scope.table = null;
	$scope.tasks = TASKS;
	$scope.users = USERS;
	$scope.statuses = STATUSES;
	$scope.urlsQueryReport = JSON_URLS.queryReport;
	
	
	$scope.queryReport = function ()
	{
		console.log('filter===>', $scope.filter);
		$http({
			method: "POST", 
			url: $scope.urlsQueryReport,
			data: $scope.filter
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.table = response.result;
			}
		})
	}
}]);