<?php
// plug/controllers/getinconf.php

//Getinconf client

addS ("getinconf_title","Getinconf client for microclouds");
addS ("getinconf_subtitle","A Getinconf client to configure a tinc-vpn network for microclouds");
addS ("getinconf_subtitle_interface_status","VPN network interface status");
addS ("getinconf_description",'Getinconf is a tool that automates the configuration of VPN networks based on <a href="http://www.tinc-vpn.org">tinc-vpn</a>. The Getinconf client connects to the server and receives the VPN configuration and the keys required to connect to the other nodes in the microcloud. This creates a secure virtual layer-2 microcloud network.');
addS ("getinconf_form_server_url","Getinconf server URL");
addS ("getinconf_form_server_url_help","The URL of the Getinconf server. The default URL in Guifi.net is <strong>http://10.139.40.84/index.php</strong>");
addS ("getinconf_form_microcloud_network","Microcloud network");
addS ("getinconf_form_microcloud_network_help","The network name of the microcloud this node belongs to. The default value, <strong>demo</strong>, can be used for testing purposes");
addS ("getinconf_form_network_password","Network password");
addS ("getinconf_form_network_password_help","The password of the microcloud network above. The password of the <strong>demo</strong> microcloud network is <strong>demo</strong>");
addS ("getinconf_form_community_network_device","Network device");
addS ("getinconf_form_community_network_device_help","The network interface of this device that is connected to the Community Network (typical values are <strong>eth0</strong> or eth1). The interface must have a CN-wide valid IP address");
addS ("getinconf_settings", "Getinconf client settings:");
addS ("getinconf_tinc_status", "Getinconf client status:");
addS ("getinconf_tinc_status_running","Getinconf client is running");
addS ("getinconf_tinc_status_stopped","Getinconf client is stopped");
addS ("getinconf_button_save","Save configuration");
addS ("getinconf_button_stop","Stop Getinconf client");
addS ("getinconf_button_start","Start Getinconf client");
addS ("getinconf_button_uninstall","Uninstall Getinconf client");
addS ("getinconf_button_interface","Show the VPN interface status");
addS ("getinconf_button_back","Back to Getinconf client");
addS ("getinconf_interface_command_output_pre","");
addS ("getinconf_interface_command_output_post"," command output:");
addS ("getinconf_alert_stopping","Stopping Getinconf client...");
addS ("getinconf_alert_starting","Starting Getinconf client...");
addS ("getinconf_alert_uninstall","Getinconf client has been uninstalled");
addS ("getinconf_alert_saved","Getinconf client settings have been saved");
addS ("getinconf_click_button_back", "Click on the button to go back to Getinconf client");
?>