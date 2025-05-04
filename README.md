# Praktikum 1

### 1. Mengaktifkan Ekstensi di `php.ini`

Berikut adalah ekstensi PHP yang harus diaktifkan:

1. **php-json** → Diperlukan untuk bekerja dengan JSON.
2. **php-mysqlnd** → Native driver untuk MySQL.
3. **php-xml** → Diperlukan untuk bekerja dengan XML.
4. **php-intl** → Diperlukan untuk mendukung aplikasi multibahasa.
5. **libcurl** (opsional) → Diperlukan jika ingin menggunakan Curl.

Buka xampp file Config `php.ini` dan pastikan ekstensi berikut tidak dikomentari (hilangkan tanda `;` di depannya jika ada):

```ini
extension=json
extension=mysqli
extension=xml
extension=intl
extension=curl
```

![Screenshot](public/readme/1.png)

### 2. Restart Webserver

Setelah mengaktifkan ekstensi, restart webserver agar perubahan diterapkan.

## Instalasi

### 3. Instalasi CodeIgniter 4

Instalasi dengan cara manual:

1. Unduh **CodeIgniter** → (https://codeigniter.com/download)
2. Extrak file zip Codeigniter ke direktori **htdocs/Lab7Web**
3. Ubah nama direktory **framework-4.x.xx** menjadi **ci4**.

### 4. Menjalankan CLI XAMPP

1. Arahkan direktori sesuai dengan project → (xampp/htdocs/Lab7Web/ci4/)
2. Perintah yang dapat dijalankan untuk memanggil CLI Codeigniter adalah:
   `php spark serve` Meluncurkan server pengembangan bawaan, Memungkinkan melihat aplikasi di browser (http://localhost:8080).

![Screenshot](public/readme/2.png)

![Screenshot](public/readme/3.png)

### 5. Mengaktifkan Mode Debugging

Fitur debugging dari CodeIgniter 4 untuk memudahkan developer untuk mengetahui
pesan error apabila terjadi kesalahan dalam membuat kode program. Mengaktifkan mode debugging dengan mengubah niai konfigurasi pada file `env`, cari variable `CI_ENVIRONMENT` ubah menjadi `development`

![Screenshot](public/readme/4.png)

Kemudian rename nama file `env → .env`

Contoh Error yang terjadi ketika menghapus `function` pada file **app/Controller/Home.php**

![Screenshot](public/readme/5.png)

![Screenshot](public/readme/6.png)

### 6. Routing dan Controllers

Routing dalam CodeIgniter 4 adalah proses yang menghubungkan permintaan (request) dari pengguna ke Controller yang sesuai untuk diproses. Routing ini memungkinkan kita menentukan bagaimana URL diterjemahkan menjadi aksi dalam aplikasi, sehingga setiap permintaan dapat diarahkan dengan benar.

**Membuat Route baru (autoRoute(false)):**

Secara default fitur autoRoute sudah aktif. Untuk mengubah status autoroute
dapat mengubah nilai variabelnya. Untuk menonaktifkan ubah nilai true menjadi false. Nonaktifkan Auto Routing `($routes->setAutoRoute(false);)` ketika menjalankan di production.

tambahkan kode berikut dalam **app/Config/Routes.php**

![Screenshot](public/readme/7.png)

Untuk mengetahui route yang ditambahkan sudah benar, buka CLI dan jalankan perintah `php spark routes`:

![Screenshot](public/readme/8.png)

**Membuat Controllers:**

tambahkan code berikut dalam **app/Controllers/Page.php & Home.page**

![Screenshot](public/readme/9.png)

**Summary:**

1. Auto Routing cocok untuk pengembangan cepat, tetapi kurang aman dan sulit dikontrol. Semua method dalam controller bisa diakses langsung dari URL, yang bisa membuka celah keamanan.

2. Manual Routing lebih aman, fleksibel, dan efisien karena hanya route yang didefinisikan secara eksplisit yang bisa diakses. Ini juga memudahkan pengelolaan middleware, otorisasi, dan optimasi aplikasi.

### 6. Membuat View

1. Buat File `app/Views/about.php` `app/Views/artikel.php` `app/Views/contact.php` `app/Views/home.php`:

   ![Screenshot](public/readme/10.png)

2. Tambahkan code pada ke 4 file diatas:

   ![Screenshot](public/readme/11.png)

### 7. Layout Web dengan CSS

1. Buat File CSS `style.css`:

   ![Screenshot](public/readme/12.png)

2. Kemudian buat folder template pada direktori `app/Views/template` kemudian buat file `header.php` dan
   `footer.php`:

   ![Screenshot](public/readme/13.png)

3. `app/Views/template/header.php`:

   ![Screenshot](public/readme/14.png)

4. `app/Views/template/footer.php`:

   ![Screenshot](public/readme/15.png)

5. Ubah file `app/Views/about.php` `app/Views/artikel.php` `app/Views/contact.php` `app/Views/home.php`:

   ![Screenshot](public/readme/16.png)

### 7. Hasil Output

1. Home

   ![Screenshot](public/readme/17.png)

2. Artikel

   ![Screenshot](public/readme/18.png)

3. About

   ![Screenshot](public/readme/19.png)

4. Contact

   ![Screenshot](public/readme/20.png)

# Paraktikum 2

### Membuat Database dan Tabel

![Screenshot](public/readme/21.png)

### Config Koneksi Database

Lakukan Config untuk menghubungkan dengan database server pada file `.env`

![Screenshot](public/readme/22.png)

### Membuat Model

Model ini menentukan hubungan antar data dan bagaimana data dapat diakses serta dimanipulasi. Membuat model untuk mengakses data artikel `app/Models`

![Screenshot](public/readme/23.png)

### Membuat Controller

Pada direktori `app/Controllers/Home.php` buat Controller pada func index

![Screenshot](public/readme/24.png)

kemudian perubahan kecil pada `Routes.php`

![Screenshot](public/readme/25.png)

Ubah `app/Views/home.php` menjadi

![Screenshot](public/readme/28.png)

Mencoba membuat artikel sementara

![Screenshot](public/readme/26.png)
![Screenshot](public/readme/27.png)

### Membuat tampilan detail artikel

1. Ubah func Artikel pada `app/Controllers/Page.php`

![Screenshot](public/readme/29.png)

2. Membuat View baru di `app/Views/artikel.php` dan membuat routing baru di `app/Config/Routes.php` **$routes->get('page/artikel/(:any)', 'Page::artikel/$1');**

![Screenshot](public/readme/30.png)

3. Hasil dari detail artikel.

![Screenshot](public/readme/31.png)

### Membuat menu admin

1. Membuat method baru pada Controllers `app/Controllers/Artikel.php` dengan nama **admin_index()**

![Screenshot](public/readme/32.png)

2. Menuju direktori `app/Views/` lalu buat **admin_index.php**

![Screenshot](public/readme/33.png)

3. Tambah routes `app/Config/Routes.php` untuk menu admin

![Screenshot](public/readme/34.png)

4. Hasil output

![Screenshot](public/readme/35.png)

### Menambah data artikel

1. Menambahkan func baru pada `app/Controllers/Artikel.php` dengan nama **Add()**

![Screenshot](public/readme/36.png)

2. Buat **add.php** di `app/Views` untuk form tambah artikel

![Screenshot](public/readme/37.png)

3. Hasil Output

![Screenshot](public/readme/38.png)
![Screenshot](public/readme/39.png)

### Edit Data

1. Menambahkan func baru pada `app/Controllers/Artikel.php` dengan nama **edit($id)**

![Screenshot](public/readme/40.png)

2. Buat **edit.php** di `app/Views` untuk form edit artikel

![Screenshot](public/readme/41.png)

3. Hasil Output

**Before**
![Screenshot](public/readme/38.png)

**After**
![Screenshot](public/readme/42.png)
![Screenshot](public/readme/43.png)

### Menghapus data

1. Menambahkan func baru pada `app/Controllers/Artikel.php` dengan nama **delete($id)**

![Screenshot](public/readme/44.png)

2. Hasil output seletah saya delete dengan judul **Luthfi Ammar Musthofa**

![Screenshot](public/readme/45.png)

# Praktikum 3

### Membuat layout utama

1. Buat folder layout utama di `app/Views/layout` lalu buat file `main.php`

![Screenshot](public/readme/46.png)

2. Modifikasi file `app/Views` seperti **about.php, artikel.php, contact.php, home.php**
   berikut contoh modifikasi di line 1,3,23 pada **home.php**

![Screenshot](public/readme/47.png)

3. Berikut adalah tampilannya

![Screenshot](public/readme/48.png)

### Membuat class view cell

Membuat folder **Cells** di dalam `app` lalu buat file `ArtikelTerkini.php` pada `app/Cells`

![Screenshot](public/readme/49.png)

### Membuat view untuk view cell

1. Melakukan perubahan field pada database dengan menambahkan tanggal agar dapat mengambil data artikel terbaru.

![Screenshot](public/readme/53.png)

![Screenshot](public/readme/54.png)

2. Buat folder components di `app/Views`

![Screenshot](public/readme/50.png)

3. kemudian buat file `artikel_terkini.php` di `app/Views/components`

![Screenshot](public/readme/51.png)

4. Modifikasi `app/Views/layout/main.php`

![Screenshot](public/readme/52.png)

5. Hasil output dengan menampilkan artikel terbaru.

![Screenshot](public/readme/55.png)

## Manfaat View Layout:

Kode lebih terstruktur dan mudah dikelola.
Memudahkan pemeliharaan dengan perubahan di satu tempat.
Konsistensi desain di seluruh aplikasi.
Mengurangi duplikasi kode.
Meningkatkan performa dengan caching.

## Perbedaan View Cell & View Biasa:

View Cell: Untuk komponen kecil yang dapat digunakan ulang, dipanggil dengan view_cell(), dan memiliki kelas sendiri.
View Biasa: Untuk halaman utama atau tampilan besar, dipanggil dengan return view() dari Controller.

## Ubah View Cell agar hanya menampilkan post dengan kategori tertentu.

1. Menambahkan data baru / column baru untuk kategori

![Screenshot](public/readme/63.png)

2. Modifikasi model di `app/Models/ArtikelModel.php`

![Screenshot](public/readme/64.png)

3. Membuat file `ArtikelKategori.php` baru pada folder `app/Cells/`.

![Screenshot](public/readme/56.png)

4. Membuat file `artikel_kategori.php` baru pada folder `app/Views/components`.

![Screenshot](public/readme/57.png)

5. Modifikasi atau menambahkan code di file `app/Views/layout/main.php`

![Screenshot](public/readme/58.png)

6. Buat route baru di `app/Config/Routes.php`

`$routes->get('/page/kategori/(:segment)', 'Page::kategori/$1');`

7. Modifikasi atau menambahkan methode kategori controller di `app/Controllers/Home.php`

![Screenshot](public/readme/59.png)

8. Berikut adalah hasil memfilter artikel berdasarkan kategori:

before filter:
![Screenshot](public/readme/60.png)

after filter (kategori: CRUD):
![Screenshot](public/readme/61.png)

after filter (kategori: PHP):
![Screenshot](public/readme/62.png)

detail artikel:
![Screenshot](public/readme/65.png)

# Praktikum 4

### Membuat tabel user

![Screenshot](public/readme/66.png)

# Praktikum 5: Pagination dan Pencarian

### Tujuan
- Memahami konsep dasar Pagination dan Pencarian data.
- Menerapkan pagination dan fitur pencarian pada daftar artikel.

### Langkah-Langkah

1. **Menambahkan Pagination di Controller**
   - Tambahkan method `admin_index` di `app/Controllers/Artikel.php`:

     ```php
     public function admin_index()
     {
         $title = 'Daftar Artikel';
         $q = $this->request->getVar('q') ?? '';
         $model = new ArtikelModel();
         $data = [
             'title'   => $title,
             'q'       => $q,
             'artikel' => $model->like('judul', $q)->paginate(10),
             'pager'   => $model->pager,
         ];
         return view('artikel/admin_index', $data);
     }
     ```

2. **Menambahkan Form Pencarian**
   - Tambahkan form pencarian di `app/Views/artikel/admin_index.php`:

     ```php
     <form method="get" class="form-search">
         <input type="text" name="q" value="<?= $q; ?>" placeholder="Cari data">
         <input type="submit" value="Cari" class="btn btn-primary">
     </form>
     ```

3. **Menambahkan Navigasi Halaman**
   - Tambahkan kode berikut setelah tabel artikel:

     ```php
     <?= $pager->only(['q'])->links(); ?>
     ```

4. **Uji Coba**
   ![Screenshot](public/readme/68.png)
   ![Screenshot](public/readme/69.png)


# Praktikum 6: Upload File Gambar

### Tujuan
- Mempelajari cara upload file (gambar) pada form artikel menggunakan CodeIgniter 4.

### Langkah-Langkah

1. **Menambahkan Input File**
   - Tambahkan field di `app/Views/artikel/form_add.php`:

     ```html
     <form action="" method="post" enctype="multipart/form-data">
         <!-- ... field lainnya ... -->
         <p><input type="file" name="gambar"></p>
     </form>
     ```

2. **Memproses Upload Gambar**
   - Modifikasi method `add()` di `app/Controllers/Artikel.php`:

     ```php
     public function add()
     {
         $validation =  \Config\Services::validation();
         $validation->setRules(['judul' => 'required']);
         $isDataValid = $validation->withRequest($this->request)->run();

         if ($isDataValid) {
             $file = $this->request->getFile('gambar');
             $file->move(ROOTPATH . 'public/gambar');

             $artikel = new ArtikelModel();
             $artikel->insert([
                 'judul'  => $this->request->getPost('judul'),
                 'isi'    => $this->request->getPost('isi'),
                 'slug'   => url_title($this->request->getPost('judul')),
                 'gambar' => $file->getName(),
             ]);

             return redirect('admin/artikel');
         }

         $title = "Tambah Artikel";
         return view('artikel/form_add', compact('title'));
     }
     ```

3. **Uji Coba**
![Screenshot](public/readme/69.png)
![Screenshot](public/readme/70.png)

