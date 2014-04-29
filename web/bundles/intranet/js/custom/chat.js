Intranet.controller('ChatController', ['$scope', '$http', '$paginator', function($scope, $http, $paginator){
	container = $('#conversation');
	messageContainer = $('#write-message');
	
	$scope.paginator = $paginator;
	$scope.postsPerPage = 10;
	$scope.paginator.postsPerPage = $scope.postsPerPage;
	
	$scope.$watch('paginator.curPageId', function(){
		var offset = $scope.paginator.postsPerPage*($scope.paginator.curPageId - 1);
		var limit = $scope.paginator.postsPerPage;
		getPosts(offset, limit);
		console.log("curPageId: paginator--->", $scope.paginator);
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
	
	
	$scope.entityid = window.ENTITY.id;
	$scope.userid = window.USER.id;
	
	function updateLastDate(posts)
	{
		
	}
	
	function getPosts(offset, limit)
	{
		$http({
			method: "GET", 
			url: $scope.postsGetURL, 
			params: {offset: offset, limit: limit}})
		.success(function(response){
			console.log(response.result);
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
			url: $scope.postsCountURL, 
			})
		.success(function(response){
			console.log("posts count: ", response.result);
			if (response.result)
				callback(response.result);
		})
	}
	
	function getMembers()
	{
		$http({
			method: "GET", 
			url: $scope.membersURL, 
			})
		.success(function(response){
			console.log("members: ", response.result);
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
				params: {last_posted: ($scope.posts.length > 0)? (_.last($scope.posts)).posted.date : null}})
			.success(function(response){
				console.log("new posts: ", response.result);
				if ((response.result) && (response.result.length > 0))
				{
					updateLastDate(response.result);
					getPostsCount(function(postsCount){
						$scope.paginator.init(postsCount, $scope.postsPerPage);
						var offset = $scope.paginator.postsPerPage*($scope.paginator.curPageId - 1);
						var limit = $scope.paginator.postsPerPage;
						getPosts(offset, limit);
						getMembers();
					});
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
		  var one=1;
		  var date1_ms = date1.getTime();
		  var date2_ms = date2.getTime();
		  var difference_ms = date2_ms - date1_ms;
		  
		  return Math.ceil(difference_ms/one_minute); 
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
		var post = {
				entityid: $scope.entityid, 
				userid: $scope.userid,
				message: $scope.message,
				posted: new Date()
		}
		
		if ($scope.editingPost)
			post.postid = $scope.editingPost.id;
				
		$http({
			method: "POST", 
			url: $scope.postAddURL, 
			data: post })
		.success(function(response){
			console.log("Created post: ", response.result);
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
	
}]);