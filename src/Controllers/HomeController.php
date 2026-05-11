<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;

class HomeController
{
    public function index()
    {
        // সব user আনো
        $users = User::all();

        View::render('home', ['users' => $users]);
    }

    public function show()
    {
        // ID দিয়ে একজন user
        $user = User::find(1);
        View::render('home', ['user' => $user]);
    }

    public function store()
    {
        // নতুন user তৈরি
        $id = User::create([
            'name'   => 'John Doe',
            'email'  => 'john@example.com',
            'status' => 'active',
        ]);

        echo "New user created with ID: $id";
    }

    public function edit()
    {
        // Update
        User::update(1, ['name' => 'Jane Doe']);
        echo "User updated!";
    }

    public function destroy()
    {
        // Delete
        User::delete(1);
        echo "User deleted!";
    }
}
