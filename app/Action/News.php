<?php

require_once('../Http/Controller/NewsController.php');

$method = $_GET ? $_GET['action'] : $_POST['action'];
$withImage = '';

switch ( $method ) {

    case 'create':
        $controller = new NewsController();
        return $controller->create();
        break;
    case 'update':
        $controller = new NewsController();
        return $controller->update();
        break;
    case 'delete':
        $controller = new NewsController();
        return $controller->delete();
        break;
    case 'export':
        $controller = new NewsController();
        return $controller->export($method, $withImage);
        break;
    default:
        $controller = new NewsController();
        return $controller->index();
        break;
}