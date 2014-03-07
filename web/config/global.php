<?php
//global.php

// You can change PATH files.
$staticFile=$_SERVER['SCRIPT_NAME'];
$staticPath=dirname($staticFile);

$documentPath=$_SERVER['DOCUMENT_ROOT'];

// App configure
$appName = "cGuinux";
$appURL="http://".$_SERVER['HTTP_HOST'];
$projectURL="http://guifi.net";
$LANG="en";

// Dir webapp
$plugs_controllers = "/plug/controllers/";
$plugs_menus = "/plug/menus/";
$plugs_avahi = "/plug/avahi/";
$lang_dir = "/lang/";

// Debug
$debug = false;

?>