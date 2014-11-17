<?php
  // Languages functions
$lang_hash=array();

function t($str){
	global $lang_hash, $debug;

	$md5val = md5($str);

	if (isset($lang_hash[$md5val])){
		return ($lang_hash[$md5val]);
	} else {
		if ($debug) { $str = "<!".$str."!>";}
		return ($str);
	}
}

function addS($str,$str_trans){
	global $debug,$lang_hash;

	$md5val = md5($str);
	$lang_hash[$md5val] = $str_trans;
	if ($debug) {echo "<pre>";echo "md5val: $md5val\nstr_trans: $str_trans\n"; print_r($lang_hash);echo "</pre>";}

}

function addLangFiles($lang_dir, $lang) {
	$matches = glob($lang_dir . $lang . ".*.php");
	if (is_array($matches)) {
		foreach ($matches as $filename) {
			require $filename;
		}
	}
}

//Load language file
if (($tLang = getSessionValue('lang')) != null){
	$LANG=$tLang;
}
if (!file_exists($documentPath.$lang_dir.$LANG.".php")){
	$LANG="en";
}
include_once $documentPath.$lang_dir.$LANG.".php";
