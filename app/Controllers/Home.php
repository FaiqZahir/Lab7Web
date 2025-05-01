<?php

namespace App\Controllers;

use App\Models\ArtikelModel;

class Home extends BaseController
{
    public function index(): string
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->findAll();
        return view('public/home', compact('artikel', 'title'));
    }

    public function kategori($kategori)
    {
        $model = new ArtikelModel();
        $artikel = $model->where('kategori', $kategori)->findAll();

        return view('public/home', ['artikel' => $artikel, 'title' => 'Kategori: ' . ucfirst($kategori)]);
    }
}
