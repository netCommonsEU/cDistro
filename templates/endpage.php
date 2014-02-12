<?php
//endpage

//global $js_end, $staticPath;
?>
</div> <!-- End "content" -->
<?php
	if (is_array($js_end)){
		foreach($js_end as $j){
			echo '<script src="'.$staticPath.'js/'.$j.'.js"></script>';
		}
	}
?>
</body>
</html>