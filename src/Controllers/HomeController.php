<?php
// namespace HomeController.php
namespace App\Controllers;
use Core\View;
class HomeController
{
    public function index()
    {
        // Render the home view
        View::render('home');
    }
}