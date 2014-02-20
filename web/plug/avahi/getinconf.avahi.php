<?php
// plug/avahi/getinconf.avahi.php

addAvahi('tincvpn','ftincvpn');

function ftincvpn($dates){
	global $staticFile;

	return ("<a class='button' href='".$staticFile."/getinconf/nothing/".$dates['ip']."/".$dates['port']."'>View</a>");
}