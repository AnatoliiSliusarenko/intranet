Intranet.controller('NavigationController', ['$scope', '$http', function($scope, $http){
	console.log('NavigationController was loaded!');
	title = $('title');
	titleValue = $('title').text();
	notificationsGetUrl = JSON_URLS.notificationsGet;
	officeShowUrlBase = JSON_URLS.officeShow;
	topicShowUrlBase = JSON_URLS.topicShow;
	disableOnSite = "/getusersettings/";
	
	$scope.notifications = [];
	$scope.notifyHandler = null;
	
	function Notify()
	{
		title.text((title.text() == titleValue) ? 'Incoming notifications!' : titleValue);
	}
	
	function StartNotify()
	{
		$scope.notifyHandler = setInterval(Notify, 1000);
	}
	
	function StopNotify()
	{
		clearInterval($scope.notifyHandler);
		title.text(titleValue);
		$scope.notifyHandler = null;
	}
	
	function getNotifications()
	{
		$http({
			method: "GET", 
			url: notificationsGetUrl
			  })
		.success(function(response){
			
			$http({
				method: "GET", 
				url: disableOnSite
				  })
			.success(function(response2){
				//console.log("disable_message_on_site->"+response2.result.disable_message_on_site);
				if(!response2.result.disable_message_on_site){
					if (response.result.length > 0)
					{
						if ($scope.notifyHandler == null) StartNotify();
						$scope.notifications = prepareNotifications(response.result);
					}else
					{
						StopNotify();
						$scope.notifications = [];
					}
				}
			})
			
			
		})
	}
	
	function getDisableOnSite(){
		$http({
			method: "GET", 
			url: disableOnSite
			  })
		.success(function(response){
			console.log("disable_message_on_site->"+response.result.disable_message_on_site);
			return response.result.disable_message_on_site;
		})
	}
	
	function prepareNotifications(notifications)
	{
		notifications = _.map(notifications, function(n){
			if (['task_comment', 'task_assigned', 'membership_own', 'membership_own_out', 'membership_user', 'membership_user_out', 'message_office', 'removed_office'].indexOf(n.type) != -1)
			{
				n.href = officeShowUrlBase.replace('0', n.destinationid);
			}else
			{
				n.href = topicShowUrlBase.replace('0', n.destinationid);
			}
			
			return n;
		});
		
		TASKS_NOTIFICATIONS =_.map(notifications,function(n){
			if(n.type=='task_comment')
				return n;
		});
		notifications = _.filter(notifications, function(n){
			return n.href != window.location.href;
		});
		
		$http({
			method: "GET", 
			url: window.location.href
			  });
		
		return notifications;
		
	}
	
	setInterval(getNotifications, 2000);
	
}]);