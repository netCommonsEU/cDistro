<?php
//menu.php

$menu = array();
//$menu['left'] = array('login'=>'auth/login');

$menu['right'] = array('config'=>
		array('getinconf'=>'getinconf',
			  'guifi-proxy3' => 'guifi/proxy3',
			  'snpservices' => 'guifi/snpservices',
			  'dnsservices' => 'guifi/dnsservices'
			)
	);

?>