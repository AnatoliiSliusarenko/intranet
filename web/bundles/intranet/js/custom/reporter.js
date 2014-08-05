Intranet.controller('ReporterController', ['$scope', '$http', '$modal', function($scope, $http, $modal){
	console.log('ReporterController was loaded!');
	
	$scope.filter = {
			query: 'type1'
	}
	$scope.timeoutHandler = null;
	
	$scope.$watch('filter', function(){
		if ($scope.timeoutHandler != null) clearTimeout($scope.timeoutHandler);
		$scope.timeoutHandler = setTimeout(queryReport, 2000);
	}, true);
	
	$scope.table = null;
	$scope.tasks = TASKS;
	$scope.users = USERS;
	$scope.statuses = STATUSES;
	$scope.urlsQueryReport = JSON_URLS.queryReport;
	
	function queryReport()
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