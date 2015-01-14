<?php
// plug/controllers/tahoe-lafs.php

//alerts
addS ("otahoe-lafs_running","Tahoe-LAFS storage node running");





//buttons
addS ("otahoe-lafs_button_install","Install Tahoe-LAFS");






//flash
addS ("otahoe-lafs_flash_starting_introducer","Starting Tahoe-LAFS introducer...");
addS ("otahoe-lafs_flash_stopping_introducer","Stopping Tahoe-LAFS introducer...");
addS ("otahoe-lafs_flash_restarting_introducer","Restarting Tahoe-LAFS introducer...");
addS ("otahoe-lafs_flash_starting_storage","Starting Tahoe-LAFS storage node...");
addS ("otahoe-lafs_flash_stopping_storage","Stopping Tahoe-LAFS storage node...");
addS ("otahoe-lafs_flash_restarting_storage","Restarting Tahoe-LAFS storage node...");
addS ("otahoe-lafs_flash_publishing_introducer","Publishing Tahoe-LAFS introducer...");
addS ("otahoe-lafs_flash_unpublishing_introducer","Unpublishing Tahoe-LAFS introducer...");









//index
addS ("otahoe-lafs_title","Tahoe-LAFS");
addS ("otahoe-lafs_subtitle","A cloud storage system that distributes your data across multiple servers");
addS ("otahoe-lafs_description1","Tahoe-LAFS is a free and open cloud storage system.");
addS ("otahoe-lafs_description2","It distributes your data across multiple servers.");
addS ("otahoe-lafs_description3","Even if some of the servers fail or are taken over by an attacker, the entire filesystem continues to function correctly, preserving your privacy and security.");
addS ("otahoe-lafs_status:","Tahoe-LAFS status:");
addS ("otahoe-lafs_not_installed","Tahoe-LAFS is not installed on this machine");
addS ("otahoe-lafs_instructions_1","To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.");
addS ("otahoe-lafs_instructions_3","Click on the buttons to start creating a storage grid or to join an existing one.");
addS ("otahoe-lafs_instructions_2","Click on the button to install Tahoe-LAFS and start creating a storage grid or to join an existing one.");






addS ("otahoe-lafs_installed_not_configured","Tahoe-LAFS is installed on this machine but has not been configured yet");
addS ("Create an introducer and start a storage grid","Create an introducer and start a storage grid");
addS ("Create a storage node to join a storage grid","Create a storage node to join a storage grid");
addS ("Uninstall Tahoe-LAFS","Uninstall Tahoe-LAFS");
addS ("Tahoe-LAFS introducer is running","Tahoe-LAFS introducer is running");
addS ("Tahoe-LAFS introducer is stopped","Tahoe-LAFS introducer is stopped");
addS ("Tahoe-LAFS storage node is running","Tahoe-LAFS storage node is running");
addS ("Tahoe-LAFS storage node is stopped","Tahoe-LAFS storage node is stopped");
addS ("Manage introducer","Manage introducer");
addS ("Manage storage node","Manage storage node");
addS ("Create a storage node","Create a storage node");

//install
addS ("otahoe-lafs_installation","Installation");
addS ("otahoe-lafs_installation_details:","Installation process result:");
addS ("otahoe-lafs_installation_already","Tahoe-LAFS is already installed");
addS ("otahoe-lafs_installation_information","Tahoe-LAFS installation information:");
addS ("otahoe-lafs_button_back","Back to Tahoe-LAFS");
addS ("otahoe-lafs_installation_successful","Tahoe-LAFS has been successfully installed");
addS ("otahoe-lafs_installation_details","Installation process details:");
addS ("Post-installation process details:","Post-installation process details:");
addS ("Tahoe-LAFS installation failed","Tahoe-LAFS installation failed");
addS ("Retry installation","Retry installation");
//uninstall
addS ("otahoe-lafs_uninstallation","Uninstallation");
addS ("otahoe-lafs_uninstallation_result","Uninstallation process result:");
addS ("otahoe-lafs_uninstallation_details","Uninstallation process details:");
addS ("otahoe-lafs_alert_currently_uninstalled","Tahoe-LAFS is currently uninstalled");
addS ("otahoe-lafs_uninstallation_introducer","A Tahoe-LAFS introducer is currently configured.");
addS ("otahoe-lafs_alert_stop_uninstall","Stop it and remove it before uninstalling Tahoe-LAFS.");
addS ("Manage Tahoe-LAFS introducer","Manage Tahoe-LAFS introducer");
addS ("A Tahoe-LAFS node is currently configured.","A Tahoe-LAFS node is currently configured.");
addS ("Tahoe-LAFS uninstallation failed","Tahoe-LAFS uninstallation failed");
addS ("otahoe-lafs_uninstallation_button_retry","Retry uninstallation");
addS ("otahoe-lafs_uninstallation_uninstallation","Tahoe-LAFS has been successfully uninstalled");
addS ("otahoe-lafs_uninstallation_post","Post-uninstallation process details:");
//introducer
addS ("Introducer","Introducer");
addS ("Tahoe-LAFS introducer is currently not created","Tahoe-LAFS introducer is currently not created");
addS ("Click on the button to set up an introducer on this machine.","Click on the button to set up an introducer on this machine.");
addS ("After that, storage nodes will be able to join the introducer to deploy the storage grid.","After that, storage nodes will be able to join the introducer to deploy the storage grid.");
addS ("Tahoe-LAFS introducer status:","Tahoe-LAFS introducer status:");
addS ("Tahoe-LAFS introducer is running","Tahoe-LAFS introducer is running");
addS ("Grid name:","Grid name:");
addS ("Introducer FURL:","Introducer FURL:");
addS ("Checking Tahoe-LAFS introducer status...","Checking Tahoe-LAFS introducer status...");
addS ("Stop Tahoe-LAFS introducer","Stop Tahoe-LAFS introducer");
addS ("Make this introducer private","Make this introducer private");
addS ("Make this introducer public","Make this introducer public");
addS ("Delete Tahoe-LAFS introducer","Delete Tahoe-LAFS introducer");
addS ("Start Tahoe-LAFS introducer","Start Tahoe-LAFS introducer");
addS ("Tahoe-LAFS introducer is stopped","Tahoe-LAFS introducer is stopped");
addS ("Tahoe-LAFS introducer is running","Tahoe-LAFS introducer is running");
addS ("The storage grid is public and is being announced via Avahi","The storage grid is public and is being announced via Avahi");
addS ("The storage grid is private","The storage grid is private");
addS ("Tahoe-LAFS introducer web page:","Tahoe-LAFS introducer web page:");
addS ("Service announcement:","Service announcement:");
//createIntroducer
addS ("Introducer creation","Introducer creation");
addS ("Tahoe-LAFS introducer is already created","Tahoe-LAFS introducer is already created");
addS ("Use this page to set up an introducer on this machine and start a storage grid.","Use this page to set up an introducer on this machine and start a storage grid.");
addS ("Grid name:","Grid name:");
addS ("Grid name","Grid name");
addS ("Create introducer","Create introducer");
addS ("A short name to identify the storage grid.","A short name to identify the storage grid.");
addS ("Introducer name","Introducer name");
addS ("A short nickname to identify the introducer in the storage grid.","A short nickname to identify the introducer in the storage grid.");
addS ("Web port","Web port");
addS ("The port where the introducer's web management interface will run on.","The port where the introducer's web management interface will run on.");
addS ("Folder","Folder");
addS ("The introducer will be installed in this folder.","The introducer will be installed in this folder.");
addS ("Public","Public");
addS ("Announce the introducer service through Avahi and allow storage nodes to join the grid.","Announce the introducer service through Avahi and allow storage nodes to join the grid.");
//createIntroducer_post
addS ("Tahoe-LAFS introducer creation failed","Tahoe-LAFS introducer creation failed");
addS ("Incorrect request parameters received","Incorrect request parameters received");
addS ("Retry introducer creation","Retry introducer creation");
addS ("Invalid storage grid name","Invalid storage grid name");
addS ("Invalid introducer name","Invalid introducer name");
addS ("Introducer creation process result:","Introducer creation process result:");
addS ("Invalid introducer web port number number","Invalid introducer web port number number");
addS ("A maximum of 80 alphanumeric characters, dashes and underscores are allowed in the names","A maximum of 80 alphanumeric characters, dashes and underscores are allowed in the names");
addS ("Tahoe-LAFS introducer successfully created","Tahoe-LAFS introducer successfully created");
addS ("Tahoe-LAFS introducer creation failed","Tahoe-LAFS introducer creation failed");
addS ("Starting Tahoe-LAFS introducer:","Starting Tahoe-LAFS introducer:");
addS ("Tahoe-LAFS introducer successfully started","Tahoe-LAFS introducer successfully started");
addS ("Tahoe-LAFS introducer start failed","Tahoe-LAFS introducer start failed");
//node
addS ("Storage node","Storage node");
addS ("Tahoe-LAFS storage node is currently not created","Tahoe-LAFS storage node is currently not created");
addS ("Click on the button to set up a storage node on this machine and join a storage grid.","Click on the button to set up a storage node on this machine and join a storage grid.");
addS ("Checking Tahoe-LAFS storage node status...","Checking Tahoe-LAFS storage node status...");
addS ("Tahoe-LAFS storage node status:","Tahoe-LAFS storage node status:");
addS ("Tahoe-LAFS storage node is running","Tahoe-LAFS storage node is running");
addS ("Stop Tahoe-LAFS storage node","Stop Tahoe-LAFS storage node");
addS ("Tahoe-LAFS storage node is stopped","Tahoe-LAFS storage node is stopped");
addS ("Start Tahoe-LAFS storage node","Start Tahoe-LAFS storage node");
addS ("Delete Tahoe-LAFS storage node","Delete Tahoe-LAFS storage node");
addS ("Tahoe-LAFS storage node web page (only accessible from localhost):","Tahoe-LAFS storage node web page (only accessible from localhost):");
//createNode_get
addS ("Use this page to set up a storage node and join a storage grid.","Use this page to set up a storage node and join a storage grid.");
addS ("Storage node name","Storage node name");
addS ("A short nickname to identify the storage node in the grid.","A short nickname to identify the storage node in the grid.");
addS ("Introducer FURL","Introducer FURL");
addS ("The introducer's FURL of the storage grid you want to join.","The introducer's FURL of the storage grid you want to join.");
addS ("This value has been obtained from the information published by the introducer via Avahi.","This value has been obtained from the information published by the introducer via Avahi.");
addS ("If you want to modify this field, please go back to the main Tahoe-LAFS page and manually create a storage node.","If you want to modify this field, please go back to the main Tahoe-LAFS page and manually create a storage node.");
addS ("The default value has been obtained from the introducer running on this host.","The default value has been obtained from the introducer running on this host.");
addS ("If you want to join another introducer, please modify this field accordingly.","If you want to join another introducer, please modify this field accordingly.");
addS ("Folder","Folder");
addS ("The installation path for the storage node.","The installation path for the storage node.");
addS ("Create storage node","Create storage node");
addS ("Storage node name:","Storage node name:");
addS ("Tahoe-LAFS storage node is already created","Tahoe-LAFS storage node is already created");
addS ("Tahoe-LAFS storage node is running","Tahoe-LAFS storage node is running");
addS ("Tahoe-LAFS storage node is stopped","Tahoe-LAFS storage node is stopped");
addS ("Storage node creation","Storage node creation");
//createNode_post
addS ("Node creation process result:","Node creation process result:");
addS ("Tahoe-LAFS node successfully created","Tahoe-LAFS node successfully created");
addS ("Tahoe-LAFS node creation failed","Tahoe-LAFS node creation failed");
addS ("Starting Tahoe-LAFS node:","Starting Tahoe-LAFS node:");
addS ("Tahoe-LAFS node successfully started","Tahoe-LAFS node successfully started");
addS ("Tahoe-LAFS node start failed","Tahoe-LAFS node start failed");
addS ("Start storage node","Start storage node");
//deleteIntroducer
addS ("Introducer deletion","Introducer deletion");
addS ("Tahoe-LAFS introducer is not created","Tahoe-LAFS introducer is not created");
addS ("Tahoe-LAFS introducer is not stopped","Tahoe-LAFS introducer is not stopped");
addS ("Introducer deletion process result:","Introducer deletion process result:");
addS ("Tahoe-LAFS introducer deletion failed","Tahoe-LAFS introducer deletion failed");
addS ("Tahoe-LAFS introducer successfully deleted","Tahoe-LAFS introducer successfully deleted");
//deleteNode
addS ("Storage node deletion","Storage node deletion");
addS ("Storage deletion process result:","Storage deletion process result:");
addS ("Tahoe-LAFS storage node successfully deleted","Tahoe-LAFS storage node successfully deleted");



?>