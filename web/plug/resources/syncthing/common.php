<?php

$dirpath="/opt/syncthing";
$binname="syncthing";
$binpath="$dirpath/$binname";
$repospath="$dirpath/repos";
$nodeidpath="$dirpath/node_id";

$cfgpath="$dirpath/config";
define("cfgpath_xml", "$cfgpath/config.xml");

$user="www-data";
$title="Syncthing";
$sc_port="22000";
$webui_port="8080";
$webui_user="syncthing";
$webui_pass="syncthing";
$webui_pass_bc='$2a$10$COoGrWYTpPxwGWqUPlOv7eEpw5EzbxhGZpsXIsCXZRjE0cn4sr7D6'; // bcrypt for "syncthing"

$avahi_type="syncthing";
$avahi_desc="Syncthing instance running";

$releases_url="https://github.com/syncthing/syncthing/releases/download";
$version="0.10.5";

function readConfig() {
    return simplexml_load_file(cfgpath_xml);
}

function hasConfig() {
    return file_exists(cfgpath_xml);
}

function writeConfig($config) {
    $config->asXml(cfgpath_xml);
}

function passwordChanged($config) {
    global $webui_pass_bc;
    return ($config->gui->password != $webui_pass_bc);
}

function getNodeID($config) {
    return ($config->device[0]->attributes()->id);
}

function isInstalled() {
	global $binpath;
	return(is_executable($binpath));
}

function getPid() {
	global $binpath;
	$pid_str = execute_program_shell("pidof $binpath | tr -s ' ' '\\n' | sort -n | sed 1q")['output'];
	if ($pid_str == NULL or $pid_str == "") {
		return -1;
	}
	return (int)$pid_str;
}

function isRunning() {
	return (getPid() != -1);
}
