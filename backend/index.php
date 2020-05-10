<?php
require_once "models/enlaces.php";
require_once "models/ingreso.php";

require_once "controllers/template.php";
require_once "controllers/enlaces.php";
require_once "controllers/ingreso.php";

// Carga de la plantilla base del back-office
$template = new TemplateController();
$template -> template();

