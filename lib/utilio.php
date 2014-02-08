<?php
	//utils I/O utilio.php
function load_conffile($file){

	if (!file_exists($file)){
		notFileExist($file);
	}
	if(($variables = parse_ini_file($file)) == FALSE) {
		notReadFile($file);
	}
	return($variables);
}
function write_conffile($file,$dates,$preinfo,$postinfo){
	//Prepare file
	$str = $preinfo;
	foreach($dates as $k=>$v){
		$str .="$k=$v\n";
	}
	$str .= $postinfo;

	if((file_put_contents($file, $str)) == FALSE) {
		notWriteFile($file);
	}
}
function isPackageInstall($pkg){
	$cmd = "dpkg -l ".$pkg." > /dev/null 2>&1; echo $?";
	return (shell_exec($cmd) == 0);

}
function installPackage($pkg){
	$cmd = "apt-get install -y ".$pkg." 2>&1";
	return (shell_exec($cmd));
}
function uninstallPackage($pkg){
	$cmd = "apt-get purge -y ".$pkg." 2>&1";
	return (shell_exec($cmd));
}

function execute_program($cmd){
	if (exec("$cmd 2>&1", $output, $return) == FALSE){
		errorExecuteExternalProgram($cmd);
	}

	return(array('output'=>$output,'return'=>$return));
}
function execute_program_shell($cmd){
	$ret = shell_exec($cmd." 2>&1");
	return(array('output'=>$ret,'return'=>""));	
}

function execute_shell($cmd){
	if (($ret = shell_exec($cmd." > /dev/null 2>&1; echo $?")) == NULL){
		errorExecuteExternalProgram($cmd);	
	}
	return(array('output'=>"",'return'=>$ret));	
}
function execute_bg_shell($cmd){

	exec('bash -c "exec nohup setsid '.$cmd.' > /dev/null 2>&1 &"');

}
function cmd_exec($cmd, &$stdout, &$stderr)
{
    $outfile = tempnam(".", "cmd");
    $errfile = tempnam(".", "cmd");
    $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("file", $outfile, "w"),
        2 => array("file", $errfile, "w")
    );
    $proc = proc_open($cmd, $descriptorspec, $pipes);
   
    if (!is_resource($proc)) return 255;

    fclose($pipes[0]);    

    $exit = proc_close($proc);
    $stdout = file($outfile);
    $stderr = file($errfile);

    unlink($outfile);
    unlink($errfile);
    return $exit;
}
function execute_proc($cmd){
	if (($return = cmd_exec("$cmd", $output, $outerr)) == NULL){
		errorExecuteExternalProgram($cmd,serialize($output)."-".serialize($outerr));	
	}
	return(array('output'=>$output,'return'=>$return));	
}

?>