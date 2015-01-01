Intranet.controller('SprintController', ['$scope', '$http', '$modal', function($scope, $http, $modal) {
    console.log('SprintController was loaded!');
    $scope.urlAddSprint = JSON_URLS.addSprint;
    $scope.urlAddTaskToSprint = JSON_URLS.addTaskToSprint;
    $scope.urlChangeStatus = JSON_URLS.changeStatus;
    $scope.urlShowSprint = JSON_URLS.showSprint;

    $scope.addSprint = function()
    {
        $http({
            method: "GET",
            url: $scope.urlAddSprint
        })
            .success(function(response) {
                var modalInstance = $modal.open({
                    template: response,
                    controller: 'AddSprintController'
                })
            })
    }

    $scope.addToSprint = function(taskid)
    {
        var url = $scope.urlAddTaskToSprint.replace('0', taskid);
        $http({
            method: "GET",
            url: url
        })
            .success(function(response) {
                var modalInstance = $modal.open({
                    template: response,
                    controller: 'AddTaskToSprintController'
                })
            })
    }

    $scope.changeStatus = function(sprintid)
    {
        var url = $scope.urlChangeStatus.replace('0', sprintid);
        $http({
            method: "GET",
            url: url
        })
            .success(function(response) {
                if(response.message)
                {
                    var url = $scope.urlShowSprint.replace('0', sprintid);
                    document.location.href = url;
                }
            })
    }
}])
Intranet.controller('AddSprintController', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance) {
    console.log('AddSprintController was loaded!');
    $scope.urlAddSprint = JSON_URLS.addSprint;
    $scope.urlShowSprint = JSON_URLS.showSprint;
    $scope.sprint = {};
    $scope.addSprint = function(event)
    {
        if ($scope.sprint.name != undefined && $scope.sprint.description != undefined){
            event.preventDefault();
        }
        $http({
            method: "POST",
            url: $scope.urlAddSprint,
            data: $scope.sprint
        })
            .success(function(response){
                if (response.result)
                    $modalInstance.close(response.result);
                var id = response.result.id;
                var url = $scope.urlShowSprint.replace('0', id)
                document.location.href = url;
            })
    }
}])
Intranet.controller('AddTaskToSprintController', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance) {
    console.log('AddTaskToSprintController was loaded!');
    $scope.urlAddTaskToSprint = JSON_URLS.addTaskToSprint;
    $scope.urlShowSprint = JSON_URLS.showSprint;
    $scope.data = {};
    $scope.addTaskToSprint = function(event, taskid)
    {
        if ($scope.data.sprintid != undefined){
            event.preventDefault();
        }
        var url = $scope.urlAddTaskToSprint.replace('0', taskid);
        $http({
            method: "POST",
            url: url,
            data: $scope.data
        })
            .success(function(response){
                if (response.result)
                {
                    $modalInstance.close(response.result);
                    var id = response.result.id;
                    var url = $scope.urlShowSprint.replace('0', id)
                    document.location.href = url;
                }
                else
                {
                    alert(response.message);
                    $modalInstance.close(response);
                }

            })
    }

}]);