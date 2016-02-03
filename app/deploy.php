<?php
//$filename = '/var/www/var/gogogo';
//$date = new DateTime();
//if (!file_exists($filename)) {
//    $file = fopen($filename, 'w');
//    $execution_date = $date->format('Y-m-d H:i:s');
//    echo $execution_date;
//    fwrite($file, $execution_date);
//    fclose($file);
//}
$environment = isset($_GET['env']) ? $_GET['env']: 'staging';

$pidfile = 'deploy.pid';
$cmd = "ansible-galaxy install -r /playbook/requirements.yml -p /roles;ansible-playbook -i /playbook/$environment /playbook/deploy.yml -vvvv";
$outputfile = '/deploylog/deploy.log';
if (!isRunning($pidfile)) {
    echo "executing\n$cmd\n";
    exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
} else {
    echo "already running";
    echo file_get_contents($outputfile);
}

function isRunning($pidfile){
    if (file_exists($pidfile)) {
        $pid = file_get_contents($pidfile);
        try{
            $result = shell_exec(sprintf("ps %d", $pid));
            if( count(preg_split("/\n/", $result)) > 2){
                return true;
            } else {
                unlink($pidfile);
            }
        }catch(Exception $e){}
    }
    return false;
}