<?php
// plug/avahi/cdn-p2p.avahi.php

addAvahi('bemtv','fcdnp2p');

function fcdnp2p($dates){
	global $staticFile;

	return ("<a class='btn' href='".$staticFile."/cdn-p2p/view" . "?".urldecode($dates['txt'])."'>".t('View This channel')."</a>  ");
}
