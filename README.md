# Simple POS - Kelompok 02

Repository sistem kasir (*Point of Sale*) sederhana berbasis Laravel.

---

## 1. Pengujian Validasi `POST /pos` Lewat cURL

Pengujian dilakukan pada endpoint penyimpanan transaksi kasir (`POST /pos`) yang ditangani oleh `StoreTransactionRequest` dan `TransactionController::store`.

Karena route berada pada grup `web`, request memerlukan session cookie dan CSRF token (atau dapat menyertakan header `Accept: application/json` untuk mendapatkan respon error validasi dalam format JSON standar HTTP 422).

### Persiapan: Mendapatkan Session Cookie & CSRF Token

Jalankan perintah berikut di terminal (PowerShell atau Bash):

**Di PowerShell (Windows):**
```powershell
# 1. Simpan cookie ke file cookies.txt
curl.exe -s -c cookies.txt [http://127.0.0.1:8000/pos](http://127.0.0.1:8000/pos) | Out-Null

# 2. Ekstrak CSRF token dari halaman form POS
$token = (curl.exe -s -b cookies.txt [http://127.0.0.1:8000/pos](http://127.0.0.1:8000/pos) | Select-String -Pattern 'name="_token" value="([^"]+)"').Matches.Groups[1].Value

```

### Kasus 1: Mengirim Request dengan `qty` Bernilai 0

#### Perintah cURL:

**PowerShell:**

```powershell
curl.exe -s -b cookies.txt -H "Accept: application/json" -d "_token=$token" -d "items[0][product_id]=1" -d "items[0][qty]=0" [http://127.0.0.1:8000/pos](http://127.0.0.1:8000/pos)

```

**Bash / cURL Biasa:**

```bash
curl -s -b cookies.txt -H "Accept: application/json" \
  -d "_token=$TOKEN" \
  -d "items[0][product_id]=1" \
  -d "items[0][qty]=0" \
  [http://127.0.0.1:8000/pos](http://127.0.0.1:8000/pos)

```

#### HTTP Status Code:

`422 Unprocessable Content`

#### Respon Error yang Dikembalikan Laravel:

```json
{
  "message": "The items.0.qty field must be at least 1.",
  "errors": {
    "items.0.qty": [
      "The items.0.qty field must be at least 1."
    ]
  }
}

```

> **Catatan Pengamatan:**
> Validasi ini berasal dari aturan pada `StoreTransactionRequest`:
> `'items.*.qty' => ['required', 'integer', 'min:1']`.
> Laravel mendeteksi bahwa nilai `0` tidak memenuhi kriteria `min:1`, sehingga menolak input dan mengembalikan pesan `"The items.0.qty field must be at least 1."`.

---

### Kasus 2: Mengirim Request dengan `product_id` yang Tidak Ada

#### Perintah cURL:

Misalnya kita mengirim `product_id` bernilai `999999` (ID produk yang tidak terdaftar di database).

**PowerShell:**

```powershell
curl.exe -s -b cookies.txt -H "Accept: application/json" -d "_token=$token" -d "items[0][product_id]=999999" -d "items[0][qty]=1" [http://127.0.0.1:8000/pos](http://127.0.0.1:8000/pos)

```

**Bash / cURL Biasa:**

```bash
curl -s -b cookies.txt -H "Accept: application/json" \
  -d "_token=$TOKEN" \
  -d "items[0][product_id]=999999" \
  -d "items[0][qty]=1" \
  [http://127.0.0.1:8000/pos](http://127.0.0.1:8000/pos)

```

#### HTTP Status Code:

`422 Unprocessable Content`

#### Respon Error yang Dikembalikan Laravel:

```json
{
  "message": "The selected items.0.product_id is invalid.",
  "errors": {
    "items.0.product_id": [
      "The selected items.0.product_id is invalid."
    ]
  }
}

```

> **Catatan Pengamatan:**
> Validasi ini berasal dari aturan pada `StoreTransactionRequest`:
> `'items.*.product_id' => ['required', 'exists:products,id']`.
> Aturan `exists:products,id` mengecek langsung ke tabel `products` di database. Karena tidak ditemukan baris dengan `id = 999999`, Laravel menolak request dan mengembalikan pesan `"The selected items.0.product_id is invalid."`.

