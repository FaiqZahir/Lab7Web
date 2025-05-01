<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\ArtikelModel;

class ArtikelTerkini extends Cell
{
    public function render(array $params = []): string
    {
        $model = new ArtikelModel();
        $artikel = $model->orderBy('tanggal', 'DESC')
            ->limit(5)
            ->findAll();

        return view('components/artikel_terkini', ['artikel' => $artikel]);
    }
}
