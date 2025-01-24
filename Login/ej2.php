<?php
require_once __DIR__.'/vendor/autoload.php';

//Indicamos que vamos a usar la clase Logger del espacio de nombres Monolog
use \Monolog\Logger; 
//Indicamos que vamos a usar la clase StreamHandler del espacio de nombres Monolog\Handler
use \Monolog\Handler\StreamHandler;

// Creamos un Logger con el nombre de canal "Eventos de usuario"
$log = new Logger('Eventos de usuario');
/* Indicamos que los logs se van a guardar en un archivo llamado eventos.log en el 
   mismo directorio de la aplicación (lo normal es que esté en otro directorio, pero 
   como estamos aprendiendo lo vamos a dejar aquí) */
$log->pushHandler(new StreamHandler(__DIR__.'/eventos.log', Logger::DEBUG));
// Añadimos un mensaje de warning
$log->info('Iniciado el script');
$log->warning('Registramos un warning'); 
$log->error('Registramos un error');
?>
Los eventos del log se guardarán en : <?=__DIR__.'/eventos.log'?>