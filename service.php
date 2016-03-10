<?php
include_once('core/service.php');
$service = new Service();
$service->load_service('mamp');



if(isset($_GET['action'])){

    $action = $_GET['action'];
    // Services Status
    if($action == 'status'){
        $status = array(
            'mysql' => $service->is_mysql_runing(),
            'apache' => $service->is_apache_runing(),
        );
        output(true,$status);
    }else{
        $status = $service->$action();
        output(true,$status);
    }

}else{
    output(false,'nothing to see');
}


function output($status,$output){

    header('Content-Type: application/json');
    echo  json_encode(array(
        'status' => $status,
        'output' => $output
    ));

    exit();
    die();
}

?>