# Pengukuran Memori: get() vs lazyById()

Dataset: sekitar 52.500 row pada tabel `transactions`.

| Cara baca data | Peak memory (MB) |
|---|---|
| `Transaction::lazyById()` | 40.6 |
| `Transaction::get()` | 98.9 |

Catatan: pada pengukuran pertama dengan `memory_limit` bawaan PHP (128 MB), `Transaction::get()` gagal dengan error `Allowed memory size of 134217728 bytes exhausted`, sedangkan `lazyById()` tetap berhasil. Angka `get()` di atas diperoleh setelah `memory_limit` dinaikkan menjadi 1G lewat `php -d memory_limit=1G artisan tinker`.

Kesimpulan kelompok: `lazyById()` memakai memori sekitar 2,4 kali lebih kecil daripada `get()` pada 52.500 row, karena data diambil per chunk kecil sehingga memori tetap datar. `get()` memuat semua row sekaligus, sehingga pemakaian memori naik mengikuti jumlah data dan bisa menabrak `memory_limit` PHP saat data makin besar. Untuk export atau proses data besar, `lazyById()` lebih aman.