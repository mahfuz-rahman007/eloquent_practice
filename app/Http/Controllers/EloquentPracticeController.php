<?php

namespace App\Http\Controllers;

use App\Models\User;

class EloquentPracticeController extends Controller
{
    public function index(){
        User::create([
            'name'=> fake()->name(),
            'email' => fake()->email(),
            'password' => bcrypt('password'),
        ]);

        echo "done";
    }

}
