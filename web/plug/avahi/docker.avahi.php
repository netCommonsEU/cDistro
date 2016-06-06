<?php
// plug/avahi/Docker.avahi.php

addAvahi('Docker','fDocker');

function fDocker($dates){
	global $staticFile;

	//Crea un botó apuntant cap a IP:Port del node que publica el servei
	return(addButton(array('label'=>"Enter App",'class'=>'btn btn-info', 'href'=>"http://".$dates['ip'].":".$dates['port'])));
}
