<?php

namespace App\Controllers;

use App\Models\ArtikelModel;

class Artikel extends BaseController
{
    public function admin_index()
    {
        $title = 'Daftar Artikel';
        $q = $this->request->getVar('q') ?? '';
        $model = new ArtikelModel();

        $artikel = $model->like('judul', $q)->paginate(4);

        $data = [
            'title' => $title,
            'q' => $q,
            'artikel' => $artikel,
            'pager' => $model->pager,
        ];

        return view('admin/admin_index', $data);
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $file = $this->request->getFile('gambar');
            $file->move(ROOTPATH . 'public/gambar');

            $artikel = new ArtikelModel();
            $artikel->insert([
                'judul' => $this->request->getPost('judul'),
                'kategori' => $this->request->getPost('kategori'),
                'isi' => $this->request->getPost('isi'),
                'slug' => url_title($this->request->getPost('judul')),
                'gambar' => $file->getName(),
            ]);
            return redirect()->to('admin');
        }

        $title = "Tambah Artikel";
        return view('admin/add', compact('title'));
    }

    public function edit($id)
    {
        $artikel = new ArtikelModel();

        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $artikel->update($id, [
                'judul' => $this->request->getPost('judul'),
                'kategori' => $this->request->getPost('kategori'),
                'isi'   => $this->request->getPost('isi'),
            ]);
            return redirect()->to('admin');
        }

        $data = $artikel->where('id', $id)->first();
        $title = "Edit Artikel";
        return view('admin/edit', compact('title', 'data'));
    }

    public function delete($id)
    {
        $artikel = new ArtikelModel();
        $artikel->delete($id);
        // Reset AUTO_INCREMENT berdasarkan ID terakhir
        $db = \Config\Database::connect();
        $query = $db->query("SELECT MAX(id) AS max_id FROM artikel")->getRow();
        $nextId = ($query->max_id ?? 0) + 1;
        $db->query("ALTER TABLE artikel AUTO_INCREMENT = $nextId");

        return redirect()->to('admin');
    }
}