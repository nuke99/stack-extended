<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=$config['name']?></title>

      <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'  type='text/css'>

    <!-- Bootstrap -->
    <link href="_media/css/bootstrap.min.css" rel="stylesheet">
    <link href="_media/css/style.css" rel="stylesheet">
    <link href="_media/css/font-awesome.min.css" rel="stylesheet">
    <link href="_media/css/app-colors.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="vHostExtended">
    
    <div class="navbar navbar-head flat-green-background">
    	<div class="container-fluid">
    		<div class="navbar-header">
    			<a class="navbar-brand" href="#">Stack <small>extended</small> <sup id="top-version"> <?=$config['version']?> </sup></a>
    		</div>
    		<button type="button" class="btn btn-success flat-green-light-background  navbar-btn pull-right"><i class="fa fa-cog"></i></button>

             <ul class="nav navbar-nav">
                 <li><a target="_blank" href="http://127.0.0.1/phpmyadmin">phpMyAdmin</a></li>
            </ul>
    	</div>

    </div>


    <div  class="row container-fluid">
    	<div ng-controller="serviceController" class="col-lg-12">

    		<div class="col-lg-4 col-sm-5 float_right" style="padding-right: 0;">
                <div class="col-lg-12 col-sm-12" style="margin-bottom: 5px">
                    <div class="col-lg-4 col-sm-6 col-lg-offset-4"  style="text-align: right" uib-tooltip="Apache is {{apache_status =='off' ? 'not' : ''}} running">
                        <span>Apache <i class="fa fa-circle-o-notch" ng-class="{'red':apache_status == 'off' , 'green':apache_status == 'on'}"></i></span>
                    </div>
                    <div class="col-lg-4 col-sm-6" style="text-align: left" uib-tooltip="MYSQL is {{mysql_status =='off' ? 'not' : ''}} runing">
                        MYSQL <i class="fa fa-circle-o-notch" ng-class="{'red':mysql_status == 'off' , 'green':mysql_status == 'on'}"></i>
                    </div>
                </div>

                    <div class="btn-group" style="float: right;">
                        <div class="btn-group">
                            <button ng-click="startAll()" class="btn btn-primary" ng-disable="(apache_status == 'wait' || mysql_status == 'wait')">
                                <i class="fa fa-power-off "></i>
                                <span ng-cloak="">{{mysql_status == 'off' && apache_status == 'off' ? 'Start All' : 'Restart All'}}</span>
                            </button>
                            <button ng-show="mysql_status === 'on' || apache_status  === 'on'" type="button" class="btn btn-primary dropdown-toggle ng-cloak" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" class="red"> Stop All <span class="glyphicon glyphicon-off pull-right red"></span></a></li>
                            </ul>
                        </div>

                        <button ng-click="apacheAction()" ng-disabled="(apache_status == 'wait')" class="btn btn-default" ng-class="{'btn-success':apache_status == 'off' , 'btn-danger':apache_status == 'on'}">
                            <img src="_media/images/apache_icon.png" width="15" height="15">
                            <span class="ng-cloak">{{apache_status == 'on' ? 'Stop' : 'Start'}}</span> Apache
                        </button>
                        <button ng-click="mysqlAction()" ng-disabled="(mysql_status == 'wait')" class="btn btn-default" ng-class="{'btn-success':mysql_status == 'off' , 'btn-danger':mysql_status == 'on'}">
                            <img src="_media/images/mysql_icon.png" width="15" height="15">
                            <span class="ng-cloak">{{mysql_status == 'on' ? 'Stop' : 'Start'}}</span> MYSQL
                        </button>

                    </div>
            </div>
    	</div>


        <div ng-controller="hostsController" class="col-lg-12" style="margin-left: 0;padding-left: 0">
            <div class="col-sm-4 fill" style="padding-left: 0;padding-right: 0;margin-top: 10px">
                <div id="vhost-list" class="panel panel-default fill ">
                    <div class="panel-heading ">
                        <h3 class="panel-title pull-left">Host List </h3>
                        <button class="btn btn-success pull-right flat-green-background"><i class="fa fa-plus"></i></button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body host-panel-body" style="height:100%">
                        <div class="list-group" ng-repeat="host in hosts">
                            <a ng-click="select_host($index)" href="#" class="list-group-item host-list-item">
                                <b ng-cloak="ng-cloak">{{host.ServerName}}</b>
                                <div style="color: #999;font-size: 12px" class="ng-cloak">
                                    {{host.DocumentRoot}}
                                </div>
                            </a>

                        </div>


                    </div>

                </div>
            </div>

            <div class="col-sm-8 fill" style="margin-top: 10px">
                <div  class="panel panel-default fill ">
                    <div class="panel-heading ">
                        <h3 class="panel-title "> <span class="ng-cloak">{{host.ServerName}}</span> </h3>

                    </div>
                    <div class="panel-body">
                        <h4>General</h4>
                        <form class="form-horizontal">
                            <!-- Server Name -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Server Name</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" placeholder="demoweb.local"  ng-model="host.ServerName">
                                </div>
                                <div class="checkbox col-sm-5">
                                    <label>
                                        <input type="checkbox" class=" col-sm-2" checked>Add to etc/hosts
                                    </label>
                                </div>

                            </div>


                            <!-- Server Alias -->
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">Server Alias</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" placeholder="www.demoweb.local"  ng-model="host.ServerAlias">
                                </div>
                            </div>

                            <!-- Path -->
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">Document Root</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                          <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-folder"></i> Select</button>
                                          </span>
                                        <input type="text" class="form-control" placeholder="/var/www/project" ng-model="host.DocumentRoot">
                                    </div>

                                </div>
                            </div>

                            <!-- IP & PORT-->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">IP & Port</label>
                                <div class="col-sm-4">
                                    <select class="form-control" ng-model="host.ip">
                                        <option value="*">*</option>
                                        <option value="<?=@gethostbyname(gethostname())?>"><?=@gethostbyname(gethostname())?></option>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control" ng-model="host.port" style="width: 65px">
                                </div>
                            </div>

                            <!-- Server E-mail -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Server Admin</label>
                                <div class="col-sm-5">
                                    <input type="email" class="form-control" placeholder="admin@demoweb.local" ng-model="host.ServerAdmin">
                                </div>
                            </div>

                            <!-- Error Log -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Error Log</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" placeholder="logs/demoweb.local-error.log" ng-model="host.ErrorLog">
                                </div>
                            </div>

                            <!-- Custom Log -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Custom Log</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" placeholder="logs/demoweb.access-error.log common" ng-model="host.CustomLog">
                                </div>
                            </div>

                            <hr class="divider">
                            <h4>Directory & Additional </h4>
                            <!-- Custom Log -->
                            <div class="form-group ">
                                <label class="col-sm-2 control-label">Directory Options</label>
                                <div class="checkbox col-sm-10">
                                    <div class="col-sm-12">
                                        <label class="checkbox col-sm-3">
                                            <input type="checkbox" class=" col-sm-1" >Indexes
                                        </label>

                                        <label class="checkbox col-sm-3">
                                            <input type="checkbox" class=" col-sm-1" checked>Includes
                                        </label>
                                    </div>

                                    <div class="col-sm-12">
                                        <label class="checkbox col-sm-3">
                                            <input type="checkbox" class=" col-sm-1" checked>FollowSymLinks
                                        </label>

                                        <label class="checkbox col-sm-3">
                                            <input type="checkbox" class=" col-sm-1" >SymLinksifOwnserMatch
                                        </label>
                                    </div>

                                    <div class="col-sm-12">
                                        <label class="checkbox col-sm-3">
                                            <input type="checkbox" class=" col-sm-1" checked>Exec CGI
                                        </label>
                                    </div>

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label"> AllowOverride </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value="All">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"> Order </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value="allow,deny">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label"> Allow from </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value="all">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Additional Parameters</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" rows="5" id="comment"></textarea>
                                </div>
                            </div>


                        </form>



                    </div>
                </div>
            </div>
        </div>
<!--        End of hostsController-->
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="_media/js/bootstrap.min.js"></script>
    <script src="_media/js/angular.min.js"></script>
    <script src="_media/js/ui-bootstrap-tpls-1.2.4.min.js"></script>

    <!--    Angular App -->
    <script src="_media/js/app/app.js"></script>
    <script src="_media/js/app/services.js"></script>
    <script src="_media/js/app/hosts.js"></script>
  </body>
</html>