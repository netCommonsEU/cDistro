<?php
// plug/avahi/peerstreamer.avahi.php

addAvahi('serf','fserf');

function fserf($dates){
	global $staticFile;

	return ("<a class='btn' href='".$staticFile."/serf/index" . "?join=" . $dates['ip'] .":".$dates['port']."'>".t('Join through this node')."</a>  ");
}
