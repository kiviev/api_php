<?php
namespace Api\App;

use Api\App\Http\Router;
use Api\App\Http\Response;
use Api\App\Services\UserService;


$router = new Router($base_route);

/** route for home */
$router->add('/', function () {
    Response::send('home','html');
});

/** route to search for user properties by term */
$router->add('/api/search/([a-zA-Z]+)', function ($term) {
    Response::send(UserService::searchProperty($term),'json');
});

/** route to search for users by id*/
$router->add('/api/user/([0-9]+)', function ($id) {
    $user = Response::send(UserService::getObjectFromId($id),'json');
},["GET"]);



/** route 404 is added for any undefined route */
$router->add('/.*', function () {
    Response::send('errors/404','html', 404);
});





$router->route();
