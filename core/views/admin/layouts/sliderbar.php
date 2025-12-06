<?php

use core\classes\Store;

if ($_SESSION['tipo_admin'] == 'admin') {

    require_once "sliderbar_admin.php";
} else if ($_SESSION['tipo_admin'] == 'recepcionista') {
    require_once "sliderbar_rec.php";
} else if ($_SESSION['tipo_admin'] == 'atendente') {
    require_once "sliderbar_ate.php";
} else {
    Store::redirect('inicio', true);
}
