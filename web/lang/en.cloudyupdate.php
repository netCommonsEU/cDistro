<?php
// plug/controllers/cloudyupdate.php

//alerts
addS ("cloudyupdate_alert_unspecified","No package was specified for upgrade");

//buttons
addS ("cloudyupdate_button_back","Back to Cloudy updates manager");
addS ("cloudyupdate_button_continue","Continue to Cloudy updates manager");
addS ("cloudyupdate_button_no_back","No, go back to Cloudy updates manager");
addS ("cloudyupdate_button_update_debian","Update Debian packages list");
addS ("cloudyupdate_button_upgrade","Upgrade");
addS ("cloudyupdate_button_upgrade_all","Upgrade all");
addS ("cloudyupdate_button_yes_upgrade_1","Yes, upgrade");
addS ("cloudyupdate_button_yes_upgrade_2","Debian package");
addS ("cloudyupdate_button_yes_upgrade_all","Yes, upgrade all Debian packages");

//common
addS ("cloudyupdate_common_title","Cloudy updates manager");

//debpkgupgrade
addS ("cloudyupdate_debpkgupgrade_result","Debian package upgrade result:");
addS ("cloudyupdate_debpkgupgrade_question_1","Do you really want to actually upgrade package");
addS ("cloudyupdate_debpkgupgrade_question_2","?");
addS ("cloudyupdate_debpkgupgrade_simresult","Debian package upgrade simulation result:");
addS ("cloudyupdate_debpkgupgrade_simulation_1","Before upgrading package");
addS ("cloudyupdate_debpkgupgrade_simulation_2",", the whole process has been simulated. If the result looks good to you, proceed with the actual upgrade. Otherwise, you can upgrade the package manually.");
addS ("cloudyupdate_debpkgupgrade_subtitle","Debian package upgrade");
addS ("cloudyupdate_debpkgupgrade_subtitle_pre","Debian package");
addS ("cloudyupdate_debpkgupgrade_subtitle_post","upgrade");

//debupdate
addS ("cloudyupdate_debupdate_subtitle","Debian packages list update");
addS ("cloudyupdate_debupdate_result","Debian packages list update process result:");

//debupgrade
addS ("cloudyupdate_debupgrade_question","Do you really want to actually upgrade all packages?");
addS ("cloudyupdate_debupgrade_result","Debian packages upgrade result:");
addS ("cloudyupdate_debupgrade_simresult","Debian packages upgrade simulation result:");
addS ("cloudyupdate_debupgrade_simulation","Before upgrading all Debian packages, the whole process has been simulated. If the result looks good to you, proceed with the actual upgrade. Otherwise, you can upgrade the packages manually.");
addS ("cloudyupdate_debupgrade_subtitle","Debian packages upgrade");

//flash
addS ("cloudyupdate_flash_loading_cloudy", "Loading Cloudy packages information");
addS ("cloudyupdate_flash_loading_debian", "Loading Debian packages information");

//index
addS ("cloudyupdate_index_cloudy_packages","Cloudy packages");
addS ("cloudyupdate_index_debian_packages","Debian packages");
addS ("cloudyupdate_index_debian_description1","The list of Debian packages needs to be updated to check for upgrades. Click on the button below to update it.");
addS ("cloudyupdate_index_description1","The Cloudy updates manager allows you to keep your node up-to-date. It checks for updates on both Cloudy packages and Debian packages and automates the upgrade process.");
addS ("cloudyupdate_index_description2","An Internet connection is required to check and obtain the updates.");
addS ("cloudyupdate_index_subtitle","A tool for updating your Cloudy device");

//getCloudyUpdateTable
addS ("cloudyupdate_getCloudyUpdateTable_action", "Action");
addS ("cloudyupdate_getCloudyUpdateTable_new", "Last version");
addS ("cloudyupdate_getCloudyUpdateTable_package", "Package");
addS ("cloudyupdate_getCloudyUpdateTable_version", "Installed version");

//getDebianUpdateTable
addS ("cloudyupdate_getDebianUpdateTable_action", "Action");
addS ("cloudyupdate_getDebianUpdateTable_new", "New version");
addS ("cloudyupdate_getDebianUpdateTable_no_updates", "There are no updates for Debian packages");
addS ("cloudyupdate_getDebianUpdateTable_package", "Package");
addS ("cloudyupdate_getDebianUpdateTable_status", "Debian packages status:");
addS ("cloudyupdate_getDebianUpdateTable_version", "Installed version");
addS ("cloudyupdate_getDebianUpdateTable_updates", "There are Debian packages updates available");

?>