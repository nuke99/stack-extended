<?php
require_once('profiles/mamp.php');
class Service {

	private $_service_detail;
    private $_inst;

	public function load_service($service){
		
		$json = @file_get_contents('config.json');
		if($json  == false){
			return false;
		}else{
			$service_array = json_decode($json);
			$this->_service_detail = $service_array->{$service};
            $this->_inst = new $service($this->_service_detail);
		}
	}


	public function is_mysql_runing(){
		$port = $this->_port_check('127.0.0.1',$this->_service_detail->{'mysql_port'});
		return $port;
	}

	public function is_apache_runing(){
		$port = $this->_port_check('127.0.0.1',$this->_service_detail->{'apache_port'});
		return $port;
	}

    public function apache_start(){
        $this->_inst->apache_start();

        $count = 0;
        while($this->is_apache_runing() == false){
            $count++;
            if($count == 10){
                return "Apache could not start";
                break;
            }
            sleep(1);
        }
        return "Apache started successfully";
    }

    public function apache_stop(){
        $this->_inst->apache_stop();
        $count = 0;
        while($this->is_apache_runing() == true){
            $count++;
            if($count == 10){
                return "Apache could not stop";
                break;
            }
            sleep(1);
        }

        return "Apache stopped successfully";
    }

    public function apache_restart(){
        $this->_inst->apache_restart();
        return "Apache is restarting";
    }




    public function mysql_start(){
        $this->_inst->mysql_stop();
        $this->_inst->mysql_start();
        $count = 0;
        while($this->is_mysql_runing() == false){
            $count++;
            if($count == 20){
                return "MySQL could not start";
                break;
            }
            sleep(1);
        }

        return "MySQL started successfully";
    }

    public function mysql_stop(){
        $this->_inst->mysql_stop();
        $count = 0;
        while($this->is_mysql_runing() == true){
            $count++;
            if($count == 10){
                return "MySQL could not stop";
                break;
            }
            sleep(1);
        }

        return "MySQL stopped successfully";
    }


    public function virtual_hosts(){
        $hosts = $this->_inst->virtual_host_list();
        if($hosts == false){
            output(false,"No virtual hosts created");
        }else{
            output(true,$hosts);
        }
    }

    public function create_virtual_host(){

        $data = json_decode(file_get_contents("php://input"));
        $error = array();
        if($data){
            // Name
            if(!isset($data->ServerName) || empty($data->ServerName)){
                $error['ServerName'] = "Name Cannot be empty";
            }
            // Alias
            if(!isset($data->ServerAlias) || empty($data->ServerAlias)){
                $error['ServerAlias'] = "Alias cannot be empty";
            }
            // Path
            if(!isset($data->DocumentRoot) || empty($data->DocumentRoot)){
                $error['DocumentRoot'] = "Document Root cannot be empty";
            }
            // IP
            if(!isset($data->ip) || empty($data->ip)){
                $error['ip'] = "IP cannot be empty";
            }
            // Port
            if(!isset($data->port) || empty($data->port)){
                $error['port'] = "Port cannot be empty";
            }

            // Server E-mail
            if(!isset($data->ServerAdmin) || empty($data->ServerAdmin)){
                $error['ServerAdmin'] = "E-mail cannot be empty";
            }else{
                if(filter_var($data->ServerAdmin, FILTER_VALIDATE_EMAIL) === false){
                    $error['ServerAdmin'] = "E-mail is invalid";
                }
            }

             // Error Logs
             if(!isset($data->ErrorLog) || empty($data->ErrorLog)){
                 $error['ErrorLog'] = "Error log cannot be empty";
             }

            //Custom Logs
            if(!isset($data->CustomLog) || empty($data->CustomLog)){
                $error['CustomLog'] = "Custom Error log cannot be empty";
            }

        }

        if(!empty($error)){
            output(false,$error);
        }

        $host = $this->_inst->create_virtual_host($data);

    }


    // ------- Private functions
	private function _port_check($ip,$port){
		$fp = @fsockopen($ip, $port, $error , $errstr, 5);
		if($fp == true){
			fclose($fp);
			return true;
		}else{
			return false;
		}

	}
}
?>