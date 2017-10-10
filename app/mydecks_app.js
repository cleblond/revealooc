var app = angular.module('myApp', ['ui.bootstrap', 'ngSanitize']);


app.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});


app.filter('iif', function () {
   return function(input, trueValue, falseValue) {
        return input ? trueValue : falseValue;
   };
});




app.controller('questionsCrtl', function ($scope, $http, $timeout) {
        //$scope.catid = {};
        //$scope.myonly = {};
        $scope.userid = {};
        $scope.myonly = $('#myonly').val();
        //$scope.data = Service.value;
        
        //console.log($scope.catid);
        //alert($('#catid').val());
        $scope.loading = true;
    var request = $http.post('ajax/getslidedecks.php', {myonly: $('#myonly').val(), userid: $('#userid').val()}).success(function(data){
        //alert($('#catid').val());
        //alert(angular.element(document.getElementById('catid')));
        
        $scope.list = data;
        console.log(data);
        //console.log($('#searchcat').val());
        //console.log($scope.catid);
        //$scope.catid = 1;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 10; //max no of items to display in a page
        //$scope.searchcat = $scope.catid; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter  
        $scope.totalItems = $scope.list.length;
        //Scopes.store('questionsCrtl', $scope);
        //console.log($scope.list);
        //alert('HERE2');
    })
    
    .finally(function () {
    $scope.loading = false;
    });

    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };


    $scope.confirmDelete = function($event){
    
           if(!confirm("Are you sure you want to delete this activity?")) {
                $event.preventDefault();
                return false;
           }
           return true;    
    };


});






