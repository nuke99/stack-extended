<?php

/**
 * Created by PhpStorm.
 * User: treasure
 * Date: 3/6/16
 * Time: 9:36 PM
 */
class Mamp{

    private $_service;


    public function __construct($service){
        $this->_service = $service;
    }


    public function apache_restart(){
        // Stop Apache && Start Apache
        file_put_contents("bin/mamp/action",$this->_service->path."bin/stopApache.sh && sleep 2 && ".$this->_service->path."bin/startApache.sh >& /dev/null"); // write action file
        $command = "open bin/mamp/Apache.app"; // read from action file and execute
        $cmd = shell_exec($command);
        return true;
    }


    /**
     * Apache Start
     * @return bool
     */
    public function apache_start(){
        //$command = "osascript bin/mamp/apache.scpt ".$this->_service->path."bin/startApache.sh >& /dev/null";
        file_put_contents("bin/mamp/action",$this->_service->path."bin/startApache.sh >& /dev/null"); // write action file
        $command = "open bin/mamp/Apache.app"; // read from action file and execute
        $cmd = shell_exec($command);
        return true;
    }


    public function apache_stop(){
        file_put_contents("bin/mamp/action",$this->_service->path."bin/stopApache.sh >> /dev/null &"); // write action file
        $command = "open bin/mamp/Apache.app"; // read from action file and execute
        system($command);
        return true;
    }

    public function mysql_start(){
        set_time_limit(2);
        passthru('bash bin/mamp/mysql_start.sh /dev/null 2>/dev/null &');
        exit();
        passthru('bash '.$this->_service->path."bin/startMysql.sh >& /dev/null");

        return true;
    }

    public function mysql_stop(){
        system('sh '.$this->_service->path."bin/stopMysql.sh >& /dev/null");
        return true;
    }

    public function virtual_host_list(){
        $path = $this->_service->path."conf/apache/extra/stackex/";
        if(!is_dir($path)){
            return false;
        }

        $all_files = scandir($path);
        $vhost_files = array();
        foreach($all_files as $k => $file){

            if(trim($file) !== "." && $file != '..'){
                $vhost_files[] = $file;
            }
        }

        if(count($vhost_files) == 0){
            return false;
        }
        $list = array();
        foreach($vhost_files as $k => $file){
            $data = file_get_contents($path.$file);
            preg_match_all('~<VirtualHost ([^>]+)>(.*?)</VirtualHost>~is',$data,$match);

            foreach($match[2] as $key => $value){
                $ip_port = trim($match[1][$key]);
                list($ip, $port) = explode(":",$ip_port);

                $hostValues = array();
                $hostValues['ip'] = $ip;
                $hostValues['port'] = $port;
                foreach(explode("\n",$value) as $k => $line){
                    $line = trim($line);
                    if(!empty($line)){
                        $keyValues = explode(" ",$line);
                        if($keyValues[0] != "#" and $keyValues[0][0] != "#"){
                            if(strtolower($keyValues[0]) == "<directory" || strtolower($keyValues[0]) == "</directory>") {
                                preg_match_all('~<Directory ([^>]+)>(.*?)</Directory>~is',$value,$dic_match);

                                $hostValues['Directory'] = explode("\n",$dic_match[2][0]);
                            }else{
                                $kk = $keyValues[0];
                                unset($keyValues[0]);
                                $val = implode(' ',$keyValues);
                                $hostValues[$kk] = str_replace('"','',$val);

                            }
                        }
                    }
                }
                $list[] = $hostValues;
            }

        }


        return $list;

    }

}