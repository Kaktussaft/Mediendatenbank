<?php

namespace App\Core;

class Controller
{
    public function model($model)
    {
        require_once '../app/model/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = null)
    {
        require_once '../app/view/' . $view . '.php';
    }
    
    function sanitizeUserInput($name, $surname, $username,  $email)
    {
        $name = (string) $name;
        $surname = (string) $surname;
        $username = (string) $username;
        $email = (string) $email;
        return [$name, $surname, $username, $email];
    }
}
