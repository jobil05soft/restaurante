<?php

use core\classes\Store;

// require_once('config.php');
// carrega todas as classes do projeto
require_once('vendor/autoload.php');

// carrega o sistema de rotas
require_once('core/route.php');

Store::redirect('inicio');
// header('location: public/');
?>