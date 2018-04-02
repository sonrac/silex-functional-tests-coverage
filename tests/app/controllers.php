<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

use Silex\Application;

$app->get('/', function () use ($app) {
    return $app->json(['status' => 'OK']);
});

$app->post('/', function (Application $app) {
    return $app->json(['status' => 'OK_POST']);
});

$app->delete('/item/{id}', function (Application $app, $id) {
    return $app->json(['status' => 'OK_DELETE'.$id]);
});

$app->put('/item/{id}', function (Application $app, $id) {
    return $app->json(['status' => 'OK_PUT'.$id]);
});

$app->patch('/item/{id}', function (Application $app, $id) {
    return $app->json(['status' => 'OK_PATCH'.$id]);
});

$app->get('/redirect', function (Application $app) {
    return $app->redirect('/');
});

$app->get('/redirect-redirect', function (Application $app) {
    return $app->redirect('/redirect');
});

$app->get('/circle-redirect', function (Application $app) {
    return $app->redirect('/circle-redirect');
});

$app->register(new \Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'path'   => __DIR__.'/../out/db.sqlite',
        'driver' => 'pdo_sqlite',
    ],
]);

$app->post('/create-user/{username}/{password}', function (Application $app, $username, $password) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $db->insert('users', [
        'id'       => mt_rand(0, 99999),
        'username' => $username,
        'password' => $password,
    ]);
});

return $app;