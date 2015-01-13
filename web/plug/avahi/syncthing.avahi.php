<?php
// plug/avahi/syncthing.avahi.php

require realpath(__DIR__ . "/../resources/syncthing/common.php");

addAvahi('syncthing','fsyncthing');

function fsyncthing($data){
	global $staticFile;
	$config = readConfig(); // TODO: don't read it again for each entry

	// We don't have syncthing installed and configured
	if ($config === false) {
		return ("<a class='btn btn-primary' href=$staticFile/syncthing>Install Syncthing first</a>");
	}

	$ip = $data['ip'];
	$port = $data['port'];
	$host = $data['host'];
	$node_id = "";
	$args = "ip=$ip&port=$port&host=$host";

	if (isset($data['txt'])) {
		$extras = $data['txt'];

		$extras_array = explode("&", $extras);
		foreach ($extras_array as $extra) {
			$split = explode("=", $extra, 2);
			$key = $split[0];
			$val = $split[1];
			if ($key == "node_id") {
				$node_id = $val;
			}
		}

		if (strlen($extras) > 0) {
			$args .= "&$extras";
		}
	}

	if (isSelf($config, $node_id)) {
		return ("<a class='btn disabled'>This is you</a>");
	}

	if (isConnectedTo($config, $ip, $port, $node_id)) {
		return ("<a class='btn btn-danger' href=$staticFile/syncthing/disconnect?$args>Disconnect from Node</a>");
	}

	return ("<a class='btn btn-success' href=$staticFile/syncthing/connect?$args>Connect to Node</a>");
}
