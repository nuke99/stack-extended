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