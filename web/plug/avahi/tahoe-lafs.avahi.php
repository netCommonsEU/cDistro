<?php
// plug/avahi/tahoe-lafs.avahi.php

addAvahi('tahoeintro','ftahoeintroducer');

function ftahoeintroducer($dates){
	global $staticFile;

	return ("<a class='btn' href='".$staticFile."/tahoe-lafs/node/".$dates['ip']."/".$dates['port']."'>Add to</a> <a class='btn' href='http://".$dates['ip'].":8228' target='_blank'>View</a> ");
}