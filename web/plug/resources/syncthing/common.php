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

function sxml_append(SimpleXMLElement $to, SimpleXMLElement $from) {
	$to_dom = dom_import_simplexml($to);
	$from_dom = dom_import_simplexml($from);
	$to_dom->appendChild($to_dom->ownerDocument->importNode($from_dom, true));
}

function sxml_remove(SimpleXMLElement $element) {
	$dom = dom_import_simplexml($element);
	$dom->parentNode->removeChild($dom);
}

function isNode($device, $ip, $port, $node_id) {
	return (
		$device->attributes()->id == $node_id ||
		$device->address == "$ip:$port");
}

function isSelf($config, $ip, $port, $node_id) {
	$devices = $config->device;
	// Assuming that we are the first device
	return isNode($devices[0], $ip, $port, $node_id);
}

function isConnectedTo($config, $ip, $port, $node_id) {
	$devices = $config->device;
	foreach ($devices as $device) {
		if (isNode($device, $ip, $port, $node_id)) {
			return true;
		}
	}
	return false;
}

function connectTo($config, $ip, $port, $host, $node_id) {
	$device = new SimpleXMLElement("
<device id=\"$node_id\" name=\"$host\" compression=\"true\" introducer=\"false\">
	<address>$ip:$port</address>
</device>
	");
	sxml_append($config, $device);
}

function disconnectFrom($config, $ip, $port) {
	$devices = $config->device;
	foreach ($devices as $device) {
		if ($device->address == "$ip:$port") {
			sxml_remove($device);
			break;
		}
	}
}
