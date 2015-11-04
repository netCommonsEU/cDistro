<?php
//plug/controllers/guifi-nms.php

$NMS_PACKAGES = array (
		array (
			'type' => 'bw',
			'name' => 'guifi-nms_common_bw',
			'packages' => array("netperfmeter", "iperf", "iperf3", "netperf"),
			),
		array (
			'type' => 'ping',
			'name' => 'guifi-nms_common_ping',
			'packages' => array("iputils-ping", "fping", "2ping", "echoping", "hping3", "httping", "oping"),
			),
		array (
			'type' => 'route',
			'name' => 'guifi-nms_common_route',
			'packages' => array("traceroute", "mtr", "inetutils-traceroute", "paris-traceroute", "tcptraceroute"),
		),
	);

function index() {
	global $staticFile, $NMS_PACKAGES;

	$buttons = '';
	$page = '';
	$GUIFI_CONF = '';

	$page .= hlc(t("guifi-nms_common_title"));
	$page .= hl(t("guifi-nms_common_subtitle"),4);

	$page .= par(t("guifi-nms_index_desc1"));

	foreach	($NMS_PACKAGES as $PKGS) {
		$page .= txt(t("guifi-nms_common_title_type_pre").t($PKGS[name]).t("guifi-nms_common_title_type_post"));

		$installed = 0;
		$available = 5;

		foreach ($PKGS[packages] as $PKG) {
			if (isPackageInstall($PKG)) {
				$page .= "<div class='alert alert-success text-center'>".t("guifi-nms_alert_installed_pre")."<strong>".t($PKG)."</strong>".t("guifi-nms_alert_installed_post")."</div>\n";
				$installed+1;
			}
			else {
				$page .= "<div class='alert alert-error text-center'>".t("guifi-nms_alert_not_installed_pre")."<strong>".t($PKG)."</strong>".t("guifi-nms_alert_not_installed_post")."</div>\n";
			}
		}
		$page .= "<div class='alert alert-success text-center'>".t("guifi-nms_alert_installed_pre")."<strong>".t($PKG)."</strong>".t("guifi-nms_alert_installed_post")."</div>\n";

	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

?>