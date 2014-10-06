<?php
// plug/controllers/getinconf.php

//Getinconf client

addS ("getinconf_title","Getinconf client for microclouds");
addS ("getinconf_subtitle","A Getinconf client to configure a tinc VPN network for microclouds");
addS ("getinconf_description",'Getinconf is a tool that automates the configuration of VPN networks based on <a href="http://www.tinc-vpn.org">tinc</a>. A node running the Getinconf client registers in a server using the required credentials (microcloud network and password) and provides its public key. After this, it receives the VPN configuration, including the public keys of the other nodes in the microcloud. Once the configuration is automatically applied, it connects with the other nodes securely, creating a virtual Layer 2 microcloud network.');
addS ("getinconf_form_server_url","Getinconf server URL");
addS ("getinconf_form_server_url_help","The URL of the Getinconf server. The default URL in Guifi is <strong>http://10.139.40.84/index.php</strong>");
addS ("getinconf_form_microcloud_network","Microcloud network");
addS ("getinconf_form_microcloud_network_help","The network name of the microcloud this node belongs to. The default value, <strong>demo</strong>, can be used for testing purposes");
addS ("getinconf_form_network_password","Network password");
addS ("getinconf_form_network_password_help","The password of the microcloud network above. The password of the <strong>demo</strong> microcloud network is <strong>demo</strong>");
addS ("getinconf_form_community_network_interface","Network interface");
addS ("getinconf_form_community_network_interface_help","The network interface of this device that is connected to the Community Network (typical values are <strong>eth0</strong> or eth1). This interface must have an IP address which is valid in the Community Network IP address (e.g. 10.78.45.23)");
addS ("getinconf_tinc_status_running","Tinc-vpn is running");
addS ("getinconf_tinc_status_stopped","Tinc-vpn is stopped");


?>