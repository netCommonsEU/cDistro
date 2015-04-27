<?php
// plug/controllers/guifi-web.php

//alerts
addS ("guifi-web_alert_credentials_security_ssl","Your credentials are sent to Guifi.net's authentication service using SSL encryption");
addS ("guifi-web_alert_credentials_security_username","Your username will be stored in");
addS ("guifi-web_alert_credentials_security_password","Your password will not be stored");
addS ("guifi-web_alert_refresh_credentials_security_ssl","Your credentials are sent to Guifi.net's authentication service using SSL encryption");
addS ("guifi-web_alert_refresh_credentials_security_username","Your username will be stored in");
addS ("guifi-web_alert_refresh_credentials_security_password","Your password will not be stored");
addS ("guifi-web_alert_index_nodeid","This device is registered at Guifi.net");
addS ("guifi-web_alert_index_no_nodeid","This device is not registered at Guifi.net");
addS ("guifi-web_alert_index_not_registered","Device not registered");
addS ("guifi-web_alert_index_initialized","This device is initialized");
addS ("guifi-web_alert_credentials_curl_empty","Empty reply from Guifi.net's remote server");
addS ("guifi-web_alert_credentials_curl_ok","User successfully authenticated");
addS ("guifi-web_alert_credentials_curl_error","An error occurred");
addS ("guifi-web_alert_credentials_curl_wrong_login","Wrong username or password");
addS ("guifi-web_alert_credentials_curl_wrong_command","Wrong command");
addS ("guifi-web_alert_credentials_file_error","The configuration file could not be created");
addS ("guifi-web_alert_credentials_file_empty","The configuration file is empty");
addS ("guifi-web_alert_credentials_file_different","The configuration was not properly saved");
addS ("guifi-web_alert_credentials_file_correct","Configuration file saved");
addS ("guifi-web_alert_credentials_success","The device was successfully initialized");
addS ("guifi-web_alert_credentials_post_empty","The credentials form did not provide any data");
addS ("guifi-web_alert_credentials_post_emptyusername","The credentials form did not provide a username");
addS ("guifi-web_alert_credentials_post_emptypassword","The credentials form did not provide a password");
addS ("guifi-web_alert_missing_nodeid","Missing node ID");
addS ("guifi-web_alert_selectnode_wrong_nodeid_pre","Node ID");
addS ("guifi-web_alert_selectnode_wrong_nodeid_post","is not valid");
addS ("guifi-web_alert_selectnode_post_empty","The device registration form did not provide any data");
addS ("guifi-web_alert_selectnode_post_emptynode","The device registration form did not provide a node ID");
addS ("guifi-web_alert_selectnode_post_found_pre","Node ID");
addS ("guifi-web_alert_selectnode_post_found_post","found");
addS ("guifi-web_alert_selectnode_post_no_devices","No devices found");
addS ("guifi-web_alert_selectdevice_cloudies","Cloudy devices found");
addS ("guifi-web_alert_selectdevice_no_cloudies","No Cloudy devices found");
addS ("guifi-web_alert_new_cloudy_post_success","Device created successfully");
addS ("guifi-web_alert_new_cloudy_post_fail","Device creation failed");
addS ("guifi-web_alert_new_cloudy_post_file_error","The configuration file could not be saved");
addS ("guifi-web_alert_new_cloudy_post_file_correct","Configuration file saved");
addS ("guifi-web_alert_new_cloudy_post_expired","Guifi.net login credentials expired");
addS ("guifi-web_alert_new_cloudy_post_already_in_use","this device name is already in use in this node");


