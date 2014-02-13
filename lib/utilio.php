<?php
	//utils I/O utilio.php
function load_conffile($file,$default = null){
	$variables = "";

	if ($default != null) {
		$variables = array();
		foreach($default as $k=>$v){
			$variables[$k]=(string)$v['default'];
		}
	}
	if (!file_exists($file)){
		if (is_array($variables)){return($variables);}
		notFileExist($file);
	}
	if(($v = parse_ini_file($file)) == FALSE) {
		if (is_array($variables)){return($variables);}
		notReadFile($file);
	}
	return($v);
}
function load_singlevalue($file,$varis ){
	global $debug;

	if (!file_exists($file)){
		$variables = array();
		foreach($varis as $k=>$v){
			$variables[$k]=(string)$v['default'];
		}
		return($variables);
	}

	$v = array();
	// llegir fitxer
	$c = file_get_contents($file);

	if ($debug) { echo "<pre>";}

	foreach($varis as $vari=>$vals){
		$p = "/{$vari}[ \t]*=[ \t]*([^;]*)/";
		preg_match($p, $c, $a);
		if ($debug) { print_r($a); }
		if (is_array($a) && isset($a[1])){
			$v[$vari] = $a[1];
		} else {
			$v[$vari] = $vals['default'];
		}
		if (substr($v[$vari],0,1) == "'") $v[$vari] = substr($v[$vari],1);
		if (substr($v[$vari],-1,1) == "'") $v[$vari] = substr($v[$vari],0,strlen($v[$vari])-1);
		if ($debug) { echo "v=$v[$vari]";}
	}

	if ($debug) { echo "</pre>";}

	return($v);

}
function write_conffile($file,$dates,$preinfo="",$postinfo=""){
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
function write_merge_conffile($file,$dates){
	global $debug;
	
	if ($debug) { echo "<pre>";}
	foreach($dates as $k=>$v){
		$cmd = "sed -i -e 's|".$k." *= *[^;]*;|".$k." = ".$v.";|g' ".$file;
		if ($debug) { echo $cmd;}
		shell_exec($cmd);
	}
	if ($debug) { echo "</pre>";}

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

function package_default_variables($dts,$default,$pkgname,$undefined_variables=null){
	global $debug;

	$str = "";
	foreach($dts as $k=>$v){
		$variable = $default[$k];

		$cmd="echo \"".$pkgname."	".$variable['vdeb']."	".$variable['kdeb']."	".$v."\" | debconf-set-selections 2>&1" ;
		if ($debug) $str .= $cmd."\n";

		$str .= shell_exec($cmd);
		if ($debug) $str .= "\n";
	}
	if (is_array($undefined_variables)) {
		foreach ($undefined_variables as $k => $v) {
			if(isset($v['debpkg'])){
				$p = $v['debpkg'];
			} else {
				$p = $pkgname;
			}
			$cmd="echo \"".$p."	".$v['vdeb']."	".$v['kdeb']."	".$v['default']."\" | debconf-set-selections 2>&1" ;
			if ($debug) $str .= $cmd."\n";

			$str .= shell_exec($cmd);
			if ($debug) $str .= "\n";		
		}
	}

	return($str);
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