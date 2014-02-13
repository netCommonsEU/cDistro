<?php
// flash!


$flash_class = "success";
if (isset($_SESSION['flash'])){
	if (isset($_SESSION['flash_class'])) {
		$flash_class = $_SESSION['flash_class'];
		unset($_SESSION['flash_class']);
	}
	echo "<div class='flash alert alert-".$flash_class."'>".$_SESSION['flash']."</div>\n";
	unset($_SESSION['flash']);
}
