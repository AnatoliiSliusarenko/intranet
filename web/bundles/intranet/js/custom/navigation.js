Intranet.controller('NavigationController', ['$scope', '$http', function($scope, $http){
	console.log('NavigationController was loaded!');
	title = $('title');
	titleValue = $('title').text();
	notificationsGetUrl = JSON_URLS.notificationsGet;
	
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
			if (response.result.length > 0)
			{
				if ($scope.notifyHandler == null) StartNotify();
				$scope.notifications = response.result;
			}else
			{
				StopNotify();
				$scope.notifications = [];
			}
		})
	}
	
	$scope.goToDestination = function(event, destinationId)
	{
		event.preventDefault();
		var url = event.currentTarget.href.replace('0', destinationId);
		window.location.href = url;
	}
	
	setInterval(getNotifications, 2000);
	
}]);