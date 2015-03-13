<?php
// plug/lang/*.menu.php

//Caracal

//alerts
addS ("caracal_java_installed_false","Java Runtime Environment is not installed");
addS ("caracal_java_installed_true","Java Runtime Environment is installed");

addS ("caracal_caracal_installed_false", "CaracalDB Server is not installed");
addS ("caracal_caracal_installed_true", "CaracalDB is installed");

addS ("caracal_java_install_fail", "Java installation failed");
addS ("caracal_download_fail", "Download of CaracalDB binaries failed");
addS ("caracal_install_success", "CaracalDB was successfully installed");
addS ("caracal_install_fail", "CaracalDB installation failed");
addS ("caracal_uninstall_success", "CaracalDB was removed");
addS ("caracal_uninstall_fail", "CaracalDB removal failed");
addS ("caracal_running_true", "CaracalDB is running");
addS ("caracal_running_false", "CaracalDB is stopped");
addS ("caracal_start_success", "CaracalDB has been started");
addS ("caracal_start_fail", "CaracalDB failed to start");
addS ("caracal_stop_success", "CaracalDB has been stopped");
addS ("caracal_stop_fail", "CaracalDB failed to stop");

addS ("caracal_saved_config", "Configuration file has been updated");

// Text
addS ("caracal_title","CaracalDB");
addS ("caracal_subtitle","A distributed key-value store");
addS ("caracal_description",'<a href="https://github.com/CaracalDB">CaracalDB</a> is a consistent, scalable and flexible distributed key-value store written in Java using the <a href="https://github.com/kompics/kompics">Kompics</a> component framework. You can learn more about <a href="https://github.com/CaracalDB/CaracalDB">CaracalDB on Github</a>.');
addS ("caracal_status", "CaracalDB status:");
addS ("caracal_config_file", "CaracalDB configuration");
addS ("caracal_config_edit", "Edit CaracalDB configuration");
addS ("caracal_config_path", "Current configuration file: ");
addS ("caracal_log", "Log");
addS ("caracal_log_path", "Current log file: ");

addS ("caracal_scroll_bottom", "Scroll down to the bottom of the log");

// Buttons
addS ("caracal_button_install", "Install CaracalDB");
addS ("caracal_button_uninstall", "Remove CaracalDB");

addS ("caracal_button_start", "Start CaracalDB");
addS ("caracal_button_stop", "Stop CaracalDB");

addS ("caracal_button_save", "Save configuration file");

addS ("caracal_button_back", "Back to CaracalDB");
addS ("caracal_button_reload", "Reload page");
addS ("caracal_button_log", "View log");

addS ("caracal_button_cancel", "Cancel");
addS ("caracal_button_config", "Edit Config");

// Form
addS ("caracal_form_bsip", "Boostrap server IP");
addS ("caracal_form_bsip_help", "The IP address of the bootstrap server. The default value in Guifi.net is 10.228.207.42.");
addS ("caracal_form_bsport", "Boostrap server port");
addS ("caracal_form_bsport_help", "The port where the boostrap server is listening to incoming connections. The default value is 45678.");
addS ("caracal_form_localip", "CaracalDB server IP");
addS ("caracal_form_localip_help", "The IP address of the server where CaracalDB will run. The default value is this Cloudy instance's IP address.");
addS ("caracal_form_localport", "CaracalDB server port");
addS ("caracal_form_localport_help", "The port where the CaracalDB server will be listening to incoming connections. The default value is 45678.");

?>