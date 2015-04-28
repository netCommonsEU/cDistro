<?php
// plug/controllers/guifi-dnss.php

//Common
addS ("guifi-dnss_common_appname","Guifi DNSServices");
addS ("guifi-dnss_common_desc","Automatic DNS servers configuration system for Guifi.net nodes");
addS ("guifi-dnss_common_status_pre","");
addS ("guifi-dnss_common_status_post"," status:");
addS ("guifi-dnss_common_guifi:","Guifi.net website integration:");
addS ("guifi-dnss_alert_running_pre","");
addS ("guifi-dnss_alert_running_post"," is running");
addS ("guifi-dnss_alert_stopped_pre","");
addS ("guifi-dnss_alert_stopped_post"," is stopped");

//Alerts
addS ("guifi-dnss_alert_installed_pre","");
addS ("guifi-dnss_alert_installed_post"," is installed");
addS ("guifi-dnss_alert_not_installed_pre","");
addS ("guifi-dnss_alert_not_installed_post"," is not installed");
addS ("guifi-dnss_alert_guifi","This Cloudy device is registered with Guifi.net's website");
addS ("guifi-dnss_alert_not_guifi","This Cloudy device is not registered with Guifi.net's website");
addS ("guifi-dnss_alert_save","Saving configuration...");

//Buttons
addS ("guifi-dnss_button_create_service","Create DNSServices at Guifi.net website");
addS ("guifi-dnss_button_register","Register this Cloudy device with Guifi.net website");
addS ("guifi-dnss_button_back","Back");
addS ("guifi-dnss_button_install_pre","Install ");
addS ("guifi-dnss_button_install_post","");
addS ("guifi-dnss_button_sinstall_pre","Save settings and install ");
addS ("guifi-dnss_button_sinstall_post","");
addS ("guifi-dnss_button_save","Save and apply settings");
addS ("guifi-dnss_button_configure_pre","Configure ");
addS ("guifi-dnss_button_configure_post","");
addS ("guifi-dnss_button_uninstall_pre","Uninstall ");
addS ("guifi-dnss_button_uninstall_post","");
addS ("guifi-dnss_button_stop_pre","Stop ");
addS ("guifi-dnss_button_stop_post","");
addS ("guifi-dnss_button_start_pre","Start ");
addS ("guifi-dnss_button_start_post","");
addS ("guifi-dnss_button_unregistered_pre","Install ");
addS ("guifi-dnss_button_unregistered_post"," without registering this device");
addS ("guifi-dnss_button_unregistereds_pre","Install ");
addS ("guifi-dnss_button_unregistereds_post"," without declaring it at the website");

//Index
addS ("guifi-dnss_index_desc","DNSServices is an automatic DNS servers configuration system for Guifi.net nodes. It provides address resolution for the domain names created by users at Guifi.net's website (including reverse DNS lookup) and for Internet domain names.");
addS ("guifi-dnss_index_connected","To install this service, the device has to be connected to both Guifi and the Internet.");
addS ("guifi-dnss_index_checkwiki","You can check this wiki page for more information: ");
addS ("guifi-dnss_index_wikiurl","http://en.wiki.guifi.net/wiki/DNS_Server");
addS ("guifi-dnss_index_not_guifi","This Cloudy device is not linked with any device at Guifi.net's website.");
addS ("guifi-dnss_index_register_before_pre","Click on the button below to register it before installing the ");
addS ("guifi-dnss_index_register_before_post"," service.");
addS ("guifi-dnss_index_register","Click on the button below to register it.");
addS ("guifi-dnss_index_guifi","Click on the button below to install the Guifi DNSServices service.");

//Install
addS ("guifi-dnss_install_subtitle","Installation and configuration");
addS ("guifi-dnss_install_declare","To install this service you need to declare it at Guifi.net's website to obtain a service ID.");
addS ("guifi-dnss_install_autodeclare","Click on the button below to automatically create the service at Guifi.net's website.");
addS ("guifi-dnss_install_otherwise","Otherwise, fill in the form click on the button to install the service without declaring it.");
addS ("guifi-dnss_install_declared_pre","To install ");
addS ("guifi-dnss_install_declared_post",", fill in the form click on the button below.");
addS ("guifi-dnss_install_configure_pre","Use this page to change ");
addS ("guifi-dnss_install_configure_post"," configuration or to uninstall it.");
addS ("guifi-dnss_install_value","The service ID has been autocompleted with the information retrieved from Guifi.net's website.");

//Form
addS ("guifi-dnss_form_id","Service id");
addS ("guifi-dnss_form_id_tooltip","The ID of the service at Guifi.net's website (e.g. http://guifi.net/node/<strong>123456</strong>)");
addS ("guifi-dnss_form_url","DNS data URL");
addS ("guifi-dnss_form_url_tooltip","The URL of the server to synchronize the DNS database with (default <strong>http://guifi.net</strong>). Do not add the trailing slash.");

?>