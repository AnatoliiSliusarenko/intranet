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
	}, true);
	
	$scope.tasks = [];
	$scope.users = USERS;
	$scope.topics = TOPICS;
	$scope.tasksNotification = TASKS_NOTIFICATIONS;
	
	$scope.urlsTasksGet = JSON_URLS.tasksGet;
	$scope.urlsTasksRemove = JSON_URLS.tasksRemove;
	$scope.urlsTasksEdit = JSON_URLS.tasksEdit;
	$scope.urlsTopicsShow = JSON_URLS.topicsShow;
	$scope.urlsTasksAdd = JSON_URLS.tasksAdd;
	$scope.urlsPostsTaskShow = JSON_URLS.urlsPostsTaskShow;
	
	function calculateNotifications()
	{
		$scope.tasksNotification = TASKS_NOTIFICATIONS;
		_.map($scope.tasks, function(task){
			task.newCommentsCount = 0;
			_.map($scope.tasksNotification, function(tn){
				if(tn.resourceid == task.id && tn.type=='task_comment')
				{
					task.newCommentsCount++;
				}
			});
			if (typeof task.subtasks != 'undefined')
			{
				_.map(task.subtasks, function(subtask){
					subtask.newCommentsCount = 0;
					_.map($scope.tasksNotification, function(stn){
						if(stn.resourceid==subtask.id)
							subtask.newCommentsCount++;
					});
				});
			}
		});
    	
	};
	$scope.$watch(function(){return TASKS_NOTIFICATIONS;}, function(input) {
		calculateNotifications();
    });
	
	function prepareTasks(tasks)
	{
		_.map(tasks, function(task){
			$scope.changeHrefTopic(task);
			if (task.parentid == null) task.subtasks = [];
		});
		var groupedList = _.groupBy(tasks, function(task){ return task.parentid; });
		
		var topList = groupedList[null];
		delete groupedList[null];
		_.map(topList, function(task){
			if (typeof groupedList[task.id] != 'undefined') 
			{
				task.subtasks = groupedList[task.id]; 
				delete groupedList[task.id];
			}
		});
		
		for (key in groupedList)
			topList = topList.concat(groupedList[key]);
		return topList;
	}
	
	function resetTasks(task)
	{
			_.map($scope.tasksNotification, function(tn){
				if(tn.resourceid==task.id)
					task.newCommentsCount=0;
			});
	}
	
	function getTasks()
	{	
		$http({
			method: "POST", 
			url: $scope.urlsTasksGet,
			data: $scope.filter
			  })
		.success(function(response){
			//console.log(response);
			if (response.result)
			{
				$scope.tasks = prepareTasks(response.result);
				setTimeout(addTooltips, 100);
			}
		})
	}
	
	////
	_.map(STATUSES, function(s){
		$scope.filter.status.push(s.id);
	});
	$scope.filter.status.splice(14, 1);
	////
	
	getTasks();
	
	$scope.changeDrop = function(task)
	{
		if (task.subtasks.length == 0) return;
		task.dropped = !task.dropped;
	}
	
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
	
	$scope.changeHrefTopic = function(task)
	{	
		url = $scope.urlsTopicsShow.replace('0', task.topicid);
		task.hrefTopic = url;
	}
	
	function addTooltips()
	{
		$('.tooltips').tooltip({
		      selector: "a",
		      container: "body"
		    });

		$("[data-toggle=popover]").popover();
	}
	
	$scope.addTask = function(task)
	{	
		var parentid = (typeof task != 'undefined') ? task.id : null;
		$http({
			method: "GET", 
			url: $scope.urlsTasksAdd,
			params: {parentid: parentid}
			  })
		.success(function(response){
			var modalInstance = $modal.open({
			      template: response,
			      controller: 'AddTasksController',
			      resolve: {
			    	  users: function(){return $scope.users;},
			    	  parentid: function(){return parentid;}
			      }
			    });
			
			modalInstance.result.then(function (addedTask) {
				$scope.changeHrefTopic(addedTask);
				if (addedTask.parentid == null)
				{
					addedTask.subtasks = [];
					$scope.tasks.push(addedTask);
				}else
				{
					_.map($scope.tasks, function(task, i){
						if (task.id == addedTask.parentid)
							$scope.tasks[i].subtasks.push(addedTask);
					});
				}
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
			    	  users: function(){return $scope.users;}
			      }
			    });
			
			modalInstance.result.then(function (editedTask) {
				console.log(editedTask);
				_.map($scope.tasks, function(task, i){
					if (task.id == editedTask.id) {
						$scope.tasks[i].topic = editedTask.topic;
						$scope.tasks[i].user = editedTask.user;
						$scope.tasks[i].startdate = editedTask.startdate;
						$scope.tasks[i].enddate = editedTask.enddate;
						$scope.tasks[i].status = editedTask.status;
						$scope.tasks[i].statusUpdated = editedTask.statusUpdated;
						$scope.changeHrefTopic($scope.tasks[i]);
					}else
					{
						_.map(task.subtasks, function(subtask, j){
							if (subtask.id == editedTask.id) {
								$scope.tasks[i].subtasks[j].topic = editedTask.topic;
								$scope.tasks[i].subtasks[j].user = editedTask.user;
								$scope.tasks[i].subtasks[j].startdate = editedTask.startdate;
								$scope.tasks[i].subtasks[j].enddate = editedTask.enddate;
								$scope.tasks[i].subtasks[j].status = editedTask.status;
								$scope.tasks[i].subtasks[j].statusUpdated = editedTask.statusUpdated;
								$scope.changeHrefTopic($scope.tasks[i].subtasks[j]);
							}
						});
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
			
            modalInstance.result.finally(function () {;
				if (modalInstance.result.response.result != null)
				{
					_.map($scope.tasks, function(t){
						if(t.id = modalInstance.result.response.result)
						{
							t.newCommentsCount = 0;
						}
					});
				}
				clearInterval(refreshIntervalId);
			});
            var refreshIntervalId = setInterval(function(){
            	if (modalInstance.result.response.result != null)
			{
				_.map($scope.tasks, function(t){
					if(t.id = modalInstance.result.response.result)
					{
						t.newCommentsCount = 0;
					}
				});
			} }, 2000);
		});
	}
}])
.controller('AddTasksController', ['$scope', '$http', '$modalInstance', 'users', 'parentid', function($scope, $http, $modalInstance, users, parentid){
	console.log('AddTasksController was loaded!');
	
	$scope.urlsTasksAdd = JSON_URLS.tasksAdd;
	
	$scope.estimated = false;
	$scope.users = users;
	$scope.task = {
			statusid: null,
			priority: 'high',
			userid: null,
			parentid: parentid,
			esth: 0,															
			estm: 0,
			topicid: null
	};
	
	setTimeout(function(){
		var topics = angular.element('#topics');
		if (topics[0].length>0)
		{
			$(topics[0][1]).attr('selected', true);
			$scope.task.topicid = $(topics[0][1]).val();
			topics[0][0] = null;
		}
	}, 500);
	
	STATUSES.forEach(function(status){
		if (status.initial && $scope.task.statusid == null)
			$scope.task.statusid = status.id;
	});
	checkEstimated();
	
	$scope.checkEstimated = checkEstimated;
	function checkEstimated()
	{
		_.map(STATUSES, function(s){
			if (s.id == $scope.task.statusid)
				$scope.estimated = s.updateEstimate;
		});
	}
	
	$scope.addTask = function(event)
	{
		if ($scope.task.name != undefined)
			event.preventDefault();				
		
		$scope.task.estimated = parseInt($scope.task.esth)*60 + parseInt($scope.task.estm);
		
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
.controller('EditTasksController', ['$scope', '$http', '$modalInstance', 'task', 'users', function($scope, $http, $modalInstance, task, users){
	console.log('EditTasksController was loaded!');
	
	$scope.urlsTasksEdit = JSON_URLS.tasksEdit;
	
	$scope.task = task;
	
	$scope.estimated = false;
	checkEstimated();
	
	$scope.checkEstimated = checkEstimated;
	function checkEstimated()
	{
		_.map(STATUSES, function(s){
			if (s.id == $scope.task.statusid)
				$scope.estimated = s.updateEstimate;
		});
	}
	(function(){
		$scope.task.esth = Math.floor($scope.task.estimated/60);
		$scope.task.estm = $scope.task.estimated%60;
	})();
	
	$scope.users = users;
	
	$scope.task.topicsIdsCurrent = _.map($scope.task.topics, function(t){return t.id;})
		
	$scope.editTask = function(event)
	{	
		if ($scope.task.name != undefined)
			event.preventDefault();
		
		var url = $scope.urlsTasksEdit.replace('0', task.id);
		
		_.map(STATUSES, function(s){
			if ((s.id == $scope.task.statusid) && (s.updateEstimate == true))
				$scope.task.estimated = parseInt($scope.task.esth)*60 + parseInt($scope.task.estm);
		});
		
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
}])
.controller('ShowPostsController', ['$scope', '$http', '$modalInstance', 'task', function($scope, $http, $modalInstance, task){
	console.log('ShowPostsController was loaded!');
	
	$scope.addingDocuments = false;
	$scope.task = task;
	$scope.posts = [];
	$scope.urlsPostsTaskAdd = JSON_URLS.urlsPostsTaskAdd.replace('0', task.id);
	$scope.urlsPostsTaskGet = JSON_URLS.urlsPostsTaskGet.replace('0', task.id);
	$scope.urlsResetCommentsCount = JSON_URLS.urlsResetCommentsCount.replace('0', task.id);
	$scope.avatarURL = JSON_URLS.avatar;
	$scope.urlsBase = JSON_URLS.baseUrl;
	
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
			
	});
	
	$http({
		method: "GET", 
		url: $scope.urlsResetCommentsCount
		  })
	.success(function(response){
		$modalInstance.result.response = response;
	});
	
	function bindList()
	{
		$('.finish').click(function(){
			$(this).parent().toggleClass('finished');
			$(this).toggleClass('fa-square-o');
		});
	}
	
	setTimeout(function(){
	    $('#file_upload').uploadify({
	    	'fileSizeLimit': 0,
	    	'progressData' : 'speed',
	    	'formData'     : {
				'timestamp' : TIMESTAMP,
				'token'     : TOKEN
			},
	    	'buttonText' : 'Upload files...',
	        'swf'      : JSON_URLS.uploaderSWF,
	        'uploader' : JSON_URLS.uploaderUpload,
	        'onUploadSuccess' : function(file, data, response) {
	            console.log('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
	            getDocuments();
	        }
	    });
		    
	},500);
	
	$scope.documents = [];
	$scope.urlsDocumentsGet = JSON_URLS.documentsGet;
	
	function prepareDocuments(documents)
	{
		return _.map(documents, function(d){d.checked = false; return d;});
	}
	
	function getDocuments()
	{
		$http({
			method: "GET", 
			url: $scope.urlsDocumentsGet,
			params: {
				userid: USER.id
			}
			  })
		.success(function(response){
			console.log(response);
			if (response.result)	
			{
				$scope.documents = prepareDocuments(response.result);
				setTimeout(bindList, 500);
			}
		})
	}
	
	$scope.checkItem = function(documentid)
	{
		_.map($scope.documents, function(d){
			if (d.id == documentid) d.checked = !d.checked;
		});
	}
	
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
	
	function insertDocumentsLinks(documents)
	{
		_.map(documents, function(d){
			$scope.comment += '\n<a href="'+$scope.urlsBase+d.url+'" download="'+d.name+'">'+d.name+'</a>';
		});
	}
	
	$scope.addDocuments = function()
	{
		if ($scope.addingDocuments == false)
		{
			getDocuments();
		}else
		{
			insertDocumentsLinks(_.filter($scope.documents, function(d){return d.checked;}));
		}
		
		$scope.addingDocuments = !$scope.addingDocuments;
	}
}]);