*(Catatan: Jika request dikirim tanpa header `Accept: application/json`, Laravel mengembalikan status `302 Found` (redirect kembali ke `/pos`) dengan pesan error di atas tersimpan dalam flash session `$errors`).*

---

## 2. Analisis Keamanan: Validasi Field `total` dari Form

### Pertanyaan:

> *Kalau field `total` tetap dikirim dari form dan divalidasi dengan aturan `numeric`, apakah itu cukup mencegah manipulasi total? Kenapa atau kenapa tidak?*

### Jawaban:

**TIDAK CUKUP.** (Sama sekali tidak cukup untuk mencegah manipulasi total).

### Penjelasan & Alasan:

1. **Aturan `numeric` Hanya Memvalidasi Tipe Data, Bukan Kebenaran Nilai (*Business Logic Correctness*):**
* Aturan validasi `numeric` di Laravel hanya memastikan bahwa nilai yang diterima adalah angka valid (misalnya `1`, `100`, atau `1000000`).
* Aturan tersebut **tidak tahu dan tidak memverifikasi** berapa nilai total yang seharusnya. Aturan `numeric` tidak dapat memastikan apakah nilai tersebut cocok dengan hasil perhitungan:

$$\text{Total} = \sum (\text{harga\_produk} \times \text{qty})$$




2. **Data dari Klien / Form Sangat Mudah Dimanipulasi (*Client-Side Input is Untrusted*):**
* Apapun yang dikirim dari browser atau form HTML berada di bawah kendali penuh pengguna/penyerang.
* Siapa pun dapat dengan mudah membuka browser DevTools (*Inspect Element*), mengubah nilai atribut `<input name="total" value="...">`, atau mengirimkan request POST secara langsung melalui cURL / Postman.
* **Skenario Eksploitasi:**
* Seorang pengguna membeli barang berharga Rp 500.000 sebanyak 2 buah (seharusnya total = Rp 1.000.000).
* Pengguna tersebut memodifikasi form atau mengirim request via cURL dengan field `total = 1000` atau `total = 1`.
* Karena angka `1` atau `1000` adalah nilai angka yang sah, validasi `numeric` di server akan menganggapnya **VALID (LOLOS)**.
* Jika backend langsung mempercayai dan menyimpan nilai `total` dari form tersebut ke database, maka transaksi bernilai Rp 1.000.000 tersebut tercatat dan dibayar hanya sebesar Rp 1 atau Rp 1.000. Ini menimbulkan kerugian finansial fatal bagi pemilik usaha.




3. **Prinsip Keamanan: *Single Source of Truth* & Perhitungan di Sisi Server (Backend):**
* Dalam sistem keuangan, e-commerce, maupun kasir POS, nilai-nilai finansial krusial (harga satuan, subtotal, diskon, dan total) **wajib dihitung ulang secara independen oleh server** berdasarkan data harga resmi yang ada di database.
* Klien (form kasir) sebaiknya **hanya mengirimkan input mentah berupa daftar item yang dibeli (`product_id` dan `qty`)**.
* Backend kemudian:
1. Mengambil harga asli produk langsung dari database (`Product::findOrFail($item['product_id'])->price`).
2. Menghitung subtotal per item: `price * qty`.
3. Menjumlahkan seluruh subtotal untuk mendapatkan `total`.


* Pola perhitungan di sisi server inilah yang sudah diterapkan dengan benar pada `TransactionController::store`:
```php
$total = 0;
foreach ($validated['items'] as $item) {$product = Product::findOrFail($item['product_id']);$subtotal = $product->price * $item['qty'];
    $total +=$subtotal;
    // ... simpan detail transaksi ...
}
$transaction->update(['total' =>$total]);

```


* Dengan cara ini, berapapun nilai `total` yang coba dikirim atau dimanipulasi oleh klien dari form, server tidak akan pernah tertipu karena server yang menghitung sendiri total resminya dari database.



---

## 3. Skenario Uji Manual

Persiapan: jalankan `php artisan migrate:fresh --seed`, lalu `php artisan serve` dan `npm run dev` di terminal terpisah. Buka `http://127.0.0.1:8000`. Semua akun demo memakai kata sandi `password`.

