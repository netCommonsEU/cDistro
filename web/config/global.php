<?php
//global.php

// You can change PATH files.
$staticFile=$_SERVER['SCRIPT_NAME'];
$staticPath=dirname($staticFile);

$documentPath=$_SERVER['DOCUMENT_ROOT'];

// App configure
$appCopyright = "&copy; 2014, GPLv2";
$appHost = $_SERVER['HTTP_HOST'];
$appHostname = gethostname();
$appName = 'Cloudy';
$appURL="http://".$appHost;
$sysCPU=`cat /proc/cpuinfo | grep -m1 "model name" | awk -F: '{print $2}'`;
$sysRAM=`free | grep Mem | awk '{print $2}'`;
$communityURL="http://guifi.net";
$projectURL="http://clommunity-project.eu";
$LANG="en";

// Dir webapp
$plugs_controllers = "/plug/controllers/";
$plugs_menus = "/plug/menus/";
$plugs_avahi = "/plug/avahi/";
$lang_dir = "/lang/";

// Debug
$debug = false;

?>
