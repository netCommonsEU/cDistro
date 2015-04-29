<?php
// plug/controllers/guifi-proxy3.php

//Common
addS ("guifi-proxy3_common_appname","Guifi Proxy3");
addS ("guifi-proxy3_common_desc","A federated proxy service for the Guifi.net Community Network");
addS ("guifi-proxy3_common_notice","Important notice:");
addS ("guifi-proxy3_common_status_pre","");
addS ("guifi-proxy3_common_status_post"," status:");
addS ("guifi-proxy3_common_guifi:","Guifi.net website integration:");
//addS ("","");


//Alerts
addS ("guifi-proxy3_alert_not_installed_pre","");
addS ("guifi-proxy3_alert_not_installed_post"," is not installed");
addS ("guifi-proxy3_alert_installed_pre","");
addS ("guifi-proxy3_alert_installed_post"," is installed");
addS ("guifi-proxy3_alert_not_guifi","This Cloudy device is not registered with Guifi.net's website");
addS ("guifi-proxy3_alert_guifi","This Cloudy device is registered with Guifi.net's website");
addS ("guifi-proxy3_alert_missing_variable","Missing configuration variable:");
addS ("guifi-proxy3_alert_installation_success","Guifi Proxy3 has been successfully installed");
addS ("guifi-proxy3_running","Guifi Proxy3 is running");
addS ("guifi-proxy3_stopped","Guifi Proxy3 is stopped");
addS ("guifi-proxy3_alert_save","Saving configuration..");
addS ("guifi-proxy3_flash_starting","Starting...");
addS ("guifi-proxy3_flash_stopping","Stopping...");
addS ("guifi-proxy3_flash_restarting","Restarting...");



//Buttons
addS ("guifi-proxy3_button_register","Register this Cloudy device before installing Guifi Proxy3");
addS ("guifi-proxy3_button_create_service_pre","Create ");
addS ("guifi-proxy3_button_create_service_post"," at Guifi.net website");
addS ("guifi-proxy3_button_unregistered_pre","Install ");
addS ("guifi-proxy3_button_unregistered_post"," without registering this device");
addS ("guifi-proxy3_button_unregistereds_pre","Install ");
addS ("guifi-proxy3_button_unregistereds_post"," without declaring it at the website");

addS ("guifi-proxy3_button_install_not_registered","Continue without registering this Cloudy device");
addS ("guifi-proxy3_button_install_pre","Install ");
addS ("guifi-proxy3_button_install_post","");
addS ("guifi-proxy3_button_uninstall_pre","Uninstall ");
addS ("guifi-proxy3_button_uninstall_post","");
addS ("guifi-proxy3_button_save","Save and apply settings");
addS ("guifi-proxy3_button_sinstall_pre","Save settings and install ");
addS ("guifi-proxy3_button_sinstall_post","");
addS ("guifi-proxy3_button_back","Back");
addS ("guifi-proxy3_button_stop","Stop Guifi Proxy3");
addS ("guifi-proxy3_button_start","Start Guifi Proxy3");
addS ("guifi-proxy3_button_restart","Restart Guifi Proxy3");
addS ("guifi-proxy3_button_configuration","Configure Guifi Proxy3");


