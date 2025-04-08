<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function about()
    {
        return view('about', [
            'title' => 'Tentang Saya',
            'content' => 'Halo! Saya adalah seorang web developer'
        ]);
    }

    public function artikel()
    {
        return view('artikel', [
            'title' => 'Artikel',
            'content' => 'Di Halaman ini, saya berbagi tentang artikel'
        ]);
    }

    public function contact()
    {
        return view('contact', [
            'title' => 'Hubungi Saya',
            'content' => 'Saya selalu terbuka untuk berdiskusi'
        ]);
    }
}