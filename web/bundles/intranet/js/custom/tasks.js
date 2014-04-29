Intranet.controller('TasksController', ['$scope', '$http', '$modal', function($scope, $http, $modal){
	console.log('TasksController was loaded!');
	
	$scope.filter = {
			status: [],
			priority: [],
			name: "",
			user: [],
			topic: []
	}
	
	$scope.$watch('filter', function(){
		getTasks();
		//console.log($scope.filter);
	}, true);
	
	$scope.tasks = [];
	$scope.users = USERS;
	$scope.topics = TOPICS;
	
	$scope.urlsTasksGet = JSON_URLS.tasksGet;
	$scope.urlsTasksRemove = JSON_URLS.tasksRemove;
	$scope.urlsTasksEdit = JSON_URLS.tasksEdit;
	$scope.urlsTopicsShow = JSON_URLS.topicsShow;
	$scope.urlsTasksAdd = JSON_URLS.tasksAdd;
	$scope.urlsPostsTaskShow = JSON_URLS.urlsPostsTaskShow;
	
	function getTasks()
	{	
		$http({
			method: "POST", 
			url: $scope.urlsTasksGet,
			data: $scope.filter
			  })
		.success(function(response){
			console.log(response);
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
	
	$scope.showPosts = function(task)
	{
		var url = $scope.urlsPostsTaskShow.replace('0', task.id);
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			var modalInstance = $modal.open({
			      template: response,
			      controller: 'ShowPostsController',
			      resolve: {
						task: function(){return task;}
					}
			    });
			
			modalInstance.result.then(function (currentTask) {
				
			
			}, function(){});
		});
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
	$scope.task.usersIds = _.map($scope.task.users, function($e){return $e.id});
	$scope.task.topicsIds = _.map($scope.task.topics, function($e){return $e.id});
	
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
		return _.find($scope.task.topicsIds, function(t){
			return t==topic.id;
		});
	}
	
	$scope.hasUser = function(user)
	{
		return _.find($scope.task.usersIds, function(u){
			return u==user.id;
		});
	}
	
}])
.controller('ShowPostsController', ['$scope', '$http', '$modalInstance', 'task', function($scope, $http, $modalInstance, task){
	console.log('ShowPostsController was loaded!');
	
	
	$scope.posts = [];
	$scope.urlsPostsTaskAdd = JSON_URLS.urlsPostsTaskAdd;
	$scope.urlsPostsTaskGet = JSON_URLS.urlsPostsTaskGet.replace('0', task.id);
	$scope.avatarURL = JSON_URLS.avatar;
	$scope.comment = "";
	$scope.editingPost = null;
	$scope.entityid = task.id;
	$scope.userid = window.USER.id;
	
	$http({
		method: "GET", 
		url: $scope.urlsPostsTaskGet
		  })
	.success(function(response){
		console.log(response);
		if (response.result)
		{
			$scope.containerTask = $('#conversation-task');
			$scope.messageContainerTask = $('#write-message-task');
			$scope.posts = response.result;
			$scope.containerTask.animate({ scrollTop: $scope.containerTask.height()+1900 },1000);
		}
			
	})
	
	$scope.isEditable = function(post)
	{
		var postedTime = new Date(Date.parse(post.posted.date));
		var now = new Date();
		var utc = new Date(now.getTime() + now.getTimezoneOffset() * 60000);
		
		var minutesAgo = Date.minutesBetween(postedTime, utc);

		return (minutesAgo <= 5 && post.userid == $scope.userid);
	}
	
	$scope.editPost = function(post)
	{
		if ((!$scope.isEditable(post)) || ($scope.editingPost != null)) return;
		console.log(post);
		$scope.editingPost = post;
		$scope.messageContainerTask.val(post.message);
	}
	
	$scope.pressEnter = function(e)
	{
		if ((e.shiftKey == false) && ( e.keyCode == 13 ))
		{
			e.preventDefault();
			$scope.addPost();
		}
	}
	
	$scope.addPost = function()
	{
		$scope.comment = $scope.messageContainerTask.val();
		var post = {
				entityid: $scope.entityid, 
				userid: $scope.userid,
				message: $scope.comment,
				posted: new Date()
		}
		
		if ($scope.editingPost)
			post.postid = $scope.editingPost.id;
		
		$http({
			method: "POST", 
			url: $scope.urlsPostsTaskAdd, 
			data: post })
		.success(function(response){
			console.log("Created post for task: ", response.result);
			if (response.result)
			{
				// maybe need to request for posts and init paginator!!!
				if ($scope.editingPost == null)
				{
					$scope.posts.push(response.result);
					$scope.containerTask.animate({ scrollTop: $scope.containerTask.height()+1900 },1000);
				}
				else
				{
					_.map($scope.posts, function(p, i){
						if (p.id == response.result.id)
							$scope.posts[i] = response.result;
					});
				}
			}
			
			$scope.editingPost = null;
			$scope.comment = "";
			$scope.messageContainerTask.val("");
			$scope.messageContainerTask.focus();
		})
	}
}]);