//form
addS ("guifi-proxy3_form_url","Base URL");
addS ("guifi-proxy3_form_url_tooltip","Base URL of the server to obtain the Guifi Proxy3 configuration from (default: http://www.guifi.net)");
addS ("guifi-proxy3_form_node","Service ID");
addS ("guifi-proxy3_form_node_tooltip","The ID number of the proxy service at Guifi.net's website (e.g. http://guifi.net/node/<strong>123456</strong>)");
addS ("guifi-proxy3_form_ldap1","Primary LDAP server");
addS ("guifi-proxy3_form_ldap1_tooltip","Hostname of the primary LDAP server to authenticate proxy users against (default: ldap.guifi.net) ");
addS ("guifi-proxy3_form_ldap2","Secondary LDAP server");
addS ("guifi-proxy3_form_ldap2_tooltip","Hostname of the secondary LDAP server to authenticate proxy users against (default: ldap2.guifi.net) ");
addS ("guifi-proxy3_form_welcome","Welcome message");
addS ("guifi-proxy3_form_welcome_default","Guifi Proxy3 Squid proxy-caching federated server");
addS ("guifi-proxy3_form_welcome_tooltip","A short message the users will first see when using the proxy. Use something that identifies your node, etc (alphanumeric characters, no whitespaces)");
addS ("guifi-proxy3_form_email","Contact e-mail");
addS ("guifi-proxy3_form_email_placeholder","yourname@guifi.net");
addS ("guifi-proxy3_form_email_tooltip","An e-mail address to contact the node manager.");
addS ("guifi-proxy3_form_language","Messages language");
addS ("guifi-proxy3_form_language_default","English");
addS ("guifi-proxy3_form_language_tooltip","User messages and errors will be displayed in this language. <br>Valid options: Armenian, Azerbaijani, Bulgarian, Catalan, Czech, Danish, Dutch, English, Estonian, Finnish, French, German, Greek, Hebrew, Hungarian, Italian, Japanese, Korean, Lithuanian, Polish, Portuguese, Romanian, Serbian, Slovak, Spanish, Swedish and Turkish");
addS ("guifi-proxy3_form_cache","Disk cache (MB)");
addS ("guifi-proxy3_form_cache_tooltip","Maximum disk space (in MB) used for caching.");
addS ("guifi-proxy3_form_ram","RAM cache (MB)");
addS ("guifi-proxy3_form_ram_tooltip","Maximum RAM space (in MB) used for caching.");
//addS ("","");
//addS ("","");



//index
addS ("guifi-proxy3_index_description",'Guifi Proxy3 is a package that provides a federated proxy service with <a href="http://en.wiki.guifi.net/wiki/LDAP">LDAP authentication</a> for the Guifi.net Community Network. It is based on <a href="http://www.squid-cache.org">Squid</a>, a free software caching proxy for the Web supporting HTTP, HTTPS, FTP, and more.');
addS ("guifi-proxy3_index_connected","To install this service, the device has to be connected to both Guifi and the Internet.");
addS ("guifi-proxy3_index_checkwiki","You can check this wiki page for more information: ");
addS ("guifi-proxy3_index_wikiurl","http://en.wiki.guifi.net/wiki/Proxy3_Server");
addS ("guifi-proxy3_index_not_guifi","This Cloudy device is not linked with any device at Guifi.net's website.");
addS ("guifi-proxy3_index_register_before_pre","Click on the button below to register it before installing the ");
addS ("guifi-proxy3_index_register_before_post"," service.");
addS ("guifi-proxy3_index_install_pre","Click on the button below to install the ");
addS ("guifi-proxy3_index_install_post"," service.");


addS ("guifi-proxy3_index_registration",'To deploy the Guifi Proxy3 service with all its features, this Cloudy device needs to be registered in your node at Guifi.net\'s website. Use the button below to register it before installing the Guifi Proxy3 package.');
addS ("guifi-proxy3_install_saved","The configuration has been saved to");
addS ("guifi-proxy3_install_result","Installation process result:");
addS ("guifi-proxy3_install_details","Installation process details:");
addS ("","");
//addS ("","");
//addS ("","");
//addS ("","");


//install
addS ("guifi-proxy3_install_subtitle","Installation and configuration");
addS ("guifi-proxy3_install_declare","To install this service you need to declare it at Guifi.net's website to obtain a service ID.");
addS ("guifi-proxy3_install_autodeclare","Click on the button below to automatically create the service at Guifi.net's website.");
addS ("guifi-proxy3_install_otherwise","Otherwise, fill in the form click on the button to install the service without declaring it.");
addS ("guifi-proxy3_install_declared_pre","To install ");
addS ("guifi-proxy3_install_declared_post",", fill in the form click on the button below.");
addS ("guifi-proxy3_install_value","The service ID has been autocompleted with the information retrieved from Guifi.net's website.");

//install_not_registered
addS ("guifi-proxy3_install_not_registered_description","Use this page to configure the Guifi Proxy3 service on your Cloudy device. Just fill in the fields of the form below and the service will be automatically installed and configured.");
addS ("guifi-proxy3_install_configuration","Configuration process result:");
//addS ("","");
//addS ("","");
//addS ("","");






//addS ("","");


?>