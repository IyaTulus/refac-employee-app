@csrf

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
                        <input type="text" name="employee_code"
                            class="form-control @error('employee_code') is-invalid @enderror"
                            value="{{ old('employee_code', $employee->employee_code ?? '') }}"
                            placeholder="Min. 8 angka">
                        @error('employee_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required small fw-bold">Nama Lengkap</label>
                        <input type="text" name="full_name"
                            class="form-control @error('full_name') is-invalid @enderror"
                            value="{{ old('full_name', $employee->full_name ?? '') }}">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $employee->email ?? '') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required small fw-bold">Nomor HP</label>
                        <input type="text" name="phone" id="phoneInput"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $employee->phone ?? '') }}" placeholder="+62...">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required small fw-bold">Tempat Lahir</label>
                        <div>
                            <input type="text" name="birth_place" id="birthPlaceInput" autocomplete="off"
                                class="form-control @error('birth_place') is-invalid @enderror"
                                value="{{ old('birth_place', $employee->birth_place ?? '') }}"
                                placeholder="Masukkan tempat lahir...">
                        </div>
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required small fw-bold">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birthDateInput"
                            class="form-control @error('birth_date') is-invalid @enderror"
                            value="{{ old('birth_date', isset($employee) && $employee->birth_date ? $employee->birth_date->format('Y-m-d') : '') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Usia (Otomatis)</label>
                        <input type="text" id="ageDisplay" class="form-control bg-light" value="" readonly
                            disabled>
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
                        @error('gender')
                            <div class="small text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required small fw-bold">Status Kawin</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="marital_status" value="kawin"
                                    id="maritalK" @checked(old('marital_status', $employee->marital_status ?? '') === 'kawin')>
                                <label class="form-check-label" for="maritalK">Kawin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="marital_status" value="tidak kawin"
                                    id="maritalTK" @checked(old('marital_status', $employee->marital_status ?? '') === 'tidak kawin')>
                                <label class="form-check-label" for="maritalTK">Tidak Kawin</label>
                            </div>
                        </div>
                        @error('marital_status')
                            <div class="small text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required small fw-bold">Jumlah Anak</label>
                        <input type="number" name="children_count"
                            class="form-control @error('children_count') is-invalid @enderror"
                            value="{{ old('children_count', $employee->children_count ?? 0) }}" min="0"
                            max="99">
                        @error('children_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <div>
                            <input type="text" name="kecamatan" id="kecamatanInput" autocomplete="off"
                                class="form-control @error('kecamatan') is-invalid @enderror"
                                value="{{ old('kecamatan', $employee->kecamatan ?? '') }}"
                                placeholder="Masukkan kecamatan...">
                        </div>
                        @error('kecamatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required small fw-bold">Kabupaten</label>
                        <input type="text" name="kabupaten" id="kabupatenInput"
                            class="form-control @error('kabupaten') is-invalid @enderror"
                            value="{{ old('kabupaten', $employee->kabupaten ?? '') }}">
                        @error('kabupaten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required small fw-bold">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsiInput"
                            class="form-control @error('provinsi') is-invalid @enderror"
                            value="{{ old('provinsi', $employee->provinsi ?? '') }}">
                        @error('provinsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label required small fw-bold">Alamat Lengkap</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $employee->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                    @php
                        $oldEducations = old('educations', isset($employee) ? $employee->educations->toArray() : []);
                    @endphp
                    @forelse($oldEducations as $idx => $edu)
                        <div class="education-row border-bottom mb-3 pb-3" data-index="{{ $idx }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Jenjang</label>
                                    <input type="text" name="educations[{{ $idx }}][level]"
                                        class="form-control form-control-sm" value="{{ $edu['level'] ?? '' }}"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Institusi</label>
                                    <input type="text" name="educations[{{ $idx }}][institution]"
                                        class="form-control form-control-sm" value="{{ $edu['institution'] ?? '' }}"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Jurusan</label>
                                    <input type="text" name="educations[{{ $idx }}][major]"
                                        class="form-control form-control-sm" value="{{ $edu['major'] ?? '' }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Lulus</label>
                                    <input type="number" name="educations[{{ $idx }}][graduation_year]"
                                        class="form-control form-control-sm"
                                        value="{{ $edu['graduation_year'] ?? '' }}" required>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button"
                                        class="btn btn-sm btn-light text-danger remove-education w-100 border"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small no-education py-3 text-center italic">Belum ada riwayat
                            pendidikan.</div>
                    @endforelse
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
                        src="{{ isset($employee) && $employee->photo ? asset('storage/' . $employee->photo) : 'https://via.placeholder.com/150x150?text=No+Photo' }}"
                        class="img-thumbnail rounded" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <input type="file" name="photo" id="photoInput"
                    class="form-control form-control-sm @error('photo') is-invalid @enderror" accept="image/*">
                @error('photo')
                    <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror
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
                    <select name="position" class="form-select @error('position') is-invalid @enderror">
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="manager" @selected(old('position', $employee->position ?? '') === 'manager')>Manager</option>
                        <option value="staf" @selected(old('position', $employee->position ?? '') === 'staf')>Staf</option>
                        <option value="magang" @selected(old('position', $employee->position ?? '') === 'magang')>Magang</option>
                    </select>
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label required small fw-bold">Departemen</label>
                    <select name="department" class="form-select @error('department') is-invalid @enderror">
                        <option value="">-- Pilih Departemen --</option>
                        <option value="marketing" @selected(old('department', $employee->department ?? '') === 'marketing')>Marketing</option>
                        <option value="hrd" @selected(old('department', $employee->department ?? '') === 'hrd')>HRD</option>
                        <option value="production" @selected(old('department', $employee->department ?? '') === 'production')>Production</option>
                        <option value="executive" @selected(old('department', $employee->department ?? '') === 'executive')>Executive</option>
                        <option value="commissioner" @selected(old('department', $employee->department ?? '') === 'commissioner')>Commissioner</option>
                    </select>
                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label required small fw-bold">Tanggal Masuk</label>
                    <input type="date" name="join_date"
                        class="form-control @error('join_date') is-invalid @enderror"
                        value="{{ old('join_date', isset($employee) && $employee->join_date ? $employee->join_date->format('Y-m-d') : '') }}">
                    @error('join_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
            <button type="submit" class="btn btn-enterprise-primary btn-lg fs-6 py-3">Simpan Data Pegawai</button>
            <a href="{{ route('employees.index') }}" class="btn btn-light btn-lg fs-6 border py-3">Batal</a>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        /**
         * Employee Form Module
         * Handles Autosuggest, Age Calculation, and Repeater
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

            // 3. Region inputs are manual now (no autosuggest)

            // 4. Education Repeater
            const addBtn = document.getElementById('addEducation');
            const container = document.getElementById('educationContainer');
            let eduIndex = {{ count($oldEducations) }};

            addBtn.addEventListener('click', () => {
                const noEdu = container.querySelector('.no-education');
                if (noEdu) noEdu.remove();

                const row = document.createElement('div');
                row.className = 'education-row border-bottom pb-3 mb-3';
                row.innerHTML = `
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Jenjang</label>
                    <input type="text" name="educations[${eduIndex}][level]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Institusi</label>
                    <input type="text" name="educations[${eduIndex}][institution]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Jurusan</label>
                    <input type="text" name="educations[${eduIndex}][major]" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Lulus</label>
                    <input type="number" name="educations[${eduIndex}][graduation_year]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-light border text-danger remove-education w-100"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
                container.appendChild(row);
                eduIndex++;
            });

            container.addEventListener('click', (e) => {
                if (e.target.closest('.remove-education')) {
                    e.target.closest('.education-row').remove();
                    if (container.children.length === 0) {
                        container.innerHTML =
                            '<div class="text-center py-3 text-muted italic small no-education">Belum ada riwayat pendidikan.</div>';
                    }
                }
            });

        })();
    </script>
@endpush
