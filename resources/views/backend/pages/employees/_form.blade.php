@php
    $formAction ??= route('employees.store');
    $formMethod ??= 'POST';
@endphp

<form id="form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="row g-4">
        <!-- Left Column: Personal & Identity -->
        <div class="col-lg-8">
            <div class="card card-enterprise mb-4 border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Informasi Pribadi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">NIP</label>
                            <input type="text" name="employee_code" class="form-control" placeholder="Min. 8 angka">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Nomor HP</label>
                            <input type="text" name="phone" id="phoneInput" class="form-control"
                                placeholder="+62...">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Tempat Lahir</label>
                            <input type="text" name="birth_place" id="birthPlaceInput" autocomplete="off"
                                class="form-control" placeholder="Masukkan tempat lahir...">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Tanggal Lahir</label>
                            <input type="date" name="birth_date" id="birthDateInput" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Usia (Otomatis)</label>
                            <input type="text" id="ageDisplay" class="form-control bg-light" readonly disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required small fw-bold">Gender</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="male"
                                        id="genderM">
                                    <label class="form-check-label" for="genderM">Pria</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="female"
                                        id="genderF">
                                    <label class="form-check-label" for="genderF">Wanita</label>
                                </div>
                            </div>
                            <div class="invalid-feedback" style="display: block;"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required small fw-bold">Status Kawin</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="marital_status" value="kawin"
                                        id="maritalK">
                                    <label class="form-check-label" for="maritalK">Kawin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="marital_status"
                                        value="tidak kawin" id="maritalTK">
                                    <label class="form-check-label" for="maritalTK">Tidak Kawin</label>
                                </div>
                            </div>
                            <div class="invalid-feedback" style="display: block;"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Jumlah Anak</label>
                            <input type="number" name="children_count" class="form-control" min="0"
                                max="99" value="0">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="card card-enterprise mb-4 border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Alamat</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label required small fw-bold">Kecamatan</label>
                            <input type="text" name="kecamatan" id="kecamatanInput" autocomplete="off"
                                class="form-control" placeholder="Masukkan kecamatan...">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required small fw-bold">Kabupaten</label>
                            <input type="text" name="kabupaten" id="kabupatenInput" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required small fw-bold">Provinsi</label>
                            <input type="text" name="provinsi" id="provinsiInput" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label required small fw-bold">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required small fw-bold">Jarak ke Kantor (Km)</label>
                            <input type="number" name="distance_km" step="0.01" min="0"
                                class="form-control" placeholder="Contoh: 12.50" value="0">
                            <div class="form-hint">Diisi manual berdasarkan jarak rumah pegawai ke kantor.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education Repeater -->
            <div class="card card-enterprise mb-4 border-0 shadow-sm">
                <div
                    class="card-header border-bottom-0 d-flex justify-content-between align-items-center bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Riwayat Pendidikan</h6>
                    <button type="button" class="btn btn-sm btn-light text-primary border" id="addEducation">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </button>
                </div>
                <div class="card-body p-4">
                    <div id="educationContainer">
                        <div class="text-muted small no-education py-3 text-center italic">Belum ada riwayat
                            pendidikan.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Employment & Status -->
        <div class="col-lg-4">
            <!-- Photo Sidebar -->
            <div class="card card-enterprise mb-4 border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Foto Pegawai</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <img id="photoPreview"
                            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='150' height='150'%3E%3Crect fill='%23e9ecef' width='150' height='150'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='14' fill='%236c757d'%3ENo image%3C/text%3E%3C/svg%3E"
                            class="img-thumbnail rounded" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <input type="file" name="upload_photo" id="photoInput" class="form-control form-control-sm"
                        accept="image/*">
                    <div class="invalid-feedback text-start" style="display: block;"></div>
                    <p class="small text-muted mt-2">Format: PNG, JPG, JPEG. Max: 2MB</p>
                </div>
            </div>

            <!-- Employment Sidebar -->
            <div class="card card-enterprise mb-4 border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Informasi Pekerjaan</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label required small fw-bold">Jabatan</label>
                        <select name="position" class="form-select">
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="manager">Manager</option>
                            <option value="staf">Staf</option>
                            <option value="magang">Magang</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required small fw-bold">Status Kepegawaian</label>
                        <select name="employment_status" class="form-select">
                            <option value="">-- Pilih Status --</option>
                            <option value="permanent">Pegawai Tetap</option>
                            <option value="contract">Pegawai Kontrak</option>
                            <option value="intern">Pegawai Magang</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required small fw-bold">Departemen</label>
                        <select name="department" class="form-select">
                            <option value="">-- Pilih Departemen --</option>
                            <option value="marketing">Marketing</option>
                            <option value="hrd">HRD</option>
                            <option value="production">Production</option>
                            <option value="executive">Executive</option>
                            <option value="commissioner">Commissioner</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required small fw-bold">Tanggal Masuk</label>
                        <input type="date" name="join_date" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Resign</label>
                        <input type="date" name="resign_date" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Keaktifan</label>
                        <div class="form-check form-switch mt-1">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                id="isActiveSwitch" checked>
                            <label class="form-check-label" for="isActiveSwitch">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-enterprise-primary btn-lg fs-6 py-3">Simpan Data
                    Pegawai</button>
                <a href="{{ route('employees.index') }}" class="btn btn-light btn-lg fs-6 border py-3">Batal</a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        /**
         * Employee Form Module (AJAX-compatible)
         * Handles Photo Preview, Age Calculation, and Education Repeater
         */
        (() => {
            'use strict';

            // 1. Photo Preview
            const photoInput = document.getElementById('photoInput');
            const photoPreview = document.getElementById('photoPreview');
            photoInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (re) => photoPreview.src = re.target.result;
                    reader.readAsDataURL(file);
                }
            });

            // 2. Age Calculation
            const birthDateInput = document.getElementById('birthDateInput');
            const ageDisplay = document.getElementById('ageDisplay');
            const calculateAge = () => {
                if (!birthDateInput.value) return;
                const birth = new Date(birthDateInput.value);
                const diff = Date.now() - birth.getTime();
                const age = new Date(diff).getUTCFullYear() - 1970;
                ageDisplay.value = age + " Tahun";
            };
            birthDateInput.addEventListener('change', calculateAge);
            calculateAge();

            // 3. Education Repeater
            const addBtn = document.getElementById('addEducation');
            const container = document.getElementById('educationContainer');
            let eduIndex = 0;

            // Helper to render education row
            const renderEducationRow = (index, data = {}) => {
                const row = document.createElement('div');
                row.className = 'education-row border-bottom mb-3 pb-3';
                row.dataset.index = index;
                row.innerHTML = `
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Jenjang</label>
                            <input type="text" name="educations[${index}][level]" class="form-control form-control-sm" value="${data.level || ''}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Institusi</label>
                            <input type="text" name="educations[${index}][institution]" class="form-control form-control-sm" value="${data.institution || ''}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Jurusan</label>
                            <input type="text" name="educations[${index}][major]" class="form-control form-control-sm" value="${data.major || ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Lulus</label>
                            <input type="number" name="educations[${index}][graduation_year]" class="form-control form-control-sm" value="${data.graduation_year || ''}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-light text-danger remove-education w-100 border"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                `;
                return row;
            };

            // Add education row handler
            addBtn.addEventListener('click', () => {
                const noEdu = container.querySelector('.no-education');
                if (noEdu) noEdu.remove();
                container.appendChild(renderEducationRow(eduIndex));
                eduIndex++;
            });

            // Remove education row handler
            container.addEventListener('click', (e) => {
                if (e.target.closest('.remove-education')) {
                    e.preventDefault();
                    e.target.closest('.education-row').remove();
                    if (container.children.length === 0) {
                        container.innerHTML =
                            '<div class="text-center py-3 text-muted italic small no-education">Belum ada riwayat pendidikan.</div>';
                    }
                }
            });

            // Expose globally for hydration from create/edit scripts
            window.renderEducationRow = renderEducationRow;
            window.educationContainer = container;

        })();
    </script>
@endpush
