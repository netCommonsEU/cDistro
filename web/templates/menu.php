<?php
	//menu.php

function printMenu($menu){

	global $staticFile;
	foreach($menu as $km=>$vm){
		if (is_array($vm)) {
			echo '<li class="dropdown">';
			echo '<a href="#" id="id'.$km.'" role="button" class="dropdown-toggle" data-toggle="dropdown">'.$km.'</a>';
			echo '	<ul class="dropdown-menu" role="menu" aria-labelledby="id'.$km.'">';
			printMenu($vm);
			echo '  </ul>';
			echo '</li>';
		} else {
			echo '<li>';
			echo '<a href="'.$staticFile."/".$vm.'">'.$km.'</a>';
			echo '</li>';
		}
	}
}
?>

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a href="<?php echo $appURL; ?>" class="brand"><img src=/images/clommunity-logo.png><?php echo $appName; ?></a>
			<div class="nav-collapse">
				<?php if (isset($menu['left'])) { ?>
				<ul class="nav">
					<?php
						printMenu($menu['left']);
					?>
				</ul>
				<?php } ?>
				<?php if (isset($menu['right'])) { ?>
				<ul class="nav pull-right">
					<?php
						printMenu($menu['right']);
					?>
				</ul>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
