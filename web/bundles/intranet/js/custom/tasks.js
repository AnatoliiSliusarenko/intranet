Intranet.controller('TasksController', ['$scope', '$http', function($scope, $http){
	console.log('TasksController was loaded!');
	
	$scope.tasks = [];
	
	$scope.urlsTasksGet = JSON_URLS.tasksGet;
	$scope.urlsTasksRemove = JSON_URLS.tasksRemove;
	$scope.urlsTopicsShow = JSON_URLS.topicsShow;
	$scope.urlsTasksAdd = JSON_URLS.tasksAdd;
	
	function getTasks()
	{
		$http({
			method: "GET", 
			url: $scope.urlsTasksGet
			  })
		.success(function(response){
			if (response.result)
			{
				_.map(response.result, function(task){task.currentTopicId = (task.topics.length > 0) ? task.topics[0].id : 0});
				$scope.tasks = response.result;
			}
		})
	}
	
	getTasks();
	
	$scope.removeTask = function(task)
	{
		var remove = confirm("Realy want to remove?");
		if (!remove) return;
		var url = $scope.urlsTasksRemove.replace('0', task.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				_.map($scope.tasks, function(task, i){
					if (task.id == response.result) $scope.tasks.splice(i, 1);
				});
			}
		})
	}
	
	$scope.showTopic = function(task)
	{
		var url = $scope.urlsTopicsShow.replace('0', task.currentTopicId);
		window.location.href = url;
	}
	
	$scope.addTask = function()
	{	
		$http({
			method: "GET", 
			url: $scope.urlsTasksAdd
			  })
		.success(function(response){
			$(".content").append(response);
			  $('#popup').bPopup({
				    position: [window.innerWidth/2 - 250, 100],
				    easing: 'easeOutBack',
			        speed: 800,
			        transition: 'slideDown',
			        onClose: function(){
			        	$("#popup").remove();
					}
			  });
		})
	}
}])
.controller('AddTasksController', ['$scope', '$http', function($scope, $http){
	console.log('AddTasksController loaded');
	
}]);