<?php

use App\Controllers\HomeController;
use Core\SimpleRouter;        

// ====================================
//          ROUTE DEFINITIONS
// ====================================

$router = new SimpleRouter();

$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);

$router->get('/about', function() {
    echo "<h1>About Us</h1>";
    echo "<p>Welcome to our about page.</p>";
});

$router->get('/contact', function() {
    echo "<h1>Contact Page</h1>";
});

// 404 হ্যান্ডলার
$router->notFound(function() {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
});