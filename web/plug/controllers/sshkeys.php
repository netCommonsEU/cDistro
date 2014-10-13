<?php
// Add ssh keys
$file_keys=dirname(__FILE__)."/../resources/sshkey/sshkeys.txt";
$ssh_path="/root/.ssh";
$root_key_path=$ssh_path."/authorized_keys";


function _getArrayKeys($file_keys){

	$aRet = array();
	$handle = fopen($file_keys, "r");
	if ($handle) {
	    while (($line = fgets($handle)) !== false) {
	        $aRet[] = rtrim($line);
	    }
	//} else {
	    // Maybe file doesn't exist.
	}
	fclose($handle);
	return $aRet;
}
function _checkkey($Keys){

	global $ssh_path,$root_key_path;

	if (!is_dir($ssh_path)) {
    	mkdir($ssh_path, 0700);
	}

	if (!file_exists($root_key_path)){
		touch($root_key_path);
	}

	$exist = 0;
	$h = fopen($root_key_path, "r");
	if ($h) {
	    while (($line = fgets($h)) !== false) {
	    	$line = rtrim($line);
	    	foreach($Keys as $k){
	    		if (strcmp($line,$k) == 0) {
	    			$exist++;
	    		}
	    	}
	    }
	//} else {
	    // Maybe file doesn't exist.
	}
	fclose($h);

	return ($exist);
}
function _addkey($key){
	global $root_key_path;

	file_put_contents($root_key_path, $key, FILE_APPEND | LOCK_EX);
}

function addkey(){
	global $staticFile,$file_keys;

	$Keys = _getArrayKeys( $file_keys );
	foreach ($Keys as $value) {
		if ( _checkkey(array($value)) == 0 ){
			_addkey($value);
		}
	}
	return(array('type'=>'redirect','url'=>$staticFile.'/sshkeys'));
}

function rmkey(){
	global $staticFile,$file_keys,$root_key_path;

	$content = "";
	$Keys = _getArrayKeys( $file_keys );
	$h = fopen($root_key_path, "r");
	if ($h) {
	    while (($line = fgets($h)) !== false) {
	    	$line = rtrim($line);
	    	$exist = false;
	    	foreach($Keys as $k){
	    		if (strcmp($line, $k) == 0) {
	    			$exist = true;
	    		}
	    	}
	    	if (!$exist) {
	    		$content .= $line."\n";
	    	}
	    }
	}
	fclose($h);
	file_put_contents($root_key_path, $content);
	return(array('type'=>'redirect','url'=>$staticFile.'/sshkeys'));
}

function index(){

	global $staticFile,$file_keys,$root_key_path;

	$page = "";

	$page .= hl(t("Add sshkeys"));
	$page .= par(t("Current")." '".$root_key_path."' ".t("file:"));
	$page .= ptxt(file_get_contents($root_key_path));
	$page .= par(t("Add/Remove there keys:"));
	$page .= ptxt(file_get_contents($file_keys));

	if (_checkkey( _getArrayKeys( $file_keys ) )  == 0)
	{
		$page .= addButton(array('label'=>t('Install Keys in ').$root_key_path,'class'=>'btn btn-success', 'href'=>$staticFile.'/sshkeys/addkey'));
	} else {
		$page .= addButton(array('label'=>t('Remove Key in ').$root_key_path,'class'=>'btn btn-success', 'href'=>$staticFile.'/sshkeys/rmkey'));
	}

	return(array('type'=>'render','page'=>$page));
}
