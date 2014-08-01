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
	
	

		
	personalPageChat.pressEnter = function(e)
	{
		if ((e.shiftKey == false) && ( e.keyCode == 13 ))
		{
			e.preventDefault();
			personalPageChat.sendPost();
		}
	}
	
	
	
	function updateLastDate(posts)
	{
		
	}
	
	function getPosts(offset, limit)
	{
		personalPageChat.http({
			method: "GET", 
			url: personalPageChat.postsGetURL, 
			params: {offset: offset, limit: limit}})
		.success(function(response){
			console.log("posts: ",response.result);
			if (response.result)
			{
				personalPageChat.posts = response.result.reverse();
			}
			if (response.result.length>0)
			{
				personalPageChat.lastDate = (_.last(personalPageChat.posts)).posted.date;
			}
			container.animate({ scrollTop: container.height()+1900 },1000);
		})
	}
	
	function getPostsCount(callback)
	{
		personalPageChat.http({
			method: "GET", 
			url: personalPageChat.postsCountURL, 
			})
		.success(function(response){
			console.log("posts count: ", response.result);
			if (response.result)
				callback(response.result);
		})
	}
	
	function getMembers()
	{
		personalPageChat.http({
			method: "GET", 
			url: personalPageChat.membersURL, 
			})
		.success(function(response){
			console.log("members: ", response.result);
			if (response.result)
				personalPageChat.members = response.result;
		})
	}
	

	function getNewPosts()
	{
		if (personalPageChat.paginator.curPageId == 1)
		{setInterval(getNewPosts, 3000);
		personalPageChat.http({
				method: "GET", 
				url: personalPageChat.postsNewURL, 
				params: {last_posted: (personalPageChat.posts.length > 0)? (_.last(personalPageChat.posts)).posted.date : null}})
			.success(function(response){
				console.log("new posts: ", response.result);
				if ((response.result) && (response.result.length > 0))
				{
					updateLastDate(response.result);
					getPostsCount(function(postsCount){
						personalPageChat.paginator.init(postsCount, personalPageChat.postsPerPage);
						var offset = personalPageChat.paginator.postsPerPage*(personalPageChat.paginator.curPageId - 1);
						var limit = personalPageChat.paginator.postsPerPage;
						getPosts(offset, limit);
						getMembers();
					});
				}
			})
		}
	}
	
	personalPageChat.editPost = function(post)
	{
		if ((!personalPageChat.isEditable(post)) || (personalPageChat.editingPost != null)) return;
		console.log(post);
		personalPageChat.editingPost = post;
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
	
	personalPageChat.isEditable = function(post)
	{
		var postedTime = new Date(Date.parse(post.posted.date));
		var now = new Date();
		var utc = new Date(now.getTime() + now.getTimezoneOffset() * 60000);
		
		var minutesAgo = Date.minutesBetween(postedTime, utc);

		return (minutesAgo <= 5 && post.userid == personalPageChat.userid);
	}
	
	personalPageChat.sendPost = function()
	{
		var post = {
				entityid: personalPageChat.entityid, 
				userid: personalPageChat.userid,
				message: personalPageChat.message,
				posted: new Date()
		}
		
		if (personalPageChat.editingPost)
			post.postid = personalPageChat.editingPost.id;
				
		personalPageChat.http({
			method: "POST", 
			url: personalPageChat.postAddURL, 
			data: post })
		.success(function(response){
			console.log("Created post: ", response.result);
			if (response.result)
			{
				// maybe need to request for posts and init paginator!!!
				if (personalPageChat.editingPost == null)
				{
					personalPageChat.posts.push(response.result);
					container.animate({ scrollTop: container.height()+1900 },1000);
				}
				else
				{
					_.map(personalPageChat.posts, function(p, i){
						if (p.id == response.result.id)
							personalPageChat.posts[i] = response.result;
					});
				}
			}
			
			personalPageChat.editingPost = null;
			personalPageChat.message = "";
			messageContainer.val("");
			messageContainer.focus();
			getMembers();
		})
	}
	
	personalPageChat.changePostsPerPage = function(){
		getPostsCount(function(postsCount){
			personalPageChat.paginator.init(postsCount, personalPageChat.postsPerPage);
			var offset = $personalPageChat.paginator.postsPerPage*(personalPageChat.paginator.curPageId - 1);
			var limit = personalPageChat.paginator.postsPerPage;
			getPosts(offset, limit);
		});
	}
	
	function startChat()
	{
		getMembers();
		getPostsCount(function(postsCount){
			personalPageChat.paginator.init(postsCount, personalPageChat.postsPerPage);
			
		});
		console.log('==========');
		setInterval(getNewPosts, 3000);
	}
	
	personalPageChat.init = function($paginator , $http)
	{
		personalPageChat.paginator = $paginator;
		personalPageChat.postsPerPage = 10;
		personalPageChat.paginator.postsPerPage = $personalPageChat.postsPerPage;
		personalPageChat.posts = [];
		personalPageChat.members = [];
		personalPageChat.message = '';
		personalPageChat.editingPost = null;
		personalPageChat.lastDate = null;
		personalPageChat.http = $http;
			
		personalPageChat.postsGetURL = JSON_URLS.posts;
		personalPageChat.avatarURL = JSON_URLS.avatar;
		personalPageChat.postAddURL = JSON_URLS.post_add;
		personalPageChat.membersURL = JSON_URLS.members;
		personalPageChat.postsNewURL = JSON_URLS.posts_new;
		personalPageChat.postsCountURL = JSON_URLS.post_count;	
		
		personalPageChat.entityid = window.ENTITY.id;
		personalPageChat.userid = window.USER.id;
		
		console.log('------> init ', this);
		return this;
	}
	
	startChat();
	

	return personalPageChat;
})

console.log('Angular core is loaded...');