//buttons
addS ("guifi-web_button_back","Back");
addS ("guifi-web_button_back_add","Back to the node creation form");
addS ("guifi-web_button_back_credentials","Back to the credentials form");
addS ("guifi-web_button_initialize","Initialize the device");
addS ("guifi-web_button_register","Register the device");
addS ("guifi-web_button_change_nodeid","Choose another node");
addS ("guifi-web_button_selectnode_continue","Continue with the device registration");
addS ("guifi-web_button_submit_add","Create a new Cloudy device");
addS ("guifi-web_button_submit_check","Check user credentials");
addS ("guifi-web_button_submit_refresh","Refresh user credentials");
addS ("guifi-web_button_submit_nodeid","Check node ID");
addS ("guifi-web_button_back_selectnode","Choose another node");
addS ("guifi-web_button_selectnode_use_node","Use node");
addS ("guifi-web_button_selectdevice_new","Create a new Cloudy device in this node");
addS ("guifi-web_button_view","View details");
addS ("guifi-web_button_view_device","View this device at Guifi.net website");
addS ("guifi-web_button_selectdevice_this","Register with this device");
addS ("guifi-web_button_change_deviceid","Choose another device");
addS ("guifi-web_button_retry","Retry node selection");
addS ("guifi-web_button_assign_ip","Assign an IP address to the device");
addS ("guifi-web_button_obtain_ip","Obtain an IP address from this device");
addS ("guifi-web_button_noip","Continue without assigning an IP address to the device");
addS ("guifi-web_button_change_credentials","Modify saved Guifi.net credentials");
addS ("guifi-web_button_change_device","Modify Cloudy device registration");


//common
addS ("guifi-web_common_title","Guifi.net website integration");
addS ("guifi-web_common_error:","Error found:");
addS ("guifi-web_common_nodename:","Node name:");
addS ("guifi-web_common_nodedescription:","Node description:");
addS ("guifi-web_common_lang","en");
addS ("guifi-web_common_deviceid","Device id");
addS ("guifi-web_common_devicename","Device name");
addS ("guifi-web_common_devicename:","Device name:");
addS ("guifi-web_common_deviceip","IPv4 address (netmask)");
addS ("guifi-web_common_devicetype","Type");
addS ("guifi-web_common_deviceip:","IPv4 address (netmask):");
addS ("guifi-web_common_actions","Available actions");
addS ("guifi-web_common_actions:","Available actions:");
addS ("guifi-web_common_services:","List of services:");
addS ("guifi-web_common_serviceid","Service id");
addS ("guifi-web_common_servicename","Service name");
addS ("guifi-web_common_servicetype","Service type");
addS ("guifi-web_common_noip","No IPv4 address assigned to this device");
addS ("","");
addS ("","");
addS ("","");


//index
addS ("guifi-web_index_description","This page lets you integrate your Cloudy-based device with your user account at Guifi.net.");
addS ("guifi-web_index_not_registered","This Cloudy device is not registered in Guifi.net's website. Please click on the button below to start the process.");
addS ("guifi-web_index_status","Cloudy device status:");
addS ("guifi-web_index_subtitle","Integration of Cloudy devices with Guifi.net's website");
addS ("guifi-web_index_information","Cloudy device information:");

