<?php
// plug/lang/en.settings.php

//Settings

//alerts
addS("settings_alert_", "");


//addSourceFile
addS("settings_sources_add_subtitle", "Add a new repository sources file");
addS("settings_sources_add_contents_pre", "Contents of the <strong>");
addS("settings_sources_add_contents_post", "</strong> sources file:");
addS("settings_sources_add_desc", "Fill in the form below to create a new sources file and add package repositories:");
addS("settings_sources_add_form_filename", "Filename");
addS("settings_sources_add_form_filename_tooltip", 'The file will be saved to the /etc/apt/sources.list.d/ directory. The ".list" extension will be automatically appended.');
addS("settings_sources_add_form_content", "Content");
addS("settings_sources_add_form_content_placeholder", "### Add your repository sources here");
addS("settings_sources_add_form_content_tooltip", "Add the package repository sources here, one per line.");
addS("settings_sources_add_form_error", "An error occurred:");
addS("settings_sources_add_form_error_file", "The sources file could not be created");
addS("settings_sources_add_form_error_filename", "The filename can not be blank");
addS("settings_sources_add_form_error_content", "The file content can not be empty");
addS("settings_sources_add_form_submit", "Save new sources file");


//buttons
addS("settings_button_hostname", "Modify hostname");
addS("settings_button_back", "Back");
addS("settings_button_shostname", "Save hostname");
addS("settings_button_disable", "Disable");
addS("settings_button_enable", "Enable");
addS("settings_button_network_configure", "Configure primary interface");
addS("settings_button_network_modify", "Modify primary interface");
addS("settings_button_network_setprimary", "Set primary interface");
addS("settings_button_retry", "Retry");
addS("settings_button_sources_addfile", "Add a new sources file");
addS("settings_button_sources_addline", "Add a new source line");
addS("settings_button_sources_pre", "Manage ");
addS("settings_button_sources_post", "");


//common
addS("settings_common_title", "System settings");
addS("settings_common_subtitle", "System settings management and configuration");
addS("settings_common_error", "An error occurred:");


//flash
addS("settings_flash_hostname", "Hostname successfully changed");
addS("settings_flash_hostnamefail", "Hostname change failed");
addS("settings_flash_network_primaryint_success", "Primary network interface successfully saved");
addS("settings_flash_network_primaryint_fail", "Primary network interface couldn't be saved");


//hostname
addS("settings_hostname_title", "Hostname");
addS("settings_hostname_current", "Current hostname:");
addS("settings_hostname_subtitle", "Hostname modification");
addS("settings_hostname_description", "Use this page to modify the hostname of this Cloudy device.");
addS("settings_hostname_fname", "Hostname");
addS("settings_hostname_ftooltip", "Write the new hostname for this device using lowercase or uppercase letters, numbers and hyphens. The hostname must start and finish with numbers or letters.");
addS("settings_hostname_fplaceholder", "Enter a valid hostname");
addS("settings_hostname_invalid", "Invalid hostname:");
addS("settings_hostname_empty", "(empty)");


//network
addS("settings_network_title", "Network");
addS("settings_network_subtitle", "Network settings and configuration");
addS("settings_network_description", "Use this page to manage network-related settings and configuration. ");
addS("settings_network_primaryint", "Primary network interface:");
addS("settings_network_primaryint_invalid", "The selected primary interface is not valid:");
addS("settings_network_primaryint_empty", "(no primary network interface selected)");
addS("settings_network_form_primaryint", "Primary interface");
addS("settings_network_form_primaryint_tooltip", "Select the primary network interface of this Cloudy device, or the one connected to the Community Network.");
addS("settings_network_title", "Network");
addS("settings_network_empty", "(empty)");


//index
addS("settings_index_description", "This page shows different system settings and lets you modify them.");


//sources
addS("settings_sources_title", "Package sources");
addS("settings_sources_subtitle", "Package sources management");
addS("settings_sources_main_pre", "Main file ");
addS("settings_sources_main_post", ":");
addS("settings_sources_file_pre", "File ");
addS("settings_sources_file_post", ":");
addS("settings_sources_fileinvalid_pre", "The specified file ");
addS("settings_sources_fileinvalid_post", " is not a valid package sources file");
addS("settings_sources_description1", "This is the content of the ");
addS("settings_sources_description2", " file.");
addS("settings_sources_description3", "Use the buttons below to enable, disable, add or remove sources to the file.");
addS("settings_sources_header1", "Line #");
addS("settings_sources_header2", "Content");
addS("settings_sources_header3", "Actions");
