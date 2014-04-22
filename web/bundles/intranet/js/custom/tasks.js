Intranet.controller('TasksController', ['$scope', '$http', '$modal', function($scope, $http, $modal){
	console.log('TasksController was loaded!');
	
	$scope.tasks = [];
	$scope.users = USERS;
	$scope.topics = TOPICS;
	
	$scope.urlsTasksGet = JSON_URLS.tasksGet;
	$scope.urlsTasksRemove = JSON_URLS.tasksRemove;
	$scope.urlsTasksEdit = JSON_URLS.tasksEdit;
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
			var modalInstance = $modal.open({
			      template: response,
			      controller: 'AddTasksController',
			      resolve: {
			    	  users: function(){return $scope.users;},
			    	  topics: function(){return $scope.topics;}
			      }
			    });
			
			modalInstance.result.then(function (addedTask) {
				addedTask.currentTopicId = (addedTask.topics.length > 0) ? addedTask.topics[0].id : 0;
				$scope.tasks.push(addedTask);
			}, function(){});
		})
	}
	
	$scope.editTask = function(task)
	{
		var url = $scope.urlsTasksEdit.replace('0', task.id);
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			var modalInstance = $modal.open({
			      template: response,
			      controller: 'EditTasksController',
			      resolve: {
			    	  task: function(){return task;},
			    	  users: function(){return $scope.users;},
			    	  topics: function(){return $scope.topics;}
			      }
			    });
			
			modalInstance.result.then(function (editedTask) {
				console.log(editedTask);
				_.map($scope.tasks, function(task, i){
					if (task.id == editedTask.id) {
						$scope.tasks[i].users = [];
						_.map(editedTask.users, function(u){$scope.tasks[i].users.push(u)});
						$scope.tasks[i].topics = [];
						_.map(editedTask.topics, function(t){$scope.tasks[i].topics.push(t)});
						task.currentTopicId = (task.topics.length > 0) ? task.topics[0].id : 0;
					}
				});
			}, function(){});
		})
	}
}])
.controller('AddTasksController', ['$scope', '$http', '$modalInstance', 'users', 'topics', function($scope, $http, $modalInstance, users, topics){
	console.log('AddTasksController was loaded!');
	
	$scope.urlsTasksAdd = JSON_URLS.tasksAdd;
	
	$scope.users = users;
	$scope.topics = topics;
	$scope.task = {
			status: 'opened',
			priority: 'high'
	};
	
	$scope.addTask = function(event)
	{
		if ($scope.task.name != undefined)
			event.preventDefault();
		$http({
			method: "POST", 
			url: $scope.urlsTasksAdd,
			data: $scope.task
			  })
		.success(function(response){
			if (response.result)
				$modalInstance.close(response.result);
		})
	}
	
}])
.controller('EditTasksController', ['$scope', '$http', '$modalInstance', 'task', 'users', 'topics', function($scope, $http, $modalInstance, task, users, topics){
	console.log('EditTasksController was loaded!');
	
	$scope.urlsTasksEdit = JSON_URLS.tasksEdit;
	
	$scope.users = users;
	$scope.topics = topics;
	$scope.task = task;
	
	$scope.editTask = function(event)
	{
		if ($scope.task.name != undefined)
			event.preventDefault();
		
		var url = $scope.urlsTasksEdit.replace('0', task.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.task
			  })
		.success(function(response){
			if (response.result)
				$modalInstance.close(response.result);
		})
	}
	
	$scope.hasTopic = function(topic)
	{
		return _.find($scope.task.topics, function(t){
			return t.id==topic.id;
		});
	}
	
	$scope.hasUser = function(user)
	{
		return _.find($scope.task.users, function(u){
			return u.id==user.id;
		});
	}
	
}]);