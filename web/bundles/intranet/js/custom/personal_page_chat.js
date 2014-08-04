
Intranet.controller('PersonalChatController',['$http', '$scope', '$personalPageChat', '$paginator', function($http, $scope, $personalPageChat, $paginator) {
	
	$scope.paginator = $paginator;
	
	$scope.postsPerPage = 10;
	$scope.paginator.postsPerPage = $scope.postsPerPage;
	$scope.posts = [];
	$scope.members = [];
	$scope.message = '';
	$scope.editingPost = null;
	$scope.lastDate = null;	
		
	$scope.postsGetURL = JSON_URLS_FOR_PERSONAL_PAGE.posts;
	$scope.avatarURL = JSON_URLS_FOR_PERSONAL_PAGE.avatar;
	$scope.postAddURL = JSON_URLS_FOR_PERSONAL_PAGE.post_add;
	$scope.membersURL = JSON_URLS_FOR_PERSONAL_PAGE.members;
	$scope.postsNewURL = JSON_URLS_FOR_PERSONAL_PAGE.posts_new;
	$scope.postsCountURL = JSON_URLS_FOR_PERSONAL_PAGE.post_count;	
	
	$scope.entityid = window.ENTITY.id;
	$scope.userid = window.USER.id;
		$personalPageChat.init($http, $scope);
		//debugger
		$personalPageChat.startChat();
		console.log("curPageId: paginator--->", $scope.paginator);
	
	console.log('=========>>>> PersonalController was loaded');

  }]);