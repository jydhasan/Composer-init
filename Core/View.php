<?php
// namespace core
namespace Core;
class View
{
    public static function render($view, $data = [])
    {
        // view path
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            // extract data to variables
            extract($data);
            // include view file
            include $viewPath;
        } else {
            echo "View not found: " . $viewPath;
        }
    }
}