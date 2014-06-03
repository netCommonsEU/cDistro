<?php
// plug/avahi/peerstreamer.avahi.php

addAvahi('peerstreamer','fpeerstreamer');

function fpeerstreamer($dates){
	global $staticFile;

	return ("<a class='btn' href='".$staticFile."/peerstreamer/runps" . "?ip=" . $dates['ip'] ."&port=".$dates['port']."'>Join Video</a>  ");
}