<?php

global $sc_dirpath, $sc_binname, $sc_initpath, $sc_binpath, $sc_repospath, $sc_nodeidpath;
$sc_dirpath="/opt/syncthing";
$sc_binname="syncthing";
$sc_initpath="/etc/init.d/syncthing";
$sc_binpath="$sc_dirpath/$sc_binname";
$sc_repospath="$sc_dirpath/repos";
$sc_nodeidpath="$sc_dirpath/node_id";

global $sc_cfgpath, $sc_initd, $sc_initd_orig;
$sc_cfgpath="$sc_dirpath/config";
define("sc_cfgpath_xml", "$sc_cfgpath/config.xml");
$sc_initd_orig="/var/local/cDistro/plug/resources/syncthing/syncthing.init.d";
$sc_initd="/etc/init.d/syncthing";

global $sc_user, $sc_title, $sc_port;
$sc_user="www-data";
$sc_title="Syncthing";
$sc_port="22000";

global $sc_webui_port, $sc_webui_user, $sc_webui_pass, $sc_webui_pass_bc;
$sc_webui_port="8080";
$sc_webui_user="syncthing";
$sc_webui_pass="syncthing";
$sc_webui_pass_bc='$2a$10$COoGrWYTpPxwGWqUPlOv7eEpw5EzbxhGZpsXIsCXZRjE0cn4sr7D6'; // bcrypt for "syncthing"

global $sc_avahi_type, $sc_avahi_desc;
$sc_avahi_type="syncthing";
$sc_avahi_desc="Syncthing instance running";

global $sc_releases_url, $sc_version;
$sc_releases_url="https://github.com/syncthing/syncthing/releases/download";
$sc_version="0.11.7";
define("sc_counter", 30);

function nameForArch($arch) {
	global $sc_version;
	switch ($arch) {
	case "amd64":
	case "x86_64":
	case "ia64":
		$urlarch = "amd64";
		break;
	case "x86":
	case "i386":
	case "i686":
		$urlarch = "386";
		break;
	case "armv6l":
	case "armv7l":
		$urlarch = "arm";
		break;
	}
	return "syncthing-linux-$urlarch-v$sc_version";
}

function downloadUrl($name) {
	global $sc_releases_url, $sc_version;
	return "$sc_releases_url/v$sc_version/$name.tar.gz";
}

function hasConfig() {
	return file_exists(sc_cfgpath_xml);
}

function isInstalled() {
	global $sc_binpath;
	return is_executable($sc_binpath);
}

function isConfigured() {
	return isInstalled() && hasConfig();
}

function readConfig() {
	if (!isConfigured()) {
		return false;
	}
	return simplexml_load_file(sc_cfgpath_xml);
}

function rest_get($path) {
	global $sc_webui_port;
	return file_get_contents("https://127.0.0.1:$sc_webui_port/rest/$path");
}

function rest_get_auth($config, $path) {
	global $sc_webui_port;
	$apikey = $config->gui->apikey;
	$opts = array(
		'http'=>array(
			'method'=>"GET",
			'header'=>"X-API-Key: $apikey\r\n"
		)
	);
	$ctx = stream_context_create($opts);
	return file_get_contents("https://127.0.0.1:$sc_webui_port/rest/$path", false, $ctx);
}

function get_own_id($config) {
	$json = rest_get_auth($config, 'system');
	$array = json_decode($json);
	if ($array == null) return "";
	return $array->myID;
}

function writeConfig($config) {
	$config->asXml(sc_cfgpath_xml);
}

function passwordChanged($config) {
	global $sc_webui_pass_bc;
	if ($config === false) return false;
	return ($config->gui->password != $sc_webui_pass_bc);
}

function getNodeID($config) {
	if ($config === false) return null;
	return $config->device[0]->attributes()->id;
}

function getPid() {
	global $sc_binpath;
	$pid_str = execute_program_shell("pidof $sc_binpath | tr -s ' ' '\\n' | sort -n | sed 1q")['output'];
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

function isSelf($config, $node_id) {
	return get_own_id($config) == $node_id;
}

function isConnectedTo($config, $ip, $port, $node_id) {
	if ($config === false) return false;
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
