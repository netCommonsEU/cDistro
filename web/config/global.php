<?php
//global.php

// You can change PATH files.
$staticFile=$_SERVER['SCRIPT_NAME'];
$staticPath=dirname($staticFile);

$documentPath=$_SERVER['DOCUMENT_ROOT'];

// App configure
$appName = "cGuinux";
$appURL="http://localhost:7000";
$projectURL="http://guifi.net";

// Dir webapp
$plugs_controllers = "/plug/controllers/";
$plugs_menus = "/plug/menus/";

// Debug
$debug = false;
?>