<?php


//alerts
addS ("docker_addsources_available", "Docker is available for installation");
addS ("docker_addsources_not_available", "Docker is not available for installation");
addS ("docker_alert_installed","Docker is installed");
addS ("docker_alert_not_installed","Docker is not installed");
addS ("docker_alert_not_running","Docker is stopped");
addS ("docker_alert_img_not_available","No Docker images available");
addS ("docker_alert_no_sources","Missing APT sources file for Docker package");
addS ("docker_alert_ps_not_running","No running Docker containers");
addS ("docker_alert_ps_not_stopped","No stopped Docker containers");
addS ("docker_alert_running","Docker is running");
addS ("docker_alert_start_message","Docker has been started");
addS ("docker_alert_stop_message","Docker has been stopped");


//buttons
addS ("docker_button_add_sources","Add Docker APT sources file");
addS ("docker_button_add_sources_retry","Retry adding Docker APT sources file");
addS ("docker_button_back","Back");
addS ("docker_button_install","Install Docker (docker-ce)");
addS ("docker_button_restart","Restart Docker");
addS ("docker_button_search","Search Docker containers");
addS ("docker_button_start","Start Docker");
addS ("docker_button_stop","Stop Docker");
addS ("docker_button_uninstall","Uninstall Docker (docker-ce)");
addS ("docker_button_container_pull","Pull image");
addS ("docker_button_container_stop","Stop container");
addS ("docker_button_container_rm","Remove container");
addS ("docker_button_container_restart","Restart container");
addS ("docker_button_image_rmi","Remove image");
addS ("docker_button_image_run","Run image");




//flash
addS ("docker_flash_restart_pre","Restarting container ");
addS ("docker_flash_restart_mid"," (ID: ");
addS ("docker_flash_restart_post",") in the background. This may take some seconds...");
addS ("docker_flash_rm_pre","Removing container ");
addS ("docker_flash_rm_mid"," (ID: ");
addS ("docker_flash_rm_post",") in the background. This may take some seconds...");
addS ("docker_flash_rmi_pre","Removing image ");
addS ("docker_flash_rmi_mid","");
addS ("docker_flash_rmi_post"," in the background. This may take some seconds...");
addS ("docker_flash_run_pre","Launching image ");
addS ("docker_flash_run_mid","");
addS ("docker_flash_run_post"," in the background. This may take some seconds...");
addS ("docker_flash_pull_pre","Pulling container ");
addS ("docker_flash_pull_post"," in the background. This may take some seconds...");
addS ("docker_flash_stop_pre","Stopping container ");
addS ("docker_flash_stop_mid"," (ID: ");
addS ("docker_flash_stop_post",") in the background. This may take some seconds...");



//Common
addS ("docker_title","Docker");
addS ("docker_desc",'Docker is an open-source project that automates the deployment of applications inside software containers, by providing an additional layer of abstraction and automation of operating-system-level virtualization.');
addS ("docker_status","Docker status:");
addS ("docker_subtitle","Automated deployment of Linux applications inside software containers");
addS ("docker_container_stopped","stopped");
addS ("docker_container_running","running");




//search
addS ("docker_search_subtitle","Search and install Docker containers");
addS ("docker_search_desc",'Use the form below to search at <a href="https://hub.docker.com/">Docker Hub</a> for available Docker containers and deploy them. The search is performed on-line.');
addS ("docker_search_form_automated",'Automated');
addS ("docker_search_form_automated_tooltip",'Search only for <a href="https://docs.docker.com/docker-cloud/builds/automated-build/">automatically built</a> Docker images.');
addS ("docker_search_form_search",'Search text');
addS ("docker_search_form_search_tooltip",'Type the name (or part of it) of a Docker container, a repository or an application to search for it. Use only lowercase and numbers.');
addS ("docker_search_header_name",'Name');
addS ("docker_search_header_description",'Description');
addS ("docker_search_header_stars",'Stars');
addS ("docker_search_header_official",'Official');
addS ("docker_search_header_automated",'Automated');
addS ("docker_search_header_action",'Action');
addS ("docker_search_yes",'Yes');



//main
addS ("docker_info","Docker info:");
addS ("docker_interface","Docker network interface:");
addS ("docker_title_containers_running", "Running Docker containers:");
addS ("docker_title_containers_stopped", "Stopped Docker containers:");
addS ("docker_title_images", "Available Docker images:");
addS ("docker_not_installed","Docker is not installed.");
addS ("docker_installed","Docker is installed.");
addS ("docker_button_install","Install Docker (Community Edition)");
addS ("docker_not_running","Docker is stopped.");
addS ("docker_start","Start Docker.");
addS ("docker_remove","Remove Docker.");
addS ("docker_running","Docker is running.");
addS ("docker_sources_manual", 'The APT sources file for the Docker package is missing. Click on the button below to add it automatically or follow the instructions at <a href="https://docs.docker.com/engine/installation/linux/docker-ce/debian/">https://docs.docker.com/engine/installation/linux/docker-ce/debian/</a> to do it manually.');
addS ("docker_stop","Stop Docker.");
addS ("docker_start_message","Docker started.");
addS ("docker_stop_message","Docker stopped.");


//addsources
addS ("docker_addsources_update", "Updating package list...");
addS ("docker_addsources_install_https", "Installing HTTPS transport for APT and other required packages...");
addS ("docker_addsources_dockerlist_pre", "Adding Docker packages repository to ");
addS ("docker_addsources_dockerlist_post", ":");
addS ("docker_addsources_aptkey", "Importing Docker's official GPG key to the local keyring:");
addS ("docker_addsources_update_again", "Updating package list again...");
addS ("docker_addsources_result", "Process result:");
