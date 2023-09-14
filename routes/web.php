<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::view('/{path?}', 'index')->where('path', '^((?!api).)*');

