angular.module('Intranet', [])
.config(['$interpolateProvider', '$httpProvider', function ($interpolateProvider, $httpProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
}])
.factory('$paginator', function() {
    var paginator = {};
    
    paginator.countPages = 5;
    paginator.curPageId = 1;
    paginator.pages = [];
    paginator.postsPerPage = 5;
    paginator.postsPerPageValues = [5, 10, 20, 50, 100];
    paginator.countPosts = 100;
    
    paginator.nextPage = function(event){
    	event.preventDefault();
    	if (this.curPageId < this.countPages)
    		this.curPageId++;
    }
    
    paginator.prevPage = function(event){
    	event.preventDefault();
    	if (this.curPageId > 1)
    		this.curPageId--;
    }
    
    paginator.toPage = function(event, id){
    	event.preventDefault();
    	if ((id > 0) && (id <= this.countPages))
    		this.curPageId = id;
    }
    
    paginator.firstPage = function(event){
    	event.preventDefault();
    	this.curPageId = 1;
    }
    
    paginator.lastPage = function(event){
    	event.preventDefault();
    	this.curPageId = this.countPages;
    }
    
    paginator.init = function(postsCount, postsPerPage){
    	this.countPosts = postsCount;
    	this.postsPerPage = postsPerPage
    	this.countPages = Math.ceil(postsCount/postsPerPage);
    	this.curPageId = 1;
    	
    	this.pages = _.range(1, this.countPages + 1);
    	
    	return this;
    }
	
	return paginator;
 })
.controller('ChatController', ['$scope', '$http', '$paginator', function($scope, $http, $paginator){
	container = $('.conversation');
	messageContainer = $('.write-message');
	
	$scope.paginator = $paginator;
	$scope.postsPerPage = 10;
	
	$scope.pressEnter = function(e)
	{
		var event = e || window.event;
		var charCode = event.which || event.keyCode;

		if ( charCode == '13' ) 
		{
			$scope.sendPost();
		}
	}
	
	$scope.posts = [];
	$scope.members = [];
	$scope.message = '';
		
	$scope.postsGetURL = JSON_URLS.posts;
	$scope.avatarURL = JSON_URLS.avatar;
	$scope.postAddURL = JSON_URLS.post_add;
	$scope.topicMembersURL = JSON_URLS.topic_members;
	$scope.postsNewURL = JSON_URLS.posts_new;
	$scope.postsCountURL = JSON_URLS.post_count;
	
	
	$scope.topicid = window.TOPIC.id;
	$scope.userid = window.USER.id;
	
	function getPosts()
	{
		$http({
			method: "GET", 
			url: $scope.postsGetURL, 
			params: {offset:'0', limit:'25'}})
		.success(function(response){
			console.log(response.result);
			if (response.result)
				$scope.posts = response.result;
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
			console.log("posts count-->", response.result);
			if (response.result)
				callback(response.result);
		})
	}
	
	function getTopicMembers()
	{
		$http({
			method: "GET", 
			url: $scope.topicMembersURL, 
			})
		.success(function(response){
			console.log("members-->", response.result);
			if (response.result)
				$scope.members = response.result;
		})
	}
	
	function getNewPosts()
	{
		$http({
			method: "GET", 
			url: $scope.postsNewURL, 
			params: {last_posted: ($scope.posts.length > 0)? (_.last($scope.posts)).posted.date : null}})
		.success(function(response){
			console.log("new posts-->", response.result);
			if ((response.result) && (response.result.length > 0))
			{
				$scope.posts = $scope.posts.concat(response.result);
				container.animate({ scrollTop: container.height()+1900 },1000);
				getTopicMembers();
				$scope.paginator.init($scope.posts.length, $scope.postsPerPage);
			}
		})
	}
	
	$scope.sendPost = function()
	{
		var post = {
				topicid: $scope.topicid, 
				userid: $scope.userid,
				message: $scope.message,
				posted: new Date()
		}
		
		$http({
			method: "POST", 
			url: $scope.postAddURL, 
			data: post })
		.success(function(response){
			console.log(response.result);
			if (response.result)
			{
				$scope.posts.push(response.result);
				$scope.message = '';
				messageContainer.focus();
				container.animate({ scrollTop: container.height()+1900 },1000);
			}
		})
	}
	
	$scope.changePostsPerPage = function(){
		$scope.paginator.init($scope.posts.length, $scope.postsPerPage);
	}
	
	function startChat()
	{
		getTopicMembers();
		getPostsCount(function(postsCount){
			$scope.paginator.init(postsCount, $scope.postsPerPage);
			
		});
		
		getPosts();
		setInterval(getNewPosts, 3000);
	}
	
	startChat();
	
}]);