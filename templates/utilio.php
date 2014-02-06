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
?>