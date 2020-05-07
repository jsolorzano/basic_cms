<?php

//~ require_once "models/enlaces.php";
//~ require_once "models/crud.php";
require_once "controllers/template.php";

// Carga de la plantilla base del front-office
$template = new TemplateController();
$template -> template();

