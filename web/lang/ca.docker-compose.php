<?php


//alerts
addS("docker_compose_alert_not_installed", "Docker Compose no está instal·lat");
addS("docker_compose_alert_no_projects", "No s'ha trobat cap projecte de Docker Compose");
addS("docker_compose_alert_not_running", "Docker está aturat");
addS("docker_compose_alert_img_not_available", "No hi ha imatges de Docker disponibles");
addS("docker_compose_alert_form_name_blank", "El nom del projecte no pot estar en blanc");
addS("docker_compose_alert_form_name_empty", "No s'ha especificat el nom del projecte");
addS("docker_compose_alert_form_name_invalid", "El nom del projecte no és vàlid");
addS("docker_compose_alert_form_name_inuse", "Ja existeix un altre projecte amb el mateix nom");
addS("docker_compose_alert_form_name_mkdir_fail_pre", "No s'ha pogut crear el directori ");
addS("docker_compose_alert_form_name_mkdir_fail_post", "");
addS("docker_compose_alert_form_dcy_put_fail_pre", "No s'ha pogut escriure al fitxer ");
addS("docker_compose_alert_form_dcy_put_fail_post", "");
addS("docker_compose_alert_form_dcy_blank", "El fitxer docker-compose.yml está buit");
addS("docker_compose_alert_form_dcy_empty", "No s'ha especificat cap contingut per al fitxer docker-compose.yml");
addS("docker_compose_alert_form_dcy_invalid", "El contingut del fitxer docker-compose.yml no és vàlid");
addS("docker_compose_alert_form_project_pre", "El projecte ");
addS("docker_compose_alert_form_project_post", " s'ha creat satisfactòriament");
addS("docker_compose_alert_ps_not_running", "No hi ha contenidors Docker en execució");
addS("docker_compose_alert_ps_not_stopped", "No hi ha contenidors Docker aturats");
addS("docker_compose_alert_running", "Docker está executant-se");
addS("docker_compose_alert_start_message", "S'ha iniciat Docker");
addS("docker_compose_alert_stop_message", "S'ha aturat Docker");


//buttons
addS("docker_compose_button_install", "Instal·la Docker Compose");
addS("docker_compose_button_start", "Inicia");
addS("docker_compose_button_stop", "Atura");
addS("docker_compose_button_image_rmi", "Esborra imatge");
addS("docker_compose_button_image_run", "Inicia imatge");
addS("docker_compose_button_create", "Crea un nou projecte de Docker Compose");
addS("docker_compose_button_clone", "Clona un projecte de Docker Compose");
addS("docker_compose_button_manage", "Gestiona");
addS("docker_compose_button_delete", "Esborra");
addS("docker_compose_button_back", "Enrere");


//flash
addS("docker_compose_flash_restart_pre", "S'está reiniciant el contenidor ");
addS("docker_compose_flash_restart_mid", " (ID: ");
addS("docker_compose_flash_restart_post", ") en segon pla. Això pot trigar uns segons...");
addS("docker_compose_flash_rm_pre", "S'está esborrant el contenidor ");
addS("docker_compose_flash_rm_mid", " (ID: ");
addS("docker_compose_flash_rm_post", ") en segon pla. Això pot trigar uns segons...");
addS("docker_compose_flash_rmi_pre", "S'está esborrant la imatge ");
addS("docker_compose_flash_rmi_mid", "");
addS("docker_compose_flash_rmi_post", " en segon pla. Això pot trigar uns segons...");
addS("docker_compose_flash_run_pre", "S'está iniciant la imatge ");
addS("docker_compose_flash_run_mid", "");
addS("docker_compose_flash_run_post", " en segon pla. Això pot trigar uns segons...");
addS("docker_compose_flash_pull_pre", "S'está obtenint el contenidor ");
addS("docker_compose_flash_pull_post", " en segon pla. Això pot trigar uns segons...");
addS("docker_compose_flash_down_pre", "S'estan aturant tots els contenidors associats al projecte ");
addS("docker_compose_flash_down_post", " en segon pla. Això pot trigar uns segons...");
addS("docker_compose_flash_up_pre", "S'está iniciant el projecte ");
addS("docker_compose_flash_up_post", " en segon pla. Això pot trigar uns segons...");
addS("docker_compose_flash_delete_pre", "S'ha esborrat el projecte ");
addS("docker_compose_flash_delete_post", "");
addS("docker_compose_flash_delete_error_pre", "No s'ha pogut esborrar el directori ");
addS("docker_compose_flash_delete_error_post", "");


//Common
addS("docker_compose_title", "Docker Compose");
addS("docker_compose_desc", "Amb Compose, podeu fer servir fitxers Compose per configurar els serveis de la vostra aplicació. Així, emprant una sola comanda, podeu crear i iniciar tots els serveis a partir de la vostra configuració. Compose és genial per a desenvolupament i entorns de proves pilot, així com per a fluxos de treball d'integració continuada. Podeu llegir més sobre Docker Compose <a href='https://docs.docker.com/compose'>aquí</a>.");
addS("docker_compose_status", "Estat de Docker Compose:");
addS("docker_compose_subtitle", "Una eina per definir i executar aplicacions Docker multicontenidor");


//down
addS("docker_compose_down_desc_pre", "S'estan aturant tots els contenidors associats al projecte ");
addS("docker_compose_down_desc_post", ":");

//up
addS("docker_compose_up_desc_pre", "Contenidors associats al projecte ");
addS("docker_compose_up_desc_post", ":");


//main
addS("docker_compose_info", "Informació de Docker:");
addS("docker_compose_interface", "Interfícies de xarxa de Docker:");
addS("docker_compose_title_containers_running", "Contenidors de Docker en execució:");
addS("docker_compose_title_containers_stopped", "Contenidors de Docker aturats:");
addS("docker_compose_title_images", "Imatges de Docker disponibles:");


//manage
addS("docker_compose_manage_desc_pre", "Empreu aquesta pàgina per gestionar el projecte de Docker Compose ");
addS("docker_compose_manage_desc_post", ".");
addS("docker_compose_manage_containers", "Contenidors Docker associats:");
addS("docker_compose_manage_files", "Fitxers del projecte:");


//create
addS("docker_compose_create_desc", "Empreu aquesta pàgina per crear un projecte de Docker Compose nou.");
addS("docker_compose_create_form_error", "El formulari conté un error:");
addS("docker_compose_create_form_errors", "El formulari conté errors:");
addS("docker_compose_create_form_name", "Nom del projecte");
addS("docker_compose_create_form_name_placeholder", "ElVostreProjecte");
addS("docker_compose_create_form_name_tooltip", "Escolliu un nom per al vostre projecte de Docker Compose. Feu servir només caràcters alfanumèrics i guionets.");
addS("docker_compose_create_form_dcy", "docker-compose.yml");
addS("docker_compose_create_form_dcy_tooltip", "Feu servir el camp de text de dalt per introduir el contingut del vostre fitxer docker-compose.yml.");
