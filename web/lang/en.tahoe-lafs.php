<?php
// plug/controllers/tahoe-lafs.php

//alerts

addS ("tahoe-lafs_alert_checking_introducer","Checking Tahoe-LAFS introducer status");
addS ("tahoe-lafs_alert_checking_storage","Checking Tahoe-LAFS storage node status");
addS ("tahoe-lafs_alert_installed_already","Tahoe-LAFS is already installed");
addS ("tahoe-lafs_alert_installed_empty","Tahoe-LAFS is installed but no introducer or storage node has been created yet");
addS ("tahoe-lafs_alert_installed_failed","Tahoe-LAFS installation has failed");
addS ("tahoe-lafs_alert_installed_successful","Tahoe-LAFS has been successfully installed");
addS ("tahoe-lafs_alert_introducer_configured","A Tahoe-LAFS introducer is currently configured");
addS ("tahoe-lafs_alert_introducer_deletion_failed","Tahoe-LAFS introducer deletion has failed");
addS ("tahoe-lafs_alert_introducer_deletion_successful","Tahoe-LAFS introducer has been successfully deleted");
addS ("tahoe-lafs_alert_introducer_failed","Tahoe-LAFS introducer creation has failed");
addS ("tahoe-lafs_alert_introducer_invalid_grid","Invalid storage grid name");
addS ("tahoe-lafs_alert_introducer_invalid_name","Invalid introducer name");
addS ("tahoe-lafs_alert_introducer_invalid_port","Invalid introducer web port number");
addS ("tahoe-lafs_alert_introducer_maximum","A maximum of 80 alphanumeric characters, dashes and underscores are allowed in the names");
addS ("tahoe-lafs_alert_introducer_not_created","The Tahoe-LAFS introducer is currently not created");
addS ("tahoe-lafs_alert_introducer_running","Tahoe-LAFS introducer is running");
addS ("tahoe-lafs_alert_introducer_started","Tahoe-LAFS introducer has been successfully started");
addS ("tahoe-lafs_alert_introducer_start_fail","Tahoe-LAFS introducer start has failed");
addS ("tahoe-lafs_alert_introducer_stopped","Tahoe-LAFS introducer is stopped");
addS ("tahoe-lafs_alert_introducer_stop_uninstall","Stop it and remove it before uninstalling Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_successful","Tahoe-LAFS introducer has been successfully created");
addS ("tahoe-lafs_alert_not_installed","Tahoe-LAFS is not installed");
addS ("tahoe-lafs_alert_request_incorrect","Incorrect request parameters received");
addS ("tahoe-lafs_alert_storage_configured","A Tahoe-LAFS storage node is currently configured");
addS ("tahoe-lafs_alert_storage_deletion_failed","Tahoe-LAFS storage node deletion has failed");
addS ("tahoe-lafs_alert_storage_deletion_successful","Tahoe-LAFS storage node has been successfully deleted");
addS ("tahoe-lafs_alert_storage_failed","Tahoe-LAFS storage node creation has failed");
addS ("tahoe-lafs_alert_storage_invalid_FURL","Invalid introducer FURL");
addS ("tahoe-lafs_alert_storage_invalid_name","Invalid storage node name");
addS ("tahoe-lafs_alert_storage_maximum","A maximum of 80 alphanumeric characters, dashes and underscores are allowed in the name");
addS ("tahoe-lafs_alert_storage_not_created","The Tahoe-LAFS storage node is currently not created");
addS ("tahoe-lafs_alert_storage_running","Tahoe-LAFS storage node is running");
addS ("tahoe-lafs_alert_storage_started","Tahoe-LAFS storage node has been successfully started");
addS ("tahoe-lafs_alert_storage_start_fail","Tahoe-LAFS storage node start has failed");
addS ("tahoe-lafs_alert_storage_stopped","Tahoe-LAFS storage node is stopped");
addS ("tahoe-lafs_alert_storage_stop_uninstall","Stop it and remove it before uninstalling Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_successful","Tahoe-LAFS storage node has been successfully created");
addS ("tahoe-lafs_alert_uninstalled_already","Tahoe-LAFS is already uninstalled");
addS ("tahoe-lafs_alert_uninstalled_failed","Tahoe-LAFS uninstallation has failed");
addS ("tahoe-lafs_alert_uninstalled_successful","Tahoe-LAFS has been successfully uninstalled");

