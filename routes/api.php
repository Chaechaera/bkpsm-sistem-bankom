<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', function () {
    return [
        ['id' => 1, 'name' => 'Hong Joshua'],
        ['id' => 2, 'name' => 'Shin Junghwan'],
    ];
});