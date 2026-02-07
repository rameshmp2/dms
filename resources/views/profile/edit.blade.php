<!-- resources/views/profile/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Profile Settings</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Timezone</label>
                            <select name="timezone" class="form-control" required>
                                @foreach($timezones as $tz => $label)
                                    <option value="{{ $tz }}" {{ $user->timezone == $tz ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                <button type="button" class="btn btn-link btn-sm p-0" id="detectTimezone">
                                    <i class="fas fa-location-arrow"></i> Auto-detect my timezone
                                </button>
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Current Time in Your Timezone</label>
                            <input type="text" class="form-control" value="{{ now()->setTimezone($user->timezone)->format('M d, Y h:i:s A') }}" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#detectTimezone').click(function() {
        $.ajax({
            url: '{{ route('profile.detect-timezone') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('select[name="timezone"]').val(response.timezone);
                    alert('Timezone detected: ' + response.timezone + ' (' + response.country + ')');
                }
            },
            error: function() {
                alert('Failed to detect timezone');
            }
        });
    });
});
</script>
@endpush