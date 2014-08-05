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
	
	var personalPageChat = function($http, $scope, $paginator)
	{
		this.entityid = $scope.entityid;
		this.userid = window.USER.id;
		this.scope = $scope;
		this.http = $http;
		this.container = $('#conversation');
		this.messageContainer = $('#write-message');	
		this.members = $scope.members;
		this.posts = $scope.posts;
		this.paginator = $paginator;
	};
	
	personalPageChat.prototype.pressEnter = function(e)
	{
		if ((e.shiftKey == false) && ( e.keyCode == 13 ))
		{
			e.preventDefault();
			this.sendPost();
		}
	}
	
	personalPageChat.prototype.sendPost = function()
	{
		var post = {
				entityid: this.scope.entityid, 
				userid: this.scope.userid,
				message: this.scope.message,
				posted: new Date()
		}
		if (personalPageChat.scope.editingPost)
			post.postid = this.scope.editingPost.id;
		this.http({
			method: "POST", 
			url: this.scope.postAddURL, 
			data: post })
		.success(function(response){
			console.log("Created post: ", response.result);
			if (response.result)
			{
				// maybe need to request for posts and init paginator!!!
				if (personalPageChat.scope.editingPost == null)
				{
					this.scope.posts.push(response.result);
					this.container.animate({ scrollTop: this.container.height()+1900 },1000);
				}
				else
				{
					_.map(this.scope.posts, function(p, i){
						if (p.id == response.result.id)
							this.scope.posts[i] = response.result;
					});
				}
			}
			this.scope.editingPost = null;
			this.scope.message = "";
			messageContainer.val("");
			messageContainer.focus();
			this.getMembers();
		})
	}
	
	personalPageChat.prototype.updatePosts = function(posts)
	{
		var editedMessages = _.filter(posts, function(p){return p.edited;});
		if (editedMessages.length == posts.length)
		{
			_.map(posts, function(post){
				_.map(this.scope.posts, function(p, i){
					if (p.id == post.id)
						this.scope.posts[i] = post;
				});
			});
			return true;
		}
		return false;
	}
	
	personalPageChat.prototype.getPosts = function (offset, limit)
	{
		this.http({
			method: "GET", 
			url: this.scope.postsGetURL, 
			params: {offset: offset, limit: limit}})
		.success(function(response){
			console.log("posts: ",response.result);
			if (response.result)
			{
				this.posts = response.result.reverse();
			}
			if (response.result.length>0)
			{
				this.lastDate = (_.last(this.posts)).posted.date;
			}
			this.container.animate({ scrollTop: this.container.height()+10 },1000);
		})
	}
	
	personalPageChat.prototype.getPostsCount = function (callback)
	{
		this.http({
			method: "GET", 
			url: this.scope.postsCountURL, 
			})
		.success(function(response){
			console.log("posts count: ", response.result);
			if (response.result)
				callback(response.result);
		})
	}
	
	personalPageChat.prototype.getMembers = function ()
	{
		this.http({
			method: "GET", 
			url: this.scope.membersURL, 
			})
		.success(function(response){
			console.log("members: ", response.result);
			if (response.result)
				this.members = response.result;
		})
	}

	personalPageChat.prototype.getNewPosts = function ()
	{
		if (this.scope.paginator.curPageId == 1)
		{setInterval(this.getNewPosts, 3000);
		this.http({
				method: "GET", 
				url: this.scope.postsNewURL, 
				params: {last_posted: this.scope.lastDate}})
			.success(function(response){
				if ((response.result) && (response.result.length > 0))
				{	
					var onlyUpdated = this.updatePosts(response.result);
					if (onlyUpdated == false)
					{
						personalPageChat.getPostsCount(function(postsCount){
							this.scope.paginator.init(postsCount, this.scope.postsPerPage);
							var offset = this.scope.paginator.postsPerPage*(this.scope.paginator.curPageId - 1);
							var limit = this.scope.paginator.postsPerPage;
							this.getPosts(offset, limit);
							this.getMembers();
						});
					}
				}
			})
		}
	}
	
	personalPageChat.prototype.isEditable = function(post)
	{
		var postedTime = new Date(Date.parse(post.posted.date));
		var now = new Date();
		var utc = new Date(now.getTime() + now.getTimezoneOffset() * 60000);
		
		var minutesAgo = Date.minutesBetween(postedTime, utc);

		return (minutesAgo <= 5 && post.userid == $scope.userid);
	}
	
	personalPageChat.prototype.changePostsPerPage = function(){
		getPostsCount(function(postsCount){
			this.scope.paginator.init(postsCount, this.scope.postsPerPage);
			var offset = this.scope.paginator.postsPerPage*(this.scope.paginator.curPageId - 1);
			var limit = this.scope.paginator.postsPerPage;
			this.getPosts(offset, limit);
		});
	}
	
	personalPageChat.prototype.editPost = function(post)
	{
		if ((!this.isEditable(post)) || (this.editingPost != null)) return;
		console.log(post);
		this.scope.editingPost = post;
		this.messageContainer.val(post.message);
	}
	
	/*personalPageChat.init = function($http, $scope)
	{
		personalPageChat.entityid = $scope.entityid;
		personalPageChat.userid = window.USER.id;
		personalPageChat.scope = $scope;
		personalPageChat.http = $http;
		personalPageChat.container = $('#conversation');
		personalPageChat.messageContainer = $('#write-message');
		personalPageChat.id++;
		console.log('Personal factory # ',this.id,'---> ',this);
		return this;
	}*/
	
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
	
	personalPageChat.prototype.startChat = function()
	{
		this.getMembers();
		this.getPostsCount(function(postsCount){
			debugger
			this.paginator.init(postsCount, this.scope.postsPerPage);
		});
		console.log('personalpagechat ----> ', this)
		setInterval(personalPageChat.getNewPosts, 3000);
	}

	return personalPageChat;
})

console.log('Angular core is loaded...');