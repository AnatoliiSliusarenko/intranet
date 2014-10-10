var Intranet = angular.module('Intranet', ['ui.bootstrap','ngSanitize'])
.config(['$interpolateProvider', '$httpProvider', function ($interpolateProvider, $httpProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
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
.directive('datetime', function(){
	var directive = {};
	directive.restrict = 'E';
	directive.template = "<label class='label' style='background-color: [[color]];'>[[dateTimeString]]</label>";

	directive.link = function (scope, element, attr) {
		scope.color = (attr.datetime > 0) ? 'green' : 'red';
		var sign = (attr.datetime >= 0) ? '' : '- ';
		attr.datetime = Math.abs(attr.datetime);
        scope.dateTimeString = sign + Math.floor(attr.datetime/(60*60)) + ' h ' + Math.floor(attr.datetime%(60*60)/60) + ' m ' + attr.datetime%60 + ' s';
    }

	return directive;
})

console.log('Angular core is loaded...');