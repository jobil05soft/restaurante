<?php

// Inicias
define('APP_NAME',          'RESTAURANTE - CERVEJARIA KISSANGA'); // Nome do sistema ou da empresa
define('APP_',              'RESTAURANTE KISSANGA'); // Nome do sistema ou da empresa
define('APP_VERSION',       '0.0.1');
define('BASE_URL',          'http://localhost/Restaurente/public/'); // link inicial

// DATABASE
define('MYSQL_SERVER',      'localhost');
define('MYSQL_DATABASE',    'db_restaurante');
define('MYSQL_USER',        'root');
define('MYSQL_PASS',        '');
define('MYSQL_CHARSET',     'utf8');

define('STATUS',            ['EM PROCESSAMENTO', 'CONFIRMADA', 'ENVIADA','CANCELADA','CONCLUIDA']);
define('STATUS_RESERVA',    ['EXPIRADA','REJEITADO', 'CONFIRMADA', 'CANCELADA', 'CONCLUIDA']);

//EMAIL
define('EMAIL_HOST',     'smtp.gmail.com'); 
define('EMAIL_FROM',     '');
define('EMAIL_PASS',     '');
define('EMAIL_PORT',     587);



// AES encriptação
define('AES_KEY',       'qs8BzdLD8N7qJpJgpJ3qmGsuHMhCWqG4');
define('AES_IV',       'WszH6HcdZAYdQ9be');

define('PDF_PATH', 'C:\xampp\htdocs\Restaurente\public\admin\PDF_PATH/');
define('CPR', 'C:\xampp\htdocs\Restaurente\public\comprovativos\reservas/');
define('CPP', 'C:\xampp\htdocs\Restaurente\public\comprovativos\pedidos/');

// dados de pagamento
define('IBAN', '0040.1236.5236.7895.4');
define('EXPRESS', '978912548');
define('CONTA', '789654125 1001');

?>