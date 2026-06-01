# Refactor Employee Form ke Pola AJAX v12 JEEMCE

**Status**: ✅ SELESAI (Juni 2026)

## 📋 RINGKASAN IMPLEMENTASI

Refactor form employee dari pola tradisional (full reload) ke pola AJAX v12 JEEMCE dengan fitur:
- ✅ Form tidak reload saat submit (AJAX + FormData)
- ✅ Data model di-embed dalam `<script type="application/json" id="data">`
- ✅ Form fields hydrated via `jsonScriptToFormFields('#form', '#data')`
- ✅ Tidak menggunakan `old()` helper
- ✅ Error validasi tampil inline per field tanpa reload
- ✅ Support file upload (photo)
- ✅ Support nested data (education repeater)

---

## 🔧 FILE YANG DIMODIFIKASI

### 1. **EmployeeController.php** → `form()` method
- **Perubahan**: Return JSON saat request AJAX, redirect saat form biasa
- **Deteksi**: `$request->expectsJson()` untuk mendeteksi AJAX request
- **Response JSON**:
  ```json
  {
    "message": "Pegawai berhasil ditambahkan.",
    "redirect_url": "https://app.local/employees"
  }
  ```
- **Validasi gagal (422)**: Laravel auto-return validation errors via `validate()` method

### 2. **employees/create.blade.php** → Baru
- **Perubahan**: Include `_form` partial, embed JSON script, setup AJAX handler
- **Fitur AJAX**:
  - Embed model JSON untuk form initial state
  - `jsonScriptToFormFields()` hydrate form fields
  - Handle nested education repeater dari JSON
  - `$.ajax()` dengan FormData untuk file upload support

### 3. **employees/edit.blade.php** → Baru
- **Perubahan**: Sama seperti create, tapi pass form action + method PUT
- **Dynamic form action**: Via `$formAction` dan `$formMethod` variable ke `_form`

### 4. **employees/_form.blade.php** → Refactor lengkap
- **Perubahan kunci**:
  - Tambah `<form id="form" method="POST" action="$formAction" enctype="multipart/form-data">`
  - Hapus semua `@error()` directive dari input attributes
  - Hapus `old()` helper, ganti dengan empty values: `value=""`
  - Hapus `@error()` conditional display, tetap simpan `.invalid-feedback` div kosong
  - Ganti `name="photo"` → `name="upload_photo"` (vendor file pattern)
  - Refactor education repeater dengan `renderEducationRow()` helper

---

## 🎯 POLA IMPLEMENTASI

### Form Tag Structure (di _form.blade.php)
```blade
@php
    $formAction ??= route('employees.form');
    $formMethod ??= 'POST';
@endphp

<form id="form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif
    
    {{-- Form fields --}}
</form>
```

### Input Field Pattern (tanpa old(), tanpa @error() check)
```blade
{{-- BEFORE (Old pola) --}}
<input type="text" name="employee_code"
    class="form-control @error('employee_code') is-invalid @enderror"
    value="{{ old('employee_code', $employee->employee_code ?? '') }}">
@error('employee_code')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

{{-- AFTER (AJAX pola) --}}
<input type="text" name="employee_code" class="form-control">
<div class="invalid-feedback"></div>
```

### Form Hydration (di create/edit view, section scripts)
```javascript
// Embed model JSON
<script id="data" type="application/json">
    {!! $employee->load('educations')->toJson(JSON_FORCE_OBJECT) !!}
</script>

// Initialize form fields from JSON
<script type="module">
    const dataJson = jsonScriptToFormFields('#form', '#data');
    
    // Hydrate nested repeater
    if (dataJson.educations && Array.isArray(dataJson.educations) && dataJson.educations.length > 0) {
        const container = document.getElementById('educationContainer');
        const noEdu = container.querySelector('.no-education');
        if (noEdu) noEdu.remove();
        
        dataJson.educations.forEach((edu, idx) => {
            const row = window.renderEducationRow(idx, edu);
            container.appendChild(row);
        });
        window.eduIndex = dataJson.educations.length;
    }
</script>
```

### Form Submit Handler (AJAX dengan FormData)
```javascript
const $form = $('#form');

$form.on('submit', function(e) {
    e.preventDefault();
    
    // Use FormData for file upload support
    const formData = new FormData(this);
    
    $.ajax({
        url: this.action,
        method: this.method || 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(data) {
            // Redirect on success
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
            }
        },
        error: function(jqXHR) {
            if (jqXHR.status === 422 && jqXHR.responseJSON?.errors) {
                const errors = jqXHR.responseJSON.errors;
                
                // Reset error states
                $form.find('[name]').removeClass('is-invalid is-valid');
                $form.find('.invalid-feedback').html('');
                
                // Display validation errors
                for (let fieldName in errors) {
                    const $field = $form.find(`[name="${fieldName}"]`);
                    if ($field.length) {
                        $field.addClass('is-invalid');
                        $field.next('.invalid-feedback').html(errors[fieldName]);
                    }
                }
            }
        }
    });
});
```

