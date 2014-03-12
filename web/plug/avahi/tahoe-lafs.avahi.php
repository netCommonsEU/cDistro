<?php
// plug/avahi/tahoe-lafs.avahi.php

addAvahi('tahoe-lafs','ftahoeintroducer');

function ftahoeintroducer($dates){
	global $staticFile;

	return ("<a class='btn' href='http://".$dates['ip'].":8228' target='_blank'>View</a> <a class='btn' href='".$staticFile."/tahoe-lafs/createNode/".$dates['ip']."/".$dates['port']."'>Join grid</a>  ");
}