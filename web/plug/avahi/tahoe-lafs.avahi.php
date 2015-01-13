<?php
// plug/avahi/tahoe-lafs.avahi.php

addAvahi('tahoe-lafs','ftahoeintroducer');

function ftahoeintroducer($dates){
	global $staticFile;

	return ("<a class='btn' href='http://".$dates['ip'].":8228' target='_blank'>View</a> <a class='btn' href='".$staticFile."/tahoe-lafs/createNode/"."?furl=pb://".$dates['txt']."@".$dates['ip'].":".$dates['port']."/introducer"."'>Join grid</a>  ");
}