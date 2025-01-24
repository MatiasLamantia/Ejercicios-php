<?php

/*
 * Datos de conexión con la base de datos.
 * Debe completarse con el DSN, usuario y password para conectar a la base de datos.
 */
define ('DB_DSN','mysql:host=localhost;dbname=pedidos');
define ('DB_USER','root');
define ('DB_PASSWD',''); 

define ('REGEX_VALIDATE_FECHA',['options'=>['regexp'=>'/^([0-9]{4}-([0][1-9]|[1][012])-(0[1-9]|[12][0-9]|[3][01]))$/']]);