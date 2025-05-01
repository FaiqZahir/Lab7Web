<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\ArtikelModel;

class ArtikelKategori extends Cell
{
    public function render(array $params = []): string
    {
        $model = new ArtikelModel();
        $kategoriList = $model->select('kategori')->distinct()->findAll();

        return view('components/artikel_kategori', ['kategoriList' => $kategoriList]);
    }
}