//credentials
addS ("guifi-web_credentials_curl_empty","The remote server at Guifi.net could not be contacted, or it provided an empty reply. Please check your Internet connection and retry.");
addS ("guifi-web_credentials_curl_error",'The remote server reported an error. Please go back to the previous form to retry.');
addS ("guifi-web_credentials_curl_authresult","Authentication result:");
addS ("guifi-web_credentials_curl_details","Response details:");
addS ("guifi-web_credentials_curl_url","URL called:");
addS ("guifi-web_credentials_curl_wrong_command",'A wrong command was sent to the remote server. Please go back to the previous form to retry.');
addS ("guifi-web_credentials_curl_wrong_login",'Please go back to the previous form to retry, or <a href="http://guifi.net/en/user/password">click here</a> to reset your password.');
addS ("guifi-web_credentials_description",'To register this Cloudy device you need to provide your Guifi.net login credentials using the form below. If you do not have an account at Guifi.net\'s website you can <a href="http://guifi.net/en/user/register">create it here</a>.');
addS ("guifi-web_credentials_register","Your credentials have been successfully verified and the configuration has been saved. Click on the button below to continue the registration process of your Cloudy device at Guifi.net.");
addS ("guifi-web_credentials_file_error","The configuration file could not be created. Check if you have free space in disk and sufficient permissions and retry.");
addS ("guifi-web_credentials_file_empty","The configuration file was created but could not be written. Check if you have free space in disk and sufficient permissions and retry.");
addS ("guifi-web_credentials_file_different","The configuration file was created but the data was not successfully saved. Check if you have free space in disk and sufficient permissions and retry.");
addS ("guifi-web_credentials_form_password","Password");
addS ("guifi-web_credentials_form_password_tooltip","Your passowrd at Guifi.net's website");
addS ("guifi-web_credentials_form_username","Username");
addS ("guifi-web_credentials_form_username_example","my_username_123");
addS ("guifi-web_credentials_form_username_tooltip","Your username at Guifi.net's website");
addS ("guifi-web_credentials_post_empty","The credentials form did not provide any data. Please go back to the previous page and retry.");
addS ("guifi-web_credentials_post_emptyusername","The credentials form did not provide a username, or it was empty. Please go back to the previous page and retry.");
addS ("guifi-web_credentials_post_emptypassword","The credentials form did not provide a password, or it was empty. Please go back to the previous page and retry.");
addS ("guifi-web_credentials_security","Security notice:");
addS ("guifi-web_credentials_subtitle","User credentials");
addS ("guifi-web_credentials_saving","Saving configuration file:");

//refresh_credentials
addS ("guifi-web_refresh_credentials_description",'The Guifi.net login credentials stored in this Cloudy device have expired. Please refresh them by entering your password below.');
addS ("guifi-web_refresh_credentials_success","Your credentials have been successfully refreshed.");
addS ("guifi-web_refresh_credentials_form_password","Password");
addS ("guifi-web_refresh_credentials_form_password_tooltip","Your passowrd at Guifi.net's website");
addS ("guifi-web_refresh_credentials_form_username","Username");
addS ("guifi-web_refresh_credentials_form_username_tooltip","Your username at Guifi.net's website");
addS ("guifi-web_refresh_credentials_security","Security notice:");
addS ("guifi-web_refresh_credentials_subtitle","Refresh user credentials");

//selectnode
addS ("guifi-web_selectnode_subtitle","Cloudy device registration");
addS ("guifi-web_selectnode_description","To register this Cloudy device you need to place it in an existing Guifi.net node (i.e. a location). Please provide the node's ID using the form below.");
addS ("guifi-web_selectnode_form_nodeid","Guifi node ID");
addS ("guifi-web_selectnode_form_nodeid_tooltip","The ID of the Guifi.net node this Cloudy device belongs to.");
addS ("guifi-web_selectnode_curl_noderesult", "Node ID check result:");
addS ("guifi-web_selectnode_wrong_node", "The provided node ID does not seem to correspond to a valid Guifi.net node. Please go back to the previous page and retry.");
addS ("guifi-web_selectnode_post_empty","The device registration form did not provide any data. Please go back to the previous page and retry.");
addS ("guifi-web_selectnode_post_emptynode","The device registration form did not provide any node ID. Please go back to the previous page and retry.");
addS ("guifi-web_selectnode_post_nodedetails","Node details:");
addS ("guifi-web_alert_selectnode_post_nodetext_pre","The information above corresponds to node ID");
addS ("guifi-web_alert_selectnode_post_nodetext_post",". Click on the buttons below to continue and register your Cloudy device in this node, or to select another node.");


addS ("guifi-web_selectnode_post_no_devices","This node has no devices. Click on the button below to register the current Cloudy instance as a new device in this node");





