<?php
//view.php

function hl($text=null,$kind=1){
	if (!is_null($text)){
		if ((!is_numeric($kind)) || (($kind < 0) && ($kind > 4)) ){
			$kind = 4;
		}
		return ("<h".$kind.">".$text."</h".$kind.">\n<br/>");
	}
}