<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }
    public function showRegisterForm()
    {
        return view('register');
    }

   }
