/**
 * Created by treasure on 3/6/16.
 */
app.controller('serviceController',['$scope','$http', function($scope,$http){
    $scope.apache_status = "wait";
    $scope.mysql_status = "wait";

    serviceStatus();

    $scope.apacheAction = function(){
        var action = $scope.apache_status == 'off' ? 'start' : 'stop';
        apacheAction(action, function(){
            serviceStatus();
        }, function(){
            serviceStatus();
        })
    }

    $scope.mysqlAction = function () {
        var action = $scope.mysql_status == 'off' ? 'start' : 'stop';
        mysqlActions(action,function(response){
            serviceStatus();
        }, function(response){
            serviceStatus();
        })
    }



    $scope.startAll = function(){
        //Restart
        if($scope.mysql_status == 'on' || $scope.apache_status == 'on'){
            // MySQL Restart
            mysqlActions('stop',function(){
                mysqlActions('start', function () {
                    serviceStatus();
                })
            },function(){
                mysqlActions('start', function () {
                    serviceStatus();
                })
            });

            // Apache Restart
            apacheAction('restart', function () {
                serviceStatus();
            },function(){
                serviceStatus();
            });


        // Start
        }else{
            mysqlActions('start');
            apacheAction('start');
        }

    }





    // Service Functions -------------------------------

    /**
     * Current Status
     */
    function serviceStatus(){
        $http({
            url:'service.php?action=status',
            method:"GET"
        }).then(function successCallback(response){
            data = response.data;
            if(data.status == true){
                // Apache
                if(data.output.apache == true){
                    $scope.apache_status = "on";
                }else{
                    $scope.apache_status = "off";
                }

                // MYSQL
                if(data.output.mysql == true){
                    $scope.mysql_status = "on";
                }else{
                    $scope.mysql_status = "off";
                }
            }
            setTimeout(function () {
                serviceStatus()
            },2000);

        }, function errorCallback(response){
            console.log('error response')
        });
    }


    /**
     * Apache Actions
     * params : start / stop
     */

    function apacheAction(action, success , error){
        $scope.apache_status = 'wait';
        $http({
            url:'service.php?action=apache_'+action,
            method:'GET'
        }).then( function successCallback(response){
            //serviceStatus();
            success(response)
        }, function errorCallback(response){
            //serviceStatus();
            error(response)
        });
    }

    /**
     * MySQL Action
     * @param action : start / stop
     */
    function mysqlActions(action,success,error){
        $scope.mysql_status = 'wait';
        $http({
            url:'service.php?action=mysql_'+action,
            method:'GET',
            timeout:'6000',
        }).then( function successCallback(response){
            //serviceStatus();
            //console.log(response.data);
            success(response)

        }, function errorCallback(response){
            //serviceStatus();
            error(response)
        });

    }

}]);