| Peran | Email |
| --- | --- |
| Admin | admin@pos.test |
| Kasir | kasir@pos.test |
| Manager | manager@pos.test |

### Skenario 1: Admin

| No | Langkah | Hasil yang diharapkan |
| --- | --- | --- |
| 1 | Login dengan admin@pos.test | Diarahkan ke `/pos` |
| 2 | Lihat navigasi | Menu Kasir, Transaksi, Produk, Kategori tampil; kanan atas menampilkan nama dan peran |
| 3 | Buka `/products` | Daftar produk tampil normal |
| 4 | Buka `/categories` | Daftar kategori tampil normal |
| 5 | Klik Keluar | Kembali ke halaman `/login` |

### Skenario 2: Kasir

| No | Langkah | Hasil yang diharapkan |
| --- | --- | --- |
| 1 | Login dengan kasir@pos.test | Diarahkan ke `/pos` |
| 2 | Buka `/pos` | Halaman kasir tampil normal |
| 3 | Ketik `/products` di address bar | Halaman 403 "Anda tidak memiliki akses ke halaman ini." |
| 4 | Ketik `/transactions` di address bar | Halaman 403 (riwayat transaksi hanya untuk admin dan manager) |
| 5 | Login dengan kata sandi salah | Tetap di `/login`, muncul "Email atau kata sandi salah." |
| 6 | Tanpa login, buka `/products` | Diarahkan ke `/login` |

### Skenario 3: Manager

| No | Langkah | Hasil yang diharapkan |
| --- | --- | --- |
| 1 | Login dengan manager@pos.test | Diarahkan ke `/pos` |
| 2 | Lihat navigasi | Menu Kasir dan Transaksi tampil; kanan atas "Manager Kafe (manager)" |
| 3 | Buka `/transactions` | Riwayat transaksi tampil normal |
| 4 | Ketik `/products` di address bar | Halaman 403 "Anda tidak memiliki akses ke halaman ini." |

### Catatan Temuan

* Menu Transaksi tampil untuk kasir, padahal `/transactions` menghasilkan 403.
* Membuka `/login` saat sudah login mengarah ke `/dashboard` dan menghasilkan error 500. Klik Keluar dulu sebelum membuka `/login`.

---

## 4. Kesimpulan Tantangan Mandiri (Import Produk)

**Perbandingan Import Manual (PHP fgetcsv) vs Laravel Excel (ToModel):**

1. **Mana yang lebih ringkas?**
Tentu saja **Laravel Excel**. Logika validasi *header*, pembacaan baris, dan pembersihan baris kosong yang memakan puluhan baris kode manual di dalam `ProcessProductImport.php` berhasil dipangkas menjadi HANYA SATU BARIS pemanggilan `Excel::import()`. Fitur `WithHeadingRow` otomatis memetakan kolom tanpa perlu `array_combine`.
2. **Mana yang lebih mudah dikontrol?**
**Versi Manual (PHP native)** memberikan kontrol tingkat rendah yang lebih mutlak. Jika ada baris yang cacat, kita bisa dengan mudah menangkap pesan *error* per baris dan menyimpannya di variabel untuk dilaporkan ke *user*. Di Laravel Excel, menangani *error* spesifik per baris (melewati data yang gagal *database constraint*) membutuhkan implementasi fungsi tambahan seperti `SkipsOnError` atau `SkipsOnFailure` yang *learning curve*-nya sedikit lebih tinggi untuk pemula.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

* [Simple, fast routing engine](https://laravel.com/docs/routing).
* [Powerful dependency injection container](https://laravel.com/docs/container).
* Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
* Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
* Database agnostic [schema migrations](https://laravel.com/docs/migrations).
* [Robust background job processing](https://laravel.com/docs/queues).
* [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

* **[Vehikl](https://vehikl.com)**
* **[Tighten Co.](https://tighten.co)**
* **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
* **[64 Robots](https://64robots.com)**
* **[Curotec](https://www.curotec.com/services/technologies/laravel)**
* **[DevSquad](https://devsquad.com/hire-laravel-developers)**
* **[Redberry](https://redberry.international/laravel-development)**
* **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](https://www.google.com/search?q=mailto%3Ataylor%40laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```

```