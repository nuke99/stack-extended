/**
 * Created by treasure on 3/8/16.
 */
app.controller('hostsController', ['$rootScope','$scope','$http','ngProgressFactory', function($rootScope,$scope,$http,ngProgressFactory){
    $scope.hosts = [];
    $scope.host = {};
    $scope.is_new_host = false;

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
    
    $scope.vhostProgress = ngProgressFactory.createInstance();
    $scope.vhostProgress.setParent(document.getElementById('vhost_selected'));
    $scope.vhostProgress.setAbsolute();
    $scope.vhostProgress.setColor('#16a085');
    $scope.select_host = function (index) {
        $scope.is_new_host = false;
        $scope.vhostProgress.start();

        $scope.vhostProgress.complete();
        $scope.host = $scope.hosts[index];
        console.log($scope.host);
    }

    //Add New Virtual Host
    $scope.add = function(){
        $scope.host={
            'ip':"*",
            'port':"80"
        };
        $scope.is_new_host = true;
    }


    /**
     * New Virtual Host Handle
     * -----------------------------
     * */

    $scope.isAliasManual = false;
    $scope.isAdminManual = false;
    $scope.isErrorLogManual = false;
    $scope.isCustomErrorLogsManual = false;


    // Server Name
    $scope.serverNameChange = function(oldValue){
        console.log(oldValue);
        if($scope.is_new_host == true){
            if($scope.isAliasManual == false){
                $scope.host.ServerAlias = "www."+$scope.host.ServerName;
            }else{
                console.log('changing old value')
                $scope.host.ServerAlias = $scope.host.ServerAlias.replace(oldValue,$scope.host.ServerName);
            }

            if($scope.isAdminManual == false){
                console.log('changing new value'+$scope.isAdminManual)
                $scope.host.ServerAdmin = 'admin@'+$scope.host.ServerName;
            }

            if($scope.isErrorLogManual == false){
                $scope.host.ErrorLog = 'logs/'+$scope.host.ServerAlias+'-error.log';
            }

            if($scope.isCustomErrorLogsManual == false){
                $scope.host.CustomLog = 'logs/'+$scope.host.ServerAlias+'-access.log common';
            }

        }
    }
    // Alias
    $scope.AliasChange = function(){
        if($scope.is_new_host == true){
            $scope.isAliasManual = true;
        }
    }
    $scope.serverAdminChange = function(){
        if($scope.is_new_host == true ){
            $scope.isAliasManual = true;
        }
    }
    //$scope.serverNameChange = function(){
    //    if($scope.is_new_host == true){
    //
    //    }
    //}
    //$scope.serverNameChange = function(){
    //    if($scope.is_new_host == true){
    //
    //    }
    //}




    /**
     *
     * Create new Virtual Host
     */
    $scope.addVirtualHost = function(){

        $http({
            url:'service.php?action=create_virtual_host',
            method:'POST',
            data:$scope.host,
        }).then(function successCallback(response){
            if(response.data.status == false){
                $scope.host.error = response.data.output;
            }
        }, function errorCallback(response){

        });
    }
}]);


app.run(function($rootScope){
    $rootScope.$on('terminal.main',function(e, input,terminal){
        $rootScope.$emit('terminal.main.echo', 'input received: ' + input)
        console.log(input);
    });
});