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
$log_file = 'log/deploy.log';

if (!isRunning($pidfile)) {
    echo "executing\n";
    installRoles($log_file, $pidfile);
    deploy($environment, $log_file, $pidfile);
} else {
    echo "already running";
}

function installRoles($logFile, $pidFile)
{
    $cmd = 'ansible-galaxy install -r /playbook/requirements.yml -p /roles';
    exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $logFile, $pidFile));
}

function deploy($environment, $logFile, $pidFile)
{
    $cmd = "ansible-playbook -i /playbook/$environment /playbook/deploy.yml";
    exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $logFile, $pidFile));
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