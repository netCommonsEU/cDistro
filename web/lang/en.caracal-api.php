<?php
// plug/lang/*.menu.php

//Caracal API

// Alerts
addS ("caracalapi_java_installed_false","Java Runtime Environment is not installed");
addS ("caracalapi_java_installed_true","Java Runtime Environment is installed");

addS ("caracalapi_caracalapi_installed_false", "Caracal REST API is not installed");
addS ("caracalapi_caracalapi_installed_true", "Caracal REST API is installed");

addS ("caracalapi_java_install_fail", "Failed installing Java");
addS ("caracalapi_download_fail", "Failed downloading Caracal REST API binaries");
addS ("caracalapi_dependeny_fail", "Failed installing dependencies");
addS ("caracalapi_install_success", "Caracal REST API was installed");
addS ("caracalapi_install_fail", "Failed to install Caracal REST API");
addS ("caracalapi_uninstall_success", "Caracal REST API was removed");
addS ("caracalapi_uninstall_fail", "Failed to remove Caracal REST API");
addS ("caracalapi_running_true", "Caracal REST API is running");
addS ("caracalapi_running_false", "Caracal REST API is stopped");
addS ("caracalapi_start_success", "Caracal REST API was started");
addS ("caracalapi_start_fail", "Failed to start Caracal REST API");
addS ("caracalapi_stop_success", "Caracal REST API was stopped");
addS ("caracalapi_stop_fail", "Failed to stop Caracal REST API");

addS ("caracalapi_saved_config", "Configuration file updated");

// Text
addS ("caracalapi_title","CaracalDB REST API");
addS ("caracalapi_subtitle","A REST API and web UI for the CaracalDB distributed key-value store");
addS ("caracalapi_description",'The CaracalDB API is a JSON-based REST API to access CaracalDB from within external services. The package also contains a simple web user interface as a front-end for the API. For more information, you can read about <a href="https://github.com/CaracalDB/CaracalDB">CaracalDB on Github</a>.');
addS ("caracalapi_status", "CaracalDB API status:");
addS ("caracalapi_config_file", "Configuration");
addS ("caracalapi_config_edit", "Edit CaracalDB API configuration");
addS ("caracalapi_config_path", "Current configuration file: ");
addS ("caracalapi_log", "Log");
addS ("caracalapi_log_path", "Current log file: ");

addS ("caracalapi_scroll_down", "Scroll down to the bottom of the log");

// Buttons
addS ("caracalapi_button_install", "Install CaracalDB API");
addS ("caracalapi_button_uninstall", "Uninstall CaracalDB API");

addS ("caracalapi_button_start", "Start CaracalDB API");
addS ("caracalapi_button_stop", "Stop CaracalDB API");

addS ("caracalapi_button_save", "Save configuration");

addS ("caracalapi_button_status", "Back to CaracalDB API");
addS ("caracalapi_button_reload", "Reload");
addS ("caracalapi_button_log", "View log");
addS ("caracalapi_button_ui", "WebUI");

addS ("caracalapi_button_cancel", "Cancel");
addS ("caracalapi_button_config", "Edit Config");

// Form
addS ("caracalapi_form_bsip", "Boostrap server IP");
addS ("caracalapi_form_bsip_help", "The IP address of the bootstrap server. The default value in Guifi.net is 10.228.207.42.");
addS ("caracalapi_form_bsport", "Boostrap server port");
addS ("caracalapi_form_bsport_help", "The port where the boostrap server is listening to incoming connections. The default value is 45678.");
addS ("caracalapi_form_localip", "CaracalDB server IP");
addS ("caracalapi_form_localip_help", "The IP address of the server where CaracalDB is running. The default value is this Cloudy instance's IP address.");
addS ("caracalapi_form_localport", "CaracalDB server port");
addS ("caracalapi_form_localport_help", "The port where the CaracalDB server is listening to incoming connections. The default value is 45678.");
addS ("caracalapi_form_webaddr", "CaracalDB API hostname");
addS ("caracalapi_form_webaddr_help", "The hostname of the server where CaracalDB API will run. The default value is localhost.");
addS ("caracalapi_form_webport", "CaracalDB API web port");
addS ("caracalapi_form_webport_help", "The port where the CaracalDB API web interface will be listening to incoming connections. The default value is 8088.");

?>