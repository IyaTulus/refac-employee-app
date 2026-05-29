@extends('frontend.layouts.guest')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form id="login-form" method="POST" action="{{ route('login') }}" novalidate>
                            @csrf

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                <div class="text-danger small d-none mt-1" data-error-for="email"></div>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                <div class="text-danger small d-none mt-1" data-error-for="password"></div>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Captcha (Image) --}}
                            <div class="mb-3">
                                <label class="form-label">Captcha</label>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ route('captcha.generate') }}" id="captcha-img" alt="captcha"
                                        style="height: 50px;">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        id="reload-captcha">Reload</button>
                                </div>
                                <input type="text" class="form-control @error('captcha') is-invalid @enderror mt-2"
                                    name="captcha" placeholder="Masukkan captcha" required>
                                <div class="text-danger small d-none mt-1" data-error-for="captcha"></div>
                                @error('captcha')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" id="login-submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const form = document.getElementById('login-form');
            const submitButton = document.getElementById('login-submit');
            const captchaImg = document.getElementById('captcha-img');
            const reloadCaptchaButton = document.getElementById('reload-captcha');
            const originalSubmitText = submitButton?.textContent ?? 'Login';

            const reloadCaptcha = () => {
                if (!captchaImg) return;
                captchaImg.src = '{{ route('captcha.generate') }}?t=' + Date.now();
            };

            const clearErrors = () => {
                form.querySelectorAll('[data-error-for]').forEach((el) => {
                    el.textContent = '';
                    el.classList.add('d-none');
                });

                form.querySelectorAll('.is-invalid').forEach((el) => {
                    el.classList.remove('is-invalid');
                });
            };

            const setFieldError = (field, message) => {
                const input = form.querySelector(`[name="${field}"]`);
                const errorBox = form.querySelector(`[data-error-for="${field}"]`);

                if (input) {
                    input.classList.add('is-invalid');
                }

                if (errorBox) {
                    errorBox.textContent = message;
                    errorBox.classList.remove('d-none');
                }
            };

            const setLoading = (loading) => {
                if (!submitButton) return;

                submitButton.disabled = loading;
                submitButton.textContent = loading ? 'Memproses...' : originalSubmitText;
            };

            reloadCaptchaButton?.addEventListener('click', reloadCaptcha);

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                if (!window.axios) {
                    form.submit();
                    return;
                }

                clearErrors();
                setLoading(true);

                try {
                    const response = await window.axios.post(form.action, new FormData(form));

                    if (response?.data?.message) {
                        window.alert(response.data.message);
                    }

                    const redirectUrl = response?.data?.redirect_url;
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                        return;
                    }

                    setLoading(false);
                } catch (error) {
                    setLoading(false);
                    reloadCaptcha();

                    const status = error?.response?.status;
                    const errors = error?.response?.data?.errors ?? {};

                    if (status === 422) {
                        Object.entries(errors).forEach(([field, messages]) => {
                            const message = Array.isArray(messages) ? messages[0] : String(
                            messages);
                            setFieldError(field, message);
                        });

                        return;
                    }

                    window.alert(error?.response?.data?.message ?? 'Terjadi kesalahan saat login.');
                }
            });
        })();
    </script>
@endsection
