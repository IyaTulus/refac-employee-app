# Vendor Template Integration Guide (Pure Vendor)

## Tujuan
Dokumentasi ini menjelaskan cara menggunakan template dari vendor (`jeemce`) tanpa membuat atau memelihara file tema lokal.

Prinsip utama:
- View tema harus dipanggil dari vendor.
- Tidak mengubah file vendor.
- Tidak membuat duplikasi view tema di `resources/views`.
- Jika ada mismatch, sesuaikan kode lokal (route/controller/model/data) agar kompatibel dengan vendor.

## Lokasi Template Vendor
Template admin berasal dari:
- `vendor/jeemce/laravel-theme-admin-v5/views`

Contoh view utama vendor:
- `backend/layouts/main.blade.php`
- `backend/layouts/main_sidebar.blade.php`
- `backend/layouts/main_navbar.blade.php`

## Kunci Pemanggilan View Vendor
Tambahkan prioritas path view vendor di service provider:

File: `app/Providers/AppServiceProvider.php`

```php
use Illuminate\Support\Facades\View;

public function boot(): void
{
    $vendorThemeViews = base_path('vendor/jeemce/laravel-theme-admin-v5/views');
    if (is_dir($vendorThemeViews)) {
        View::getFinder()->prependLocation($vendorThemeViews);
    }

    // kode boot lain...
}
```

Kenapa `prependLocation`:
- Agar Laravel mencari view vendor lebih dulu dibanding lokasi default.
- Mengurangi risiko view lokal menimpa view vendor.

## Aturan Struktur View Lokal
Untuk menjaga pure vendor:
- Jangan buat `main_sidebar.blade.php` lokal.
- Jangan buat `main_navbar.blade.php` lokal.
- Jangan buat `main.blade.php` lokal untuk tema yang sama.
- Hindari file partial lokal dengan nama yang meniru view vendor.

Jika file override lokal sudah terlanjur ada, hapus supaya resolver view mengarah ke vendor.

## Kompatibilitas Route (Wajib)
View vendor sering memanggil route name tertentu. Project lokal harus menyediakan route alias kompatibel.

Contoh route name yang dibutuhkan vendor:
- `backend.home.index`
- `backend.user.show`
- `backend.user.changePassword`
- `backend.user.updatePassword`

Implementasi dilakukan di route lokal (alias ke endpoint yang sudah ada), bukan mengubah vendor.

## Kompatibilitas Controller (Jika Dibutuhkan Vendor)
Jika vendor memanggil URL/aksi tertentu, sediakan method di controller lokal.

Contoh:
- `editPassword()`
- `updatePassword()`

Pastikan flow tetap mengarah ke bisnis logic project lokal.

## Kontrak Data Sidebar
Sidebar vendor membaca data menu dari tabel menu/access vendor.

Pastikan data memenuhi kontrak:
- `menus.type = 'owner_sidebar'`
- `menus.status = 'publish'`
- Tabel `accesses` berisi izin role terhadap menu (`read` tidak `none`).

Jika data ada tetapi menu tidak muncul, biasanya masalah ada pada:
- resolusi role user,
- mapping role ke `accesses.id_role`,
- status/type menu.

## Bug Umum yang Pernah Terjadi
Kasus:
- Query akses memakai object role (JSON), bukan role id.

Gejala:
- Sidebar kosong.
- Query `accesses.id_role` terlihat aneh (object/string JSON).

Penyebab:
- Helper vendor membaca role dari field yang dianggap ada karena relasi loaded.

Solusi lokal:
- Pastikan model user mengembalikan field role sebagai scalar id (`role_id`), bukan object relasi.

## Checklist Implementasi
1. Path view vendor diprioritaskan di `AppServiceProvider`.
2. Tidak ada file view tema lokal yang menimpa vendor.
3. Route alias vendor tersedia di route lokal.
4. Method controller yang dibutuhkan vendor tersedia.
5. Data menu/access sesuai kontrak vendor.
6. Uji halaman `/admin` setelah clear cache.

## Perintah Verifikasi
Setelah perubahan route/view, jalankan:

```bash
php artisan optimize:clear
php artisan route:clear
php artisan view:clear
```

Lalu cek:
- `/admin` render tanpa error route.
- Sidebar tampil sesuai data menu DB.
- Menu mengikuti akses role login.

## Best Practice Ke Depan
- Anggap vendor sebagai source of truth untuk tema.
- Penyesuaian dilakukan di integration layer lokal: route alias, controller adapter, model mapping, data mapping.
- Hindari forking vendor view kecuali benar-benar terpaksa.
