var Intranet = angular.module('Intranet', ['ui.bootstrap'])
.config(['$interpolateProvider', function ($interpolateProvider) {
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
    	
    	if(this.countPages == 0) this.countPages++;
    	
    	this.pages = _.range(1, this.countPages + 1);
    	
    	return this;
    }
	
	return paginator;
 })
 .filter('estimatedTime', function(){
	 return function(minutes){
		 return Math.floor(minutes/60) + ' h ' + minutes%60 + ' m';
	 }
 })

.factory('$personalPageChat', function() {
	personalPageChat = {};
	personalPageChat.ui = 0;
	
	personalPageChat.updatePosts = function(posts)
	{
		var editedMessages = _.filter(posts, function(p){return p.edited;});
		if (editedMessages.length == posts.length)
		{
			_.map(posts, function(post){
				_.map(personalPageChat.scope.posts, function(p, i){
					if (p.id == post.id)
						personalPageChat.scope.posts[i] = post;
				});
			});
			return true;
		}
		
		return false;
	}
	
	personalPageChat.getPosts = function (offset, limit)
	{
		personalPageChat.http({
			method: "GET", 
			url: personalPageChat.scope.postsGetURL, 
			params: {offset: offset, limit: limit}})
		.success(function(response){
			console.log("posts: ",response.result);
			if (response.result)
			{
				personalPageChat.scope.posts = response.result.reverse();
			}
			if (response.result.length>0)
			{
				personalPageChat.scope.lastDate = (_.last(personalPageChat.scope.posts)).posted.date;
			}
			personalPageChat.container.animate({ scrollTop: container.height()+1900 },1000);
		})
	}
	
	personalPageChat.getPostsCount = function (callback)
	{
		personalPageChat.http({
			method: "GET", 
			url: personalPageChat.scope.postsCountURL, 
			})
		.success(function(response){
			console.log("posts count: ", response.result);
			if (response.result)
				callback(response.result);
		})
	}
	
	personalPageChat.getMembers = function ()
	{
		personalPageChat.http({
			method: "GET", 
			url: personalPageChat.scope.membersURL, 
			})
		.success(function(response){
			console.log("members: ", response.result);
			if (response.result)
				personalPageChat.scope.members = response.result;
		})
	}

	personalPageChat.getNewPosts = function ()
	{
		if (personalPageChat.scope.paginator.curPageId == 1)
		{setInterval(personalPageChat.getNewPosts, 3000);
		personalPageChat.http({
				method: "GET", 
				url: personalPageChat.scope.postsNewURL, 
				params: {last_posted: personalPageChat.scope.lastDate}})
			.success(function(response){
				console.log("new posts: ", response.result);
				if ((response.result) && (response.result.length > 0))
				{	
					var onlyUpdated = personalPageChat.updatePosts(response.result);
					if (onlyUpdated == false)
					{
						personalPageChat.getPostsCount(function(postsCount){
							personalPageChat.scope.paginator.init(postsCount, personalPageChat.scope.postsPerPage);
							var offset = personalPageChat.scope.paginator.postsPerPage*(personalPageChat.scope.paginator.curPageId - 1);
							var limit = personalPageChat.scope.paginator.postsPerPage;
							personalPageChat.getPosts(offset, limit);
							personalPageChat.getMembers();
						});
					}
						
				}
			})
		}
	}
	
	personalPageChat.init = function($http, $scope)
	{
		personalPageChat.entityid = window.ENTITY.id;
		personalPageChat.userid = window.USER.id;
		personalPageChat.scope = $scope;
		personalPageChat.http = $http;
		personalPageChat.container = $('#conversation');
		personalPageChat.messageContainer = $('#write-message');
		
		console.log('------> init ', this);
		personalPageChat.ui++;
		return this;
		
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
	
	personalPageChat.startChat = function()
	{
		personalPageChat.getMembers();
		personalPageChat.getPostsCount(function(postsCount){
			personalPageChat.scope.paginator.init(postsCount, personalPageChat.scope.postsPerPage);
			
		});
		console.log('==========');
		setInterval(personalPageChat.getNewPosts, 3000);
	}

	return personalPageChat;
})

console.log('Angular core is loaded...');