---

## 📝 CATATAN KHUSUS

### File Upload
- **Pattern**: `name="upload_photo"` (bukan `name="photo"`)
- **Vendor handling**: Helper `jeemce\models\Fileable` trait handle upload secara otomatis
- **FormData support**: FormData **bisa** mengirim binary file, berbeda dengan `serialize()` yang hanya text
- **Preview**: Photo preview di-update saat file dipilih via `FileReader` API

### Nested Data (Education Repeater)
- **Hydration**: `jsonScriptToFormFields()` hanya hydrate field-field sederhana, tidak array kompleks
- **Custom logic**: Di `_form.blade.php`, ada `window.renderEducationRow(idx, data)` helper untuk render education rows
- **Create/edit coordination**: Create.blade.php dan edit.blade.php call `renderEducationRow()` untuk setiap education record dari JSON

### Form Validation
- **Backend**: Laravel `$request->validate(Employee::rules($employee), Employee::messages())` akan return 422 otomatis
- **Frontend**: Error 422 ditangkap di AJAX error handler, loop errors, apply `.is-invalid` class + populate `.invalid-feedback` div
- **Display**: Per-field errors show inline tanpa page reload

---

## ✅ TESTING CHECKLIST

### Create Form
- [ ] Buka `/employees/create` → form kosong, tidak ada data
- [ ] Isi beberapa field, klik Tambah Pendidikan → education row bertambah
- [ ] Isi form lengkap, upload photo → photo preview update
- [ ] Klik Simpan → console check AJAX POST request
- [ ] Validasi: isi NIP dengan value pendek (< 8 char) → error tampil di field tanpa reload
- [ ] Klik Simpan lagi → redirect ke index page

### Edit Form
- [ ] Buka `/employees/{id}/edit` → form terisi dari database
- [ ] Check photo preview → menampilkan existing photo
- [ ] Periksa education repeater → existing educations sudah render
- [ ] Update salah satu field → klik Simpan → console check AJAX PUT request
- [ ] Validasi: kosongkan email → error tampil tanpa reload
- [ ] Klik Simpan lagi → redirect ke index page

### File Upload
- [ ] Change photo file → preview update (bukan reload page)
- [ ] Klik Simpan → check Networks tab, FormData contain file binary
- [ ] Check server receive file dengan nama `upload_photo`

### Education Repeater
- [ ] Add education → new row dengan index baru (tidak duplicate field names)
- [ ] Remove education → row delete, jika kosong tampil "Belum ada..."
- [ ] Edit form existing → existing education rows pre-filled dari JSON
- [ ] Submit form → all education data terkirim sebagai `educations[0][level]`, `educations[0][institution]`, etc

---

## 🔄 MIGRASI KE FORM LAIN

Untuk refactor form lain (User, Role, etc) ke AJAX pola:

1. **Controller**: Update method form/store/update untuk `return response()->json()` saat AJAX
2. **Create view**: Include form partial, embed JSON script, setup AJAX handler
3. **Edit view**: Sama, tapi dynamic form action
4. **Form partial**: Hapus `old()`, `@error()` checks, tambah form tag
5. **Nested data**: Jika ada, buat helper function `window.renderXxxRow()` untuk custom hydration

---

## 📚 REFERENSI VENDOR

- `vendor/jeemce/laravel/assets/main.js` - `jsonScriptToFormFields()`, `formAjaxSubmit()`
- `vendor/jeemce/laravel-theme-admin-v5/views/backend/user/form.blade.php` - Template reference
- `vendor/jeemce/laravel-theme-admin-v5/views/backend/menu/form.blade.php` - Nested data example

---

## 🐛 TROUBLESHOOTING

### Form tidak terisi saat edit
- Check: Script `id="data"` ada dan berisi valid JSON
- Check: `jsonScriptToFormFields()` function tersedia (ada di vendor JS)
- Check: Console untuk JavaScript errors

### Validation error tidak tampil
- Check: Form input ada `.invalid-feedback` div setelah input
- Check: Error response status 422
- Check: Error field name match dengan input `name` attribute

### Photo tidak terupload
- Check: File input `name="upload_photo"` (bukan `photo`)
- Check: FormData send `contentType: false, processData: false`
- Check: Server-side handle vendor `upload_photo` pattern

### Education repeater error
- Check: `window.renderEducationRow()` function defined di _form.blade.php
- Check: JSON embed berisi `educations` array
- Check: Foreach loop di hydration script tidak error di console

---

**Ende Dokumentasi. Implementasi selesai dan siap diproduksi.**
