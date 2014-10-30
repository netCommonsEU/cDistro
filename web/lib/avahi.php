<?php
// lib/avahi.php
$avahiactions = array();

function addAvahi($name_server,$fun_name){
	global $avahiactions;

	$avahiactions[$name_server]=$fun_name;

}

function addAvahiFiles($path){
	$matches = glob($path."*.avahi.php");
	if ( is_array ( $matches ) ) {
   		foreach ( $matches as $filename) {
      		require $filename;
   		}
	}
}

function checkAvahi($name_server,$server_array){
	global $avahiactions;

	$ret = "";
	if (isset($avahiactions[$name_server]) && function_exists($avahiactions[$name_server])) {
		$ret = call_user_func_array($avahiactions[$name_server],$server_array);
	}

	return ($ret);
}
