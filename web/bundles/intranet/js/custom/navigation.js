Intranet.controller('NavigationController', ['$scope', '$http', function($scope, $http){
	console.log('NavigationController was loaded!');
	title = $('title');
	titleValue = $('title').text();
	notificationsGetUrl = JSON_URLS.notificationsGet;
	officeShowUrlBase = JSON_URLS.officeShow;
	topicShowUrlBase = JSON_URLS.topicShow;
	userSettingsUrl = JSON_URLS.userSettings;
	
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
				url: userSettingsUrl
				  })
			.success(function(response2){
					//---main
					if (response.result.length > 0)
					{
						var notification_to_show = true;
						for(i = 0; i < response.result.length; i++){
							switch (response.result[i].type) {
							case 'message_office' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_message_office;
								break;
							case 'message_topic' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_message_topic;
								break;
							case 'membership_own' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_membership_own;
								break;
							case 'membership_user' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_membership_user;
								break;
							case 'removed_office' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_removed_office;
								break;
							case 'removed_topic' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_removed_topic;
								break;
							case 'topic_added' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_topic_added;
								break;
							case 'membership_own_out' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_membership_own_out;
								break;
							case 'membership_user_out' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_membership_user_out;
								break;
							case 'task_assigned' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_task_assigned;
								break;
							case 'task_comment' :
								notification_to_show = response2.result.user_settings_notifications.msg_site_task_comment;
								break;
							default:
								notification_to_show = true;
								break;
							}
							//console.log("notification_to_show["+i+"] -> "+notification_to_show);
							if(notification_to_show == false){
								response.result.splice(i,1);
								i--;
							}
						}
						//console.log("response_result -> "+response.result);
						if(!response2.result.user_settings.disable_message_on_site){
							if(response.result.length > 0){
								if ($scope.notifyHandler == null) StartNotify();
								$scope.notifications = prepareNotifications(response.result);
							}else{
								StopNotify();
								$scope.notifications = [];
							}
						}else{
							var notification_to_show2 = true;
							for(i = 0; i < response.result.length; i++){
								switch (response.result[i].type) {
								case 'private_message_office' :
									notification_to_show2 = true;
									break;
								case 'private_message_topic' :
									notification_to_show2 = true;
									break;
								case 'private_message_task' :
									notification_to_show2 = true;
									break;
								default:
									notification_to_show2 = false;
									break;
								}
								//console.log("notification_to_show["+i+"] -> "+notification_to_show);
								if(notification_to_show2 == false){
									response.result.splice(i,1);
									i--;
								}
							}
							if(response.result.length > 0){
								if ($scope.notifyHandler == null) StartNotify();
								$scope.notifications = prepareNotifications(response.result);
							}else{
								StopNotify();
								$scope.notifications = [];
							}
						}
					}else
					{
						StopNotify();
						$scope.notifications = [];
					}
					//---end main
			})
			
			
		})
	}
	
	function prepareNotifications(notifications)
	{
		notifications = _.map(notifications, function(n){
			if (['private_message_office', 'task_comment', 'task_assigned', 'membership_own', 'membership_own_out', 'membership_user', 'membership_user_out', 'message_office', 'removed_office'].indexOf(n.type) != -1)
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
	
	$(".write-message").keyup(function(){
		var msg = $(this).val();
		var symb_index = msg.indexOf("@");
		if(symb_index != -1){
			var tmp_str = msg.substring(symb_index);
			var space_index = tmp_str.indexOf(" ");
			var user_to_send_name = "";
			if(space_index != -1){
				user_to_send_name = tmp_str.substring('0',space_index);
			}else{
				user_to_send_name = tmp_str.substring('1');
			}
			//alert(user_to_light_name);
		}
	});
	
}]);