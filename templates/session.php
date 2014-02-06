<?php

//Session!!


	function getSessionValue($key){
		if (isset($_SESSION[$key])){
			return $_SESSION[$key];
		} else {
			return null;
		}
	}

	function setSessionValue($key,$value){
		$_SESSION[$key] = $value;
	}

	function setFlash($msg,$class="alert"){
		setSessionValue('flash',$msg);
		setSessionValue('flash_class',$class);
	}

	session_start();
