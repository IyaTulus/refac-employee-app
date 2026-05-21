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
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Captcha (Image) --}}
                        <div class="mb-3">
                            <label class="form-label">Captcha</label>
                            <div class="d-flex gap-2 align-items-center">
                                <img src="{{ route('captcha.generate') }}" id="captcha-img" alt="captcha" style="height: 50px;">
                                <button type="button" class="btn btn-sm btn-secondary" id="reload-captcha">Reload</button>
                            </div>
                            <input type="text" class="form-control mt-2 @error('captcha') is-invalid @enderror" 
                                name="captcha" placeholder="Masukkan captcha" required>
                            @error('captcha')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('reload-captcha').addEventListener('click', function(){
    document.getElementById('captcha-img').src = '{{ route('captcha.generate') }}?t=' + Date.now();
});
</script>
@endsection