//buttons
addS ("tahoe-lafs_button_back","Back to Tahoe-LAFS");
addS ("tahoe-lafs_button_create_introducer","Create an introducer");
addS ("tahoe-lafs_button_create_introducer_start_grid","Create an introducer and start a storage grid");
addS ("tahoe-lafs_button_create_storage","Create a storage node");
addS ("tahoe-lafs_button_create_storage_join_grid","Create a storage node to join a storage grid");
addS ("tahoe-lafs_button_delete_introducer","Delete introducer");
addS ("tahoe-lafs_button_delete_storage","Delete storage node");
addS ("tahoe-lafs_button_install","Install Tahoe-LAFS");
addS ("tahoe-lafs_button_introducer_private","Make introducer private");
addS ("tahoe-lafs_button_introducer_public","Make introducer public");
addS ("tahoe-lafs_button_introducer_retry","Retry introducer creation");
addS ("tahoe-lafs_button_manage_introducer","Manage introducer");
addS ("tahoe-lafs_button_manage_storage","Manage storage node");
addS ("tahoe-lafs_button_retry_install","Retry installation");
addS ("tahoe-lafs_button_retry_uninstall","Retry uninstallation");
addS ("tahoe-lafs_button_start_introducer","Start introducer");
addS ("tahoe-lafs_button_start_storage","Start storage node");
addS ("tahoe-lafs_button_stop_introducer","Stop introducer");
addS ("tahoe-lafs_button_stop_storage","Stop storage node");
addS ("tahoe-lafs_button_storage_retry","Retry storage node creation");
addS ("tahoe-lafs_button_uninstall","Uninstall Tahoe-LAFS");

//common
addS ("tahoe-lafs_common_empty","empty");
addS ("tahoe-lafs_common_status:","Tahoe-LAFS status:");
addS ("tahoe-lafs_common_title","Tahoe-LAFS");

//createIntroducer
addS ("tahoe-lafs_createintroducer_folder","Folder");
addS ("tahoe-lafs_createintroducer_folder_tooltip","The introducer will be installed in this folder.");
addS ("tahoe-lafs_createintroducer_grid_name_example","Example-Grid-");
addS ("tahoe-lafs_createintroducer_grid_name","Grid name");
addS ("tahoe-lafs_createintroducer_grid_name_tooltip","A short name to identify the storage grid.");
addS ("tahoe-lafs_createintroducer_instructions_1","To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed in the network.");
addS ("tahoe-lafs_createintroducer_instructions_2","Use this page to set up an introducer on this machine and start a storage grid.");
addS ("tahoe-lafs_createintroducer_instructions_3","After that, storage nodes will be able to join the introducer to deploy the storage grid.");
addS ("tahoe-lafs_createintroducer_introducer_name_example","MyIntroducer");
addS ("tahoe-lafs_createintroducer_introducer_name","Introducer name");
addS ("tahoe-lafs_createintroducer_introducer_name_tooltip","A short nickname to identify the introducer in the storage grid.");
addS ("tahoe-lafs_createintroducer_public","Public");
addS ("tahoe-lafs_createintroducer_public_tooltip","Announce the introducer service through Avahi and allow storage nodes to join the grid.");
addS ("tahoe-lafs_createintroducer_result","Introducer creation process result:");
addS ("tahoe-lafs_createintroducer_starting","Starting Tahoe-LAFS introducer:");
addS ("tahoe-lafs_createintroducer_subtitle","Introducer creation");
addS ("tahoe-lafs_createintroducer_web_port_tooltip","The port where the introducer's web management interface will run on.");
addS ("tahoe-lafs_createintroducer_web_port","Web port");

//createNode_get
addS ("tahoe-lafs_createnode_folder","Folder");
addS ("tahoe-lafs_createnode_folder_tooltip","The installation path for the storage node.");
addS ("tahoe-lafs_createnode_FURL","Introducer FURL");
addS ("tahoe-lafs_createnode_FURL_tooltip_1","The introducer's FURL of the storage grid you want to join.");
addS ("tahoe-lafs_createnode_FURL_tooltip_2","This value has been obtained from the information published by the introducer via Avahi.");
addS ("tahoe-lafs_createnode_FURL_tooltip_3","If you want to modify this field, please go back to the main Tahoe-LAFS page and manually create a storage node.");
addS ("tahoe-lafs_createnode_FURL_tooltip_4","The default value has been obtained from the introducer running on this host.");
addS ("tahoe-lafs_createnode_FURL_tooltip_5","If you want to join another introducer, please modify this field accordingly.");
addS ("tahoe-lafs_createnode_instructions_1","To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed in the network.");
addS ("tahoe-lafs_createnode_instructions_2","Use this page to set up a storage node and join a storage grid.");
addS ("tahoe-lafs_createnode_name_example","MyStorageNode");
addS ("tahoe-lafs_createnode_name","Storage node name");
addS ("tahoe-lafs_createnode_name_tooltip","A short nickname to identify the storage node in the grid.");
addS ("tahoe-lafs_createnode_result","Introducer creation process result:");
addS ("tahoe-lafs_createnode_starting","Starting Tahoe-LAFS storage node:");
addS ("tahoe-lafs_createnode_subtitle","Storage node creation");

