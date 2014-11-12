<?php
// plug/avahi/syncthing.avahi.php

require realpath(__DIR__ . "/../resources/syncthing/common.php");

addAvahi('syncthing','fsyncthing');

function fsyncthing($data){
	global $staticFile;
	$config = readConfig(); // TODO: don't read it again for each entry
	$ip = $data['ip'];
	$port = $data['port'];
	$node_id = "";
	$extras = "";

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
			$extras = "&$extras";
		}
	}

	if (isSelf($config, $ip, $port, $node_id)) {
		return ("<a class='btn disabled'>This is you</a>");
	}

	if (isConnectedTo($config, $ip, $port, $node_id)) {
		return ("<a class='btn' href=$staticFile/syncthing/disconnect?ip=$ip&port=$port>Disconnect from Node</a>");
	}

	return ("<a class='btn' href=$staticFile/syncthing/connect?ip=$ip&port=$port$extras>Connect to Node</a>");
}
