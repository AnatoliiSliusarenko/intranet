Intranet.controller('DocumentsController', ['$scope', '$http', '$modal', function($scope, $http, $modal){
	console.log('DocumentsController was loaded!');
	
	$scope.documents = [];
	
	$scope.urlsDocumentsGet = JSON_URLS.documentsGet;
	
	$(function() {
	    $('#file_upload').uploadify({
	    	'fileSizeLimit': 0,
	    	'progressData' : 'speed',
	    	'formData'     : {
				'timestamp' : TIMESTAMP,
				'token'     : TOKEN
			},
	    	'buttonText' : 'Upload files...',
	        'swf'      : JSON_URLS.uploaderSWF,
	        'uploader' : JSON_URLS.uploaderUpload,
	        'onUploadSuccess' : function(file, data, response) {
	            console.log('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
	            getDocuments();
	        }
	    });
	});
	
	function getDocuments()
	{	
		$http({
			method: "POST", 
			url: $scope.urlsDocumentsGet
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.documents = response.result;
			}
		})
	}
	
	getDocuments();
	
}]);
