<?php


if($_GET['cmd'] == 'run'){
    $cmd = "ping -c 10 google.com";
    print_r(liveExecuteCommand($cmd));
}elseif($_GET['cmd'] == 'kill'){
    fclose($_GET['pid']);
}


function liveExecuteCommand($cmd)
{

    while (@ ob_end_flush()); // end all output buffers if any

    $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

    $live_output     = "";
    $complete_output = "";
    var_dump(proc_get_status($proc));
    while (!feof($proc))
    {
        $live_output     = fread($proc, 4096);
        $complete_output = $complete_output . $live_output;
        echo "$live_output";
        @ flush();
    }

    pclose($proc);

    // get exit status
    preg_match('/[0-9]+$/', $complete_output, $matches);

    // return exit status and intended output
    return array (
        'exit_status'  => $matches[0],
        'pid' => $proc,
        'output'       => str_replace("Exit status : " . $matches[0], '', $complete_output)
    );
}
?>