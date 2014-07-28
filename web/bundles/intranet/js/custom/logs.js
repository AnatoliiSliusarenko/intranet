Intranet.controller('LogsController', ['$scope', '$http', '$modal', function($scope, $http, $modal){
	console.log('LogsController was loaded!');
	
	$scope.filter = {
			task: [],
			user: [],
			type: [],
			from: null,
			to: null
	}
	
	$scope.$watch('filter', function(){
		getLogs();
	}, true);
	
	$scope.logs = [];
	$scope.tasks = []//from global
	$scope.users = []//from global
	
	function getLogs()
	{
		return true;
	}

}]);