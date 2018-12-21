<?php


//alerts
addS("docker_compose_alert_not_installed", "Docker Compose is not installed");
addS("docker_compose_alert_no_projects", "No Docker Compose projects found");
addS("docker_compose_alert_not_running", "Docker is stopped");
addS("docker_compose_alert_img_not_available", "No Docker images available");
addS("docker_compose_alert_form_name_blank", "The project name can not be blank");
addS("docker_compose_alert_form_name_empty", "No project name was submitted");
addS("docker_compose_alert_form_name_invalid", "The project name is not valid");
addS("docker_compose_alert_form_name_inuse", "A project with the same name already exists");
addS("docker_compose_alert_form_name_mkdir_fail_pre", "The directory ");
addS("docker_compose_alert_form_name_mkdir_fail_post", " could not be created");
addS("docker_compose_alert_form_dcy_put_fail_pre", "The file ");
addS("docker_compose_alert_form_dcy_put_fail_post", " could not be written to");
addS("docker_compose_alert_form_dcy_blank", "The docker-compose.yml file content is empty");
addS("docker_compose_alert_form_dcy_empty", "No docker-compose.yml file content was submitted");
addS("docker_compose_alert_form_dcy_invalid", "The docker-compose.yml file content is not valid");
addS("docker_compose_alert_form_project_pre", "Project ");
addS("docker_compose_alert_form_project_post", " successfully created");
addS("docker_compose_alert_ps_not_running", "No running Docker containers");
addS("docker_compose_alert_ps_not_stopped", "No stopped Docker containers");
addS("docker_compose_alert_running", "Docker is running");
addS("docker_compose_alert_start_message", "Docker has been started");
addS("docker_compose_alert_stop_message", "Docker has been stopped");


//buttons
addS("docker_compose_button_install", "Install Docker Compose");
addS("docker_compose_button_start", "Start");
addS("docker_compose_button_stop", "Stop");
addS("docker_compose_button_image_rmi", "Remove image");
addS("docker_compose_button_image_run", "Run image");
addS("docker_compose_button_create", "Create a new Docker Compose project");
addS("docker_compose_button_clone", "Clone a Docker Compose project");
addS("docker_compose_button_manage", "Manage");
addS("docker_compose_button_delete", "Delete");
addS("docker_compose_button_back", "Back");


//flash
addS("docker_compose_flash_restart_pre", "Restarting container ");
addS("docker_compose_flash_restart_mid", " (ID: ");
addS("docker_compose_flash_restart_post", ") in the background. This may take some seconds...");
addS("docker_compose_flash_rm_pre", "Removing container ");
addS("docker_compose_flash_rm_mid", " (ID: ");
addS("docker_compose_flash_rm_post", ") in the background. This may take some seconds...");
addS("docker_compose_flash_rmi_pre", "Removing image ");
addS("docker_compose_flash_rmi_mid", "");
addS("docker_compose_flash_rmi_post", " in the background. This may take some seconds...");
addS("docker_compose_flash_run_pre", "Launching image ");
addS("docker_compose_flash_run_mid", "");
addS("docker_compose_flash_run_post", " in the background. This may take some seconds...");
addS("docker_compose_flash_pull_pre", "Pulling container ");
addS("docker_compose_flash_pull_post", " in the background. This may take some seconds...");
addS("docker_compose_flash_down_pre", "Stopping all containers associated to project ");
addS("docker_compose_flash_down_post", " in the background. This may take some seconds...");
addS("docker_compose_flash_up_pre", "Starting project ");
addS("docker_compose_flash_up_post", " in the background. This may take some seconds...");
addS("docker_compose_flash_delete_pre", "Project ");
addS("docker_compose_flash_delete_post", " has been deleted.");
addS("docker_compose_flash_delete_error_pre", "The directory ");
addS("docker_compose_flash_delete_error_post", " could not be deleted.");


//Common
addS("docker_compose_title", "Docker Compose");
addS("docker_compose_desc", "With Compose, you use a Compose file to configure your application's services. Then, using a single command, you create and start all the services from your configuration. Compose is great for development, testing, and staging environments, as well as continuous integration workflows. You can read more about Docker Compose <a href='https://docs.docker.com/compose'>here</a>.");
addS("docker_compose_status", "Docker Compose status:");
addS("docker_compose_subtitle", "A tool for defining and running multi-container Docker applications");


//down
addS("docker_compose_down_desc_pre", "Stopping all containers associated to project ");
addS("docker_compose_down_desc_post", ":");

//up
addS("docker_compose_up_desc_pre", "Containers associated to project ");
addS("docker_compose_up_desc_post", ":");


//main
addS("docker_compose_info", "Docker info:");
addS("docker_compose_interface", "Docker network interface:");
addS("docker_compose_title_containers_running", "Running Docker containers:");
addS("docker_compose_title_containers_stopped", "Stopped Docker containers:");
addS("docker_compose_title_images", "Available Docker images:");


//manage
addS("docker_compose_manage_desc_pre", "Use this page to manage the ");
addS("docker_compose_manage_desc_post", " Docker Compose project.");
addS("docker_compose_manage_containers", "Associated Docker containers:");
addS("docker_compose_manage_files", "Project files:");


//create
addS("docker_compose_create_desc", "Use this page to create a new Docker Compose project.");
addS("docker_compose_create_form_error", "The form contains an error:");
addS("docker_compose_create_form_errors", "The form contains errors:");
addS("docker_compose_create_form_name", "Project name");
addS("docker_compose_create_form_name_placeholder", "YourProject");
addS("docker_compose_create_form_name_tooltip", "Choose a name for your Docker Compose project. Use only alphanumeric characters and dashes.");
addS("docker_compose_create_form_dcy", "docker-compose.yml");
addS("docker_compose_create_form_dcy_tooltip", "Use the text area above to enter the contents of your docker-compose.yml file.");
