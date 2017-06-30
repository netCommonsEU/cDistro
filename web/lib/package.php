<?php
//package.php

function getYourVersion($user, $repo){
	global $dir_configs;

	if (!is_dir($dir_configs)) {
    	mkdir($dir_configs);
	}
	$configfile = $dir_configs."/".$user."-".$repo.".sha";
	if (!file_exists($configfile))
		return (t('cloudyupdate_getYourVersion_none'));
	else
		return (str_replace("\n", "",str_replace("\r", "",file_get_contents($configfile))));

}