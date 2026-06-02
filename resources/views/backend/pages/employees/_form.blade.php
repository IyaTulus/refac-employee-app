@php
    $employee ??= new \App\Models\Employee();
    $isEdit ??= !empty($employee->id);
    $formAction ??= $isEdit ? route('employees.update', $employee->id) : route('employees.store');
    $formMethod ??= $isEdit ? 'PUT' : 'POST';

    $title = $isEdit ? 'Edit Pegawai' : 'Tambah Pegawai';
    $indexHref = route('employees.index');

    $photoPreviewUrl =
        $employee->photo_url ??
        'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'150\'%3E%3Crect fill=\'%23e9ecef\' width=\'150\' height=\'150\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%236c757d\'%3ENo image%3C/text%3E%3C/svg%3E';

    $positionOptions = [
        '' => '-- Pilih Jabatan --',
        'manager' => 'Manager',
        'staf' => 'Staf',
        'magang' => 'Magang',
    ];

    $employmentStatusOptions = [
        '' => '-- Pilih Status --',
        'permanent' => 'Pegawai Tetap',
        'contract' => 'Pegawai Kontrak',
        'intern' => 'Pegawai Magang',
    ];

    $departmentOptions = [
        '' => '-- Pilih Departemen --',
        'marketing' => 'Marketing',
        'hrd' => 'HRD',
        'production' => 'Production',
        'executive' => 'Executive',
        'commissioner' => 'Commissioner',
    ];

    $positionClass = 'form-select' . ($errors->has('position') ? ' is-invalid' : '');
    $employmentStatusClass = 'form-select' . ($errors->has('employment_status') ? ' is-invalid' : '');
    $departmentClass = 'form-select' . ($errors->has('department') ? ' is-invalid' : '');

    $positionSelected = old('position', $employee->position ?? '');
    $employmentStatusSelected = old('employment_status', $employee->employment_status ?? '');
    $departmentSelected = old('department', $employee->department ?? '');
@endphp

@extends('backend.layouts.admin')

@section('title', $title)

