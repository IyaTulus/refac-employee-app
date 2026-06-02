@extends('backend.layouts.admin')

@section('title', 'Edit User: ' . $user->username)

@section('content')
    <div class="container pb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Daftar User</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit User: {{ $user->username }}</h1>
        </div>

        @include('backend.pages.users._form', [
            'formAction' => route('users.update', $user->id),
            'formMethod' => 'PUT',
        ])
    </div>
@endsection

@push('scripts')
    {{-- Embed model JSON with existing data for form hydration --}}
    <script id="data" type="application/json">
        {!! $user->toJson(JSON_FORCE_OBJECT) !!}
    </script>

    <script type="module">
        // Hydrate form fields from JSON data (all fields from model, not old())
        const dataJson = jsonScriptToFormFields('#form', '#data');

        // Setup AJAX form submit with file upload support
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
    </script>
@endpush
