<?php
//endpage

global $js, $staticPath;
?>
</div> <!-- End "content" -->
<?php
	if (is_array($js)){
		foreach($js as $j){
			echo '<script src="'.$staticPath.'js/'.$j.'.js"></script>';
		}
	}
?>
</body>
</html>