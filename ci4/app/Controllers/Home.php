<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home', [
            'title' => 'Home',
            'content' => 'Selamat Datang di Portofolio Faiq Zahir Fadillah'
        ]);
    }
}
