<?php

use App\Controllers\PaymentController;
use App\Controllers\UserController;

return [
    'GET /'              => [PaymentController::class, 'showForm'],
    'POST /transactions' => [PaymentController::class, 'create'],
    'GET /card'          => [PaymentController::class, 'showCardForm'],
    'POST /card'         => [PaymentController::class, 'complete'],
    'GET /users'           => [UserController::class, 'index'],
    'GET /users/create'    => [UserController::class, 'create'],
    'POST /users'          => [UserController::class, 'store'],
    'GET /users/{id}'      => [UserController::class, 'show'],
    'GET /users/{id}/edit' => [UserController::class, 'edit'],
    'PUT /users/{id}'      => [UserController::class, 'update'],
    'DELETE /users/{id}'   => [UserController::class, 'delete'],
];
