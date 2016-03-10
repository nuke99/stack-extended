/**
 * Created by treasure on 3/8/16.
 */
app.controller('hostsController', ['$scope','$http', function($scope,$http){
    $scope.hosts = [];
    $scope.host = {};

    hosts_load();

    function hosts_load(){
        $http({
            url:'service.php?action=virtual_hosts',
            method:'GET',
        }).then(function successCallback(response){
                if(response.data.status == true){
                    console.log(response.data.output);
                    $scope.hosts = response.data.output;
                    $scope.host = $scope.hosts[0];
                }
        }, function errorCallback(response){

        });
    }
    
    
    $scope.select_host = function (index) {
        $scope.host = $scope.hosts[index];
        console.log($scope.host);
    }


}]);