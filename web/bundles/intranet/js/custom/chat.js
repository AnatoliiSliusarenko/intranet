Intranet.controller('ChatController', ['$scope', '$http', '$paginator', '$modal', function($scope, $http, $paginator, $modal){
	container = $('#conversation');
	messageContainer = $('#write-message');
	
	$scope.paginator = $paginator;
	$scope.postsPerPage = 20;
	$scope.paginator.postsPerPage = $scope.postsPerPage;
	
	$scope.$watch('paginator.curPageId', function(){
		var offset = $scope.paginator.postsPerPage*($scope.paginator.curPageId - 1);
		var limit = $scope.paginator.postsPerPage;
		getPosts(offset, limit);
	});
		
	$scope.pressEnter = function(e)
	{
		if ((e.shiftKey == false) && ( e.keyCode == 13 ))
		{
			e.preventDefault();
			$scope.sendPost();
		}
	}
	
	$scope.posts = [];
	$scope.members = [];
	$scope.message = '';
	$scope.editingPost = null;
	$scope.lastDate = null;
	
		
	$scope.postsGetURL = JSON_URLS.posts;
	$scope.avatarURL = JSON_URLS.avatar;
	$scope.postAddURL = JSON_URLS.post_add;
	$scope.membersURL = JSON_URLS.members;
	$scope.postsNewURL = JSON_URLS.posts_new;
	$scope.postsCountURL = JSON_URLS.post_count;
	$scope.urlsDocumentsAdd = JSON_URLS.documentsAdd;
	$scope.urlsBase = JSON_URLS.baseUrl;
	$scope.urlSendPrivateMsg = JSON_URLS.sendPrivateMsg;
	
	$scope.entityid = window.ENTITY.id;
	$scope.userid = window.USER.id;
	
	function updatePosts(posts)
	{
		var editedMessages = _.filter(posts, function(p){return p.edited;});
		if (editedMessages.length == posts.length)
		{
			_.map(posts, function(post){
				_.map($scope.posts, function(p, i){
					if (p.id == post.id)
						$scope.posts[i] = post;
				});
			});
			return true;
		}
		
		return false;
	}
	
	function getPosts(offset, limit)
	{
		$http({
			method: "GET", 
			url: $scope.postsGetURL, 
			params: {offset: offset, limit: limit}})
		.success(function(response){
			if (response.result)
			{
				$scope.posts = response.result.reverse();
			}
			if (response.result.length>0)
			{
				$scope.lastDate = (_.last($scope.posts)).posted.date;
			}
			container.animate({ scrollTop: container.height()+1900 },1000);
		})
	}
	
	function getPostsCount(callback)
	{
		$http({
			method: "GET", 
			url: $scope.postsCountURL
			})
		.success(function(response){
			if (response.result)
				callback(response.result);
		})
	}
	
	function getMembers()
	{
		$http({
			method: "GET", 
			url: $scope.membersURL
			})
		.success(function(response){
			if (response.result)
				$scope.members = response.result;
		})
	}
	

	function getNewPosts()
	{
		if ($scope.paginator.curPageId == 1)
		{
			$http({
				method: "GET", 
				url: $scope.postsNewURL, 
				params: {last_posted: $scope.lastDate}})
			.success(function(response){
				if ((response.result) && (response.result.length > 0))
				{	
					var onlyUpdated = updatePosts(response.result);
					if (onlyUpdated == false)
					{
						getPostsCount(function(postsCount){
							$scope.paginator.init(postsCount, $scope.postsPerPage);
							var offset = $scope.paginator.postsPerPage*($scope.paginator.curPageId - 1);
							var limit = $scope.paginator.postsPerPage;
							getPosts(offset, limit);
							getMembers();
						});
					}
						
				}
			})
		}
	}
	
	$scope.editPost = function(post)
	{
		if ((!$scope.isEditable(post)) || ($scope.editingPost != null)) return;
		console.log(post);
		$scope.editingPost = post;
		messageContainer.val(post.message);
	}
	
	Date.minutesBetween = function( date1, date2 ) {
		  var one_minute=1000*60;
		  var date1_ms = date1.getTime();
		  var date2_ms = date2.getTime();
		  var difference_ms = date2_ms - date1_ms;
		  
		  return Math.ceil(difference_ms/one_minute); 
	}
	
	Date.milisecondsBetween = function( date1, date2 ) {
		  var date1_ms = date1.getTime();
		  var date2_ms = date2.getTime();
		  var difference_ms = date2_ms - date1_ms;
		  return difference_ms; 
	}
	
	Date.inMyString = function(date)
	{
		return date.getFullYear()+"-"+date.getMonth()+1+"-"+date.getDate()+" "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
	}
	
	$scope.isEditable = function(post)
	{
		var postedTime = new Date(Date.parse(post.posted.date));
		var now = new Date();
		var utc = new Date(now.getTime() + now.getTimezoneOffset() * 60000);
		var minutesAgo = Date.minutesBetween(postedTime, utc);
		return (minutesAgo <= 5 && post.userid == $scope.userid);
	}
	
	$scope.sendPost = function()
	{
		var msg = $scope.message;
		var symb_index = msg.indexOf("@");
		var tmp_str = msg.substring(symb_index);
		var space_index = tmp_str.indexOf(" ");
		var user_to_send_name = "";
		if(space_index != -1){
			user_to_send_name = tmp_str.substring('1',space_index);
		}else{
			user_to_send_name = tmp_str.substring('1');
		}
		var post = {
				entityid: $scope.entityid, 
				userid: $scope.userid,
				message: $scope.message,
				posted: new Date(),
				usertosendname: user_to_send_name
		}
		///////////
		if(symb_index != -1){
			$http({
				method: "POST", 
				url: $scope.urlSendPrivateMsg, 
				data: post })
			.success(function(response2){
				console.log("Private notification was created!");
			})
		}
		///////////
		
		if ($scope.editingPost)
			post.postid = $scope.editingPost.id;
				
		$http({
			method: "POST", 
			url: $scope.postAddURL, 
			data: post })
		.success(function(response){
			if (response.result)
			{
				// maybe need to request for posts and init paginator!!!
				if ($scope.editingPost == null)
				{
					$scope.posts.push(response.result);
					container.animate({ scrollTop: container.height()+1900 },1000);
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
			$scope.message = "";
			messageContainer.val("");
			messageContainer.focus();
			getMembers();
		})
	}
	
	$scope.changePostsPerPage = function(){
		getPostsCount(function(postsCount){
			$scope.paginator.init(postsCount, $scope.postsPerPage);
			var offset = $scope.paginator.postsPerPage*($scope.paginator.curPageId - 1);
			var limit = $scope.paginator.postsPerPage;
			getPosts(offset, limit);
		});
	}
	
	function startChat()
	{
		getMembers();
		getPostsCount(function(postsCount){
			$scope.paginator.init(postsCount, $scope.postsPerPage);
			
		});
		setInterval(getNewPosts, 3000);
	}
	
	startChat();
	
	$scope.addDocuments = function()
	{
		$http({
			method: "GET", 
			url: $scope.urlsDocumentsAdd
			  })
		.success(function(response){
			var modalInstance = $modal.open({
			      template: response,
			      controller: 'AddDocumentsController'
			    });
			
			modalInstance.result.then(function (addedDocuments) {
				insertDocumentsLinks(addedDocuments);
			}, function(){});
		})
	}
	
	function insertDocumentsLinks(documents)
	{
		_.map(documents, function(d){
			$scope.message += '\n<a href="'+$scope.urlsBase+d.url+'" download="'+d.name+'">'+d.name+'</a>';
		});
	}
}])
.controller('AddDocumentsController', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance){
	console.log('AddDocumentsController was loaded!');
	
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
	        	
	            getDocuments_();
	        }
	    });
		    
	},500);

	$scope.documents = [];
	$scope.urlsDocumentsGet = JSON_URLS.documentsGet;
	
	function prepareDocuments(documents)
	{
		return _.map(documents, function(d){d.checked = false; return d;});
	}
	
	function getDocuments_()
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
				setTimeout(bindList, 1000);
				$scope.documents[0].checked = true;
			}
		})
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
			if (response.result)	
			{
				$scope.documents = prepareDocuments(response.result);
				setTimeout(bindList, 500);
			}
		})
	}
	
	getDocuments();
	
	$scope.addDocuments = function()
	{
		$modalInstance.close(_.filter($scope.documents, function(d){return d.checked;}));
	}
	
	$scope.checkItem = function(documentid)
	{
		_.map($scope.documents, function(d){
			if (d.id == documentid) d.checked = !d.checked;
		});
	}
	
}]);