<?php
// plug/avahi/tahoe-lafs.avahi.php

addAvahi('tahoe-lafs','ftahoeintroducer');

function ftahoeintroducer($dates){
	global $staticFile;

	return ("<a class='btn' href='http://".$dates['ip'].":8228' target='_blank'>View</a> <a class='btn' href='".$staticFile."/tahoe-lafs/createNode/"."?furl=pb://".substr($dates['txt'], 1, -1)."@".$dates['ip'].":".$dates['port']."/introducer"."'>Join grid</a>  ");
}