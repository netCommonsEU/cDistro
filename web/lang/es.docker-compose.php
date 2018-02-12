<?php


//alerts
addS("docker_compose_alert_not_installed", "Docker Compose no está instalado");
addS("docker_compose_alert_no_projects", "No se ha encontrado ningún proyecto de Docker Compose");
addS("docker_compose_alert_not_running", "Docker está parado");
addS("docker_compose_alert_img_not_available", "No hay imágenes de Docker disponibles");
addS("docker_compose_alert_form_name_blank", "El nombre del proyecto no puede estar en blanco");
addS("docker_compose_alert_form_name_empty", "No se ha especificado el nombre del proyecto");
addS("docker_compose_alert_form_name_invalid", "El nombre del proyecto no es válido");
addS("docker_compose_alert_form_name_inuse", "Ya existe un proyecto con el mismo nombre");
addS("docker_compose_alert_form_name_mkdir_fail_pre", "No se ha podido crear el directorio ");
addS("docker_compose_alert_form_name_mkdir_fail_post", "");
addS("docker_compose_alert_form_dcy_put_fail_pre", "No se ha podido escribir en el fichero ");
addS("docker_compose_alert_form_dcy_put_fail_post", "");
addS("docker_compose_alert_form_dcy_blank", "El fichero docker-compose.yml está vacío");
addS("docker_compose_alert_form_dcy_empty", "No se ha especificado ningún contenido para el fichero docker-compose.yml");
addS("docker_compose_alert_form_dcy_invalid", "El contenido del fichero docker-compose.yml no es válido");
addS("docker_compose_alert_form_project_pre", "El proyecto ");
addS("docker_compose_alert_form_project_post", " se ha creado satisfactoriamente");
addS("docker_compose_alert_ps_not_running", "No hay contenedores Docker en ejecución");
addS("docker_compose_alert_ps_not_stopped", "No hay contenedores Docker parados");
addS("docker_compose_alert_running", "Docker se está ejecutando");
addS("docker_compose_alert_start_message", "Docker se ha iniciado");
addS("docker_compose_alert_stop_message", "Docker se ha parado");


//buttons
addS("docker_compose_button_install", "Instalar Docker Compose");
addS("docker_compose_button_start", "Iniciar");
addS("docker_compose_button_stop", "Parar");
addS("docker_compose_button_image_rmi", "Borrar imagen");
addS("docker_compose_button_image_run", "Iniciar imagen");
addS("docker_compose_button_create", "Crear un nuevo proyecto de Docker Compose");
addS("docker_compose_button_clone", "Clonar un proyecto de Docker Compose");
addS("docker_compose_button_manage", "Gestionar");
addS("docker_compose_button_delete", "Borrar");
addS("docker_compose_button_back", "Atrás");


//flash
addS("docker_compose_flash_restart_pre", "Se está reiniciando el contenedor ");
addS("docker_compose_flash_restart_mid", " (ID: ");
addS("docker_compose_flash_restart_post", ") en segundo plano. Esto puede tardar unos segundos...");
addS("docker_compose_flash_rm_pre", "Se está borrando el contenedor ");
addS("docker_compose_flash_rm_mid", " (ID: ");
addS("docker_compose_flash_rm_post", ") en segundo plano. Esto puede tardar unos segundos...");
addS("docker_compose_flash_rmi_pre", "Se está borrando la imagen ");
addS("docker_compose_flash_rmi_mid", "");
addS("docker_compose_flash_rmi_post", " en segundo plano. Esto puede tardar unos segundos...");
addS("docker_compose_flash_run_pre", "Se está iniciando la imagen ");
addS("docker_compose_flash_run_mid", "");
addS("docker_compose_flash_run_post", " en segundo plano. Esto puede tardar unos segundos...");
addS("docker_compose_flash_pull_pre", "Se está obteniendo el contenedor ");
addS("docker_compose_flash_pull_post", " en segundo plano. Esto puede tardar unos segundos...");
addS("docker_compose_flash_down_pre", "Se están parando todos los contenedores asociados al proyecto ");
addS("docker_compose_flash_down_post", " en segundo plano. Esto puede tardar unos segundos...");
addS("docker_compose_flash_up_pre", "Se está iniciando el proyecto ");
addS("docker_compose_flash_up_post", " en segundo plano. Esto puede tardar unos segundos...");
addS("docker_compose_flash_delete_pre", "Se ha borrado el proyecto ");
addS("docker_compose_flash_delete_post", "");
addS("docker_compose_flash_delete_error_pre", "No se ha podido borrar el directorio ");
addS("docker_compose_flash_delete_error_post", "");


//Common
addS("docker_compose_title", "Docker Compose");
addS("docker_compose_desc", "Con Compose, puede utilizar ficheros Compose para configurar los servicios de su aplicación. Así, empleando un solo comando, puede crear e iniciar todos los servicios a partir de su configuración. Compose es genial para desarrollo y entornos de pruebas piloto, así como para flujos de trabajo de integración continua. Puede leer más acerca de Docker Compose <a href='https://docs.docker.com/compose'>aquí</a>.");
addS("docker_compose_status", "Estado de Docker Compose:");
addS("docker_compose_subtitle", "Una herramienta para definir y ejecutar aplicaciones Docker multicontenedor");


//down
addS("docker_compose_down_desc_pre", "Se están parando todos los contenedores asociados al proyecto ");
addS("docker_compose_down_desc_post", ":");

//up
addS("docker_compose_up_desc_pre", "Contenedores asociados al proyecto ");
addS("docker_compose_up_desc_post", ":");


//main
addS("docker_compose_info", "Información de Docker:");
addS("docker_compose_interface", "Interfaces de red de Docker:");
addS("docker_compose_title_containers_running", "Contenedores de Docker en ejecución:");
addS("docker_compose_title_containers_stopped", "Contenedores de Docker parados:");
addS("docker_compose_title_images", "Imágenes de Docker disponibles:");


//manage
addS("docker_compose_manage_desc_pre", "Use esta página para gestionar el proyecto de Docker Compose ");
addS("docker_compose_manage_desc_post", ".");
addS("docker_compose_manage_containers", "Contenedores Docker asociados:");
addS("docker_compose_manage_files", "Ficheros del proyecto:");


//create
addS("docker_compose_create_desc", "Use esta página para crear un proyecto de Docker Compose nuevo.");
addS("docker_compose_create_form_error", "El formulario contiene un error:");
addS("docker_compose_create_form_errors", "El formulario contiene errores:");
addS("docker_compose_create_form_name", "Nombre del proyecto");
addS("docker_compose_create_form_name_placeholder", "MiProyecto");
addS("docker_compose_create_form_name_tooltip", "Elija un nombre para su proyecto de Docker Compose. Use solo caracteres alfanuméricos y guiones.");
addS("docker_compose_create_form_dcy", "docker-compose.yml");
addS("docker_compose_create_form_dcy_tooltip", "Use el campo de texto de arriba para introducir el contenido de su fichero docker-compose.yml.");