//deleteIntroducer
addS ("tahoe-lafs_deleteintroducer_result","Introducer deletion process result:");
addS ("tahoe-lafs_deleteintroducer_subtitle","Introducer deletion");

//deleteNode
addS ("tahoe-lafs_deletenode_result","Storage deletion process result:");
addS ("tahoe-lafs_deletenode_subtitle","Storage node deletion");

//flash
addS ("tahoe-lafs_flash_publishing_introducer","Publishing Tahoe-LAFS introducer...");
addS ("tahoe-lafs_flash_restarting_introducer","Restarting Tahoe-LAFS introducer...");
addS ("tahoe-lafs_flash_restarting_storage","Restarting Tahoe-LAFS storage node...");
addS ("tahoe-lafs_flash_starting_introducer","Starting Tahoe-LAFS introducer...");
addS ("tahoe-lafs_flash_starting_storage","Starting Tahoe-LAFS storage node...");
addS ("tahoe-lafs_flash_stopping_introducer","Stopping Tahoe-LAFS introducer...");
addS ("tahoe-lafs_flash_stopping_storage","Stopping Tahoe-LAFS storage node...");
addS ("tahoe-lafs_flash_unpublishing_introducer","Unpublishing Tahoe-LAFS introducer...");

//index
addS ("tahoe-lafs_index_description1","Tahoe-LAFS is a free and open cloud storage system.");
addS ("tahoe-lafs_index_description2","It distributes your data across multiple servers.");
addS ("tahoe-lafs_index_description3","Even if some of the servers fail or are taken over by an attacker, the entire filesystem continues to function correctly, preserving your privacy and security.");
addS ("tahoe-lafs_index_instructions_1","To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed in the network.");
addS ("tahoe-lafs_index_instructions_2","Click on the button to install Tahoe-LAFS and start creating a storage grid or to join an existing one.");
addS ("tahoe-lafs_index_instructions_3","Click on the buttons to start creating a storage grid or to join an existing one.");
addS ("tahoe-lafs_index_subtitle","A cloud storage system that distributes your data across multiple servers");

//install
addS ("tahoe-lafs_install_details","Installation process details:");
addS ("tahoe-lafs_install_post","Post-installation process details:");
addS ("tahoe-lafs_install_result","Installation process result:");
addS ("tahoe-lafs_install_subtitle","Installation");

//introducer
addS ("tahoe-lafs_introducer_announcement","Service announcement:");
addS ("tahoe-lafs_introducer_FURL","Introducer FURL:");
addS ("tahoe-lafs_introducer_grid","Grid name:");
addS ("tahoe-lafs_introducer_instructions_1","To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed in the network.");
addS ("tahoe-lafs_introducer_instructions_2","Click on the button to set up an introducer on this machine.");
addS ("tahoe-lafs_introducer_instructions_3","After that, storage nodes will be able to join the introducer to deploy the storage grid.");
addS ("tahoe-lafs_introducer_private","The storage grid is private");
addS ("tahoe-lafs_introducer_public","The storage grid is public and is being announced to the community");
addS ("tahoe-lafs_introducer_status","Tahoe-LAFS introducer status:");
addS ("tahoe-lafs_introducer_subtitle","Introducer");
addS ("tahoe-lafs_introducer_web","Introducer web interface:");

//node
addS ("tahoe-lafs_node_FURL","Introducer FURL:");
addS ("tahoe-lafs_node_instructions_1","To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed in the network.");
addS ("tahoe-lafs_node_instructions_2","Click on the button to set up a storage node on this machine and join a storage grid.");
addS ("tahoe-lafs_node_status","Tahoe-LAFS storage node status:");
addS ("tahoe-lafs_node_subtitle","Storage node");
addS ("tahoe-lafs_node_web","Tahoe-LAFS storage node web page (only accessible from localhost):");

//uninstall
addS ("tahoe-lafs_uninstall_details","Uninstallation process details:");
addS ("tahoe-lafs_uninstall_post","Post-uninstallation process details:");
addS ("tahoe-lafs_uninstall_result","Uninstallation process result:");
addS ("tahoe-lafs_uninstall_subtitle","Uninstallation");
?>