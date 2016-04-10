<?php
//options
if(isset($data->Directory->Options)){
    $arr = get_object_vars($data->Directory->Options);
    $keys = array_keys($arr);
    $options = "Options ".implode(" ",$keys);
}else{
    $options = "";
}

$vhost_template = "
<VirtualHost $data->ip:$data->port>
    ServerAdmin $data->ServerAdmin
    DocumentRoot \"$data->DocumentRoot\"
    ServerName $data->ServerName
    ServerAlias $data->ServerAlias
    ErrorLog \"$data->ErrorLog\"
    CustomLog \"$data->CustomLog\" common
    <Directory \"$data->DocumentRoot\">
    ".$options."
    ".
        $data->Directory->extras
    ."
    </Directory>
</VirtualHost>
";