//selectdevice
addS ("guifi-web_selectdevice_subtitle","Cloudy device registration");
addS ("guifi-web_selectdevice_missing_nodeid","The node ID could not be found. Click on the button below to choose a new node ID.");
addS ("guifi-web_selectdevice_description_pre","Use this page to link this Cloudy device to an existing one or to create a new Cloudy device in node ");
addS ("guifi-web_selectdevice_description_post",".");
addS ("guifi-web_selectdevices_nodedevices","Devices in this node:");
addS ("guifi-web_selectdevice_id","Device id");
addS ("guifi-web_selectdevice_name","Device name ");
addS ("guifi-web_selectdevice_actions","Available actions");
addS ("guifi-web_selectdevice_cloudies_pre", "One or more previously registered Cloudy devices were found in node");
addS ("guifi-web_selectdevice_cloudies_post", ". If the current Cloudy instance corresponds to any of them, click on the corresponding button above to link them. Otherwise, click on the button below to register the current Cloudy instance as a new device in this node.");
addS ("guifi-web_selectdevice_no_cloudies_pre", "No previously registered Cloudy devices were found in node");
addS ("guifi-web_selectdevice_no_cloudies_post", ". Click on the button below to register the current Cloudy instance as a new device in this node.");

//new_cloudy
addS ("guifi-web_new_cloudy_subtitle","Add a new Cloudy device to a node");
addS ("guifi-web_new_cloudy_description","Fill in the required information below o create a new Cloudy device in your Guifi.net node:");
addS ("guifi-web_new_cloudy_form_nodeid","Guifi.net node ID");
addS ("guifi-web_new_cloudy_form_nodeid_tooltip","The ID of the Guifi.net node where the new Cloudy device will be created.");
addS ("guifi-web_new_cloudy_form_nick","Cloudy device name");
addS ("guifi-web_new_cloudy_form_nick_tooltip","A short name to identify the new Cloudy device, like the node's name plus \"Cloudy\". Use alphanumeric characters, dashes and underscores (no spaces or punctuation)");
addS ("guifi-web_new_cloudy_form_mail","Contact e-mail");
addS ("guifi-web_new_cloudy_form_mail_tooltip","An e-mail to be displayed as the contact address of the owner of the device. It will only be shown to users registered at Guifi.net");
addS ("guifi-web_new_cloudy_form_mail_placeholder","yourname@guifi.net");
addS ("guifi-web_new_cloudy_form_mac","MAC address");
addS ("guifi-web_new_cloudy_form_mac_tooltip","The MAC address of the network interface of this device connected to the community network (optional, you can leave the default one)");
addS ("guifi-web_new_cloudy_form_comment","Node description");
addS ("guifi-web_new_cloudy_form_comment_tooltip","A description of this device (optional, you can leave it blank)");
addS ("guifi-web_new_cloudy_form_comment_placeholder","My rocking Cloudy machine");
addS ("guifi-web_new_cloudy_result","New device creation result:");
addS ("guifi-web_new_cloudy_details","New device creation details:");
addS ("guifi-web_new_cloudy_deviceid","New device ID:");
addS ("guifi-web_new_cloudy_success","A new Cloudy device was successfully created in your node at Guifi.net. The current Cloudy instance has been registered to this new device. To finish the registration process, click on the button below to assign an IP address to the newly created device.");
addS ("guifi-web_new_cloudy_saving","Saving configuration file:");
addS ("guifi-web_new_cloudy_file_error","The configuration file could not be saved. Check if you have free space in disk and sufficient permissions and retry.");
addS ("guifi-web_new_cloudy_fail","The creation of the new Cloudy device failed. Check the details above for more information and click on the button below to retry.");
addS ("guifi-web_new_cloudy_credentials_expired","The Guifi.net login credentials stored in this Cloudy device have expired. To refresh them, please click on the button below.");
addS ("guifi-web_new_cloudy_post_already_in_use","The device name chosen is already in use in this node. To use another one, please click on the button below.");

// _getNodeInformation
addS ("guifi_services_did_not_define_in_this_device","There are no services registered with this device.");

// assign
addS ("guifi-web_assign_title","Assign an IP address to the Cloudy device");
addS ("guifi-web_assign_description","This page lets you assign an IP address to this Cloudy device. Below is a list with the devices in the node that can provide an IP address to the device. Select one of them to obtain an IP address and assign it to the Cloudy device:");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");
addS ("","");

?>