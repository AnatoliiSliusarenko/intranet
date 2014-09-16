Intranet.controller('TabController', function (){
    console.log("TabController was loaded!");
	
    this.tab = 1;
    
    this.selectTab = function (setTab){
        this.tab = setTab;
    };
    
    this.isSelected = function(checkTab) {
        return this.tab === checkTab;
    };

  });