@section('content')
    <div class="container pb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Daftar Pegawai</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">{{ $title }}</h1>
        </div>

        <form id="form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data" novalidate>
            @csrf
            @if ($formMethod === 'PUT')
                @method('PUT')
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card card-enterprise mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                            <h6 class="fw-bold mb-0">Informasi Pribadi</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">NIP</label>
                                    <input type="text" name="employee_code" class="form-control"
                                        placeholder="Min. 8 angka"
                                        value="{{ old('employee_code', $employee->employee_code ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Nama Lengkap</label>
                                    <input type="text" name="full_name" class="form-control"
                                        value="{{ old('full_name', $employee->full_name ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $employee->email ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Nomor HP</label>
                                    <input type="text" name="phone" id="phoneInput" class="form-control"
                                        placeholder="+62..." value="{{ old('phone', $employee->phone ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Tempat Lahir</label>
                                    <input type="text" name="birth_place" id="birthPlaceInput" autocomplete="off"
                                        class="form-control" placeholder="Masukkan tempat lahir..."
                                        value="{{ old('birth_place', $employee->birth_place ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" id="birthDateInput" class="form-control"
                                        value="{{ old('birth_date', optional($employee->birth_date)->format('Y-m-d')) }}">
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
                                                id="genderM" @checked(old('gender', $employee->gender ?? '') === 'male')>
                                            <label class="form-check-label" for="genderM">Pria</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" value="female"
                                                id="genderF" @checked(old('gender', $employee->gender ?? '') === 'female')>
                                            <label class="form-check-label" for="genderF">Wanita</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" style="display: block;"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required small fw-bold">Status Kawin</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="marital_status"
                                                value="kawin" id="maritalK" @checked(old('marital_status', $employee->marital_status ?? '') === 'kawin')>
                                            <label class="form-check-label" for="maritalK">Kawin</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="marital_status"
                                                value="tidak kawin" id="maritalTK" @checked(old('marital_status', $employee->marital_status ?? '') === 'tidak kawin')>
                                            <label class="form-check-label" for="maritalTK">Tidak Kawin</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" style="display: block;"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Jumlah Anak</label>
                                    <input type="number" name="children_count" class="form-control" min="0"
                                        max="99"
                                        value="{{ old('children_count', $employee->children_count ?? 0) }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-enterprise mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                            <h6 class="fw-bold mb-0">Alamat</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label required small fw-bold">Kecamatan</label>
                                    <input type="text" name="kecamatan" id="kecamatanInput" autocomplete="off"
                                        class="form-control" placeholder="Masukkan kecamatan..."
                                        value="{{ old('kecamatan', $employee->kecamatan ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required small fw-bold">Kabupaten</label>
                                    <input type="text" name="kabupaten" id="kabupatenInput" class="form-control"
                                        value="{{ old('kabupaten', $employee->kabupaten ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required small fw-bold">Provinsi</label>
                                    <input type="text" name="provinsi" id="provinsiInput" class="form-control"
                                        value="{{ old('provinsi', $employee->provinsi ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label required small fw-bold">Alamat Lengkap</label>
                                    <textarea name="address" class="form-control" rows="3">{{ old('address', $employee->address ?? '') }}</textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required small fw-bold">Jarak ke Kantor (Km)</label>
                                    <input type="number" name="distance_km" step="0.01" min="0"
                                        class="form-control" placeholder="Contoh: 12.50"
                                        value="{{ old('distance_km', $employee->distance_km ?? 0) }}">
                                    <div class="form-hint">Diisi manual berdasarkan jarak rumah pegawai ke kantor.</div>
                                </div>
                            </div>
                        </div>
                    </div>

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

                <div class="col-lg-4">
                    <div class="card card-enterprise mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                            <h6 class="fw-bold mb-0">Foto Pegawai</h6>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <img id="photoPreview" src="{{ $photoPreviewUrl }}" class="img-thumbnail rounded"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <input type="file" name="upload_photo" id="photoInput"
                                class="form-control form-control-sm" accept="image/*">
                            <div class="invalid-feedback text-start" style="display: block;"></div>
                            <p class="small text-muted mt-2">Format: PNG, JPG, JPEG. Max: 2MB</p>
                        </div>
                    </div>

                    <div class="card card-enterprise mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                            <h6 class="fw-bold mb-0">Informasi Pekerjaan</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label required small fw-bold">Jabatan</label>
                                {{ HtmlHelper::select([
                                    'name' => 'position',
                                    'options' => $positionOptions,
                                    'selected' => $positionSelected,
                                    'class' => $positionClass,
                                ]) }}
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required small fw-bold">Status Kepegawaian</label>
                                {{ HtmlHelper::select([
                                    'name' => 'employment_status',
                                    'options' => $employmentStatusOptions,
                                    'selected' => $employmentStatusSelected,
                                    'class' => $employmentStatusClass,
                                ]) }}
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required small fw-bold">Departemen</label>
                                {{ HtmlHelper::select([
                                    'name' => 'department',
                                    'options' => $departmentOptions,
                                    'selected' => $departmentSelected,
                                    'class' => $departmentClass,
                                ]) }}
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required small fw-bold">Tanggal Masuk</label>
                                <input type="date" name="join_date" class="form-control"
                                    value="{{ old('join_date', optional($employee->join_date)->format('Y-m-d')) }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Tanggal Resign</label>
                                <input type="date" name="resign_date" class="form-control"
                                    value="{{ old('resign_date', optional($employee->resign_date)->format('Y-m-d')) }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Status Keaktifan</label>
                                <div class="form-check form-switch mt-1">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        id="isActiveSwitch" @checked(old('is_active', $employee->is_active ?? true))>
                                    <label class="form-check-label" for="isActiveSwitch">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-enterprise-primary btn-lg fs-6 py-3">Simpan Data
                            Pegawai</button>
                        <a href="{{ $indexHref }}" class="btn btn-light btn-lg fs-6 border py-3">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script id="data" type="application/json">
        {!! $employee->load('educations')->toJson() !!}
    </script>

    <script type="module">
        jsonScriptToFormFields('#form', '#data');
        $('#form').formAjaxSubmit();
    </script>

    <script type="module">
        (() => {
            'use strict';

            const form = document.getElementById('form');
            const submitButton = form?.querySelector('button[type="submit"]');
            const photoInput = document.getElementById('photoInput');
            const photoPreview = document.getElementById('photoPreview');
            const birthDateInput = document.getElementById('birthDateInput');
            const joinDateInput = form?.querySelector('[name="join_date"]');
            const resignDateInput = form?.querySelector('[name="resign_date"]');
            const ageDisplay = document.getElementById('ageDisplay');
            const addBtn = document.getElementById('addEducation');
            const container = document.getElementById('educationContainer');
            let eduIndex = 0;

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
                            <button type="button" class="btn btn-sm btn-light text-danger remove-education w-100 border">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                return row;
            };

            const hydrateEducations = (educations = []) => {
                const noEdu = container?.querySelector('.no-education');
                if (noEdu) noEdu.remove();

                educations.forEach((education, index) => {
                    container.appendChild(renderEducationRow(index, education));
                });

                eduIndex = educations.length;
                window.eduIndex = eduIndex;
            };

            const setLoadingState = (loading) => {
                if (!submitButton) return;
                submitButton.disabled = loading;
                submitButton.dataset.originalText ??= submitButton.innerHTML;
                submitButton.innerHTML = loading ? 'Menyimpan...' : submitButton.dataset.originalText;
            };

            const calculateAge = () => {
                if (!birthDateInput?.value) return;
                const birth = new Date(birthDateInput.value);
                const diff = Date.now() - birth.getTime();
                const age = new Date(diff).getUTCFullYear() - 1970;
                ageDisplay.value = `${age} Tahun`;
            };

            const normalizeDateValue = (value) => {
                if (!value) return '';
                const date = new Date(value);
                if (Number.isNaN(date.getTime())) return '';
                return date.toISOString().slice(0, 10);
            };

            photoInput?.addEventListener('change', (event) => {
                const file = event.target.files?.[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (readerEvent) => {
                    photoPreview.src = readerEvent.target.result;
                };
                reader.readAsDataURL(file);
            });

            birthDateInput?.addEventListener('change', calculateAge);
            calculateAge();

            addBtn?.addEventListener('click', () => {
                const noEdu = container?.querySelector('.no-education');
                if (noEdu) noEdu.remove();

                container.appendChild(renderEducationRow(eduIndex));
                eduIndex++;
                window.eduIndex = eduIndex;
            });

            container?.addEventListener('click', (event) => {
                if (!event.target.closest('.remove-education')) return;

                event.preventDefault();
                event.target.closest('.education-row')?.remove();

                if (container.children.length === 0) {
                    container.innerHTML =
                        '<div class="text-center py-3 text-muted italic small no-education">Belum ada riwayat pendidikan.</div>';
                }
            });

            const dataJson = jsonScriptToFormFields('#form', '#data');
            if (birthDateInput && dataJson.birth_date) {
                birthDateInput.value = normalizeDateValue(dataJson.birth_date);
                calculateAge();
            }
            if (joinDateInput && dataJson.join_date) {
                joinDateInput.value = normalizeDateValue(dataJson.join_date);
            }
            if (resignDateInput && dataJson.resign_date) {
                resignDateInput.value = normalizeDateValue(dataJson.resign_date);
            }
            if (Array.isArray(dataJson.educations) && dataJson.educations.length > 0) {
                hydrateEducations(dataJson.educations);
            }

            calculateAge();

            window.renderEducationRow = renderEducationRow;
            window.educationContainer = container;
        })();
    </script>
@endpush
