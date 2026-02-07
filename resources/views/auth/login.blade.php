<!-- resources/views/auth/login.blade.php -->
@extends('layouts.guest')

@section('title', 'Login - DMS')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card auth-card">
            <div class="card-header text-center">
                <h3 class="mb-0">
                    <i class="fas fa-file-alt text-primary"></i>
                    Document Management System
                </h3>
                <p class="text-muted mt-2">Sign in to your account</p>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   placeholder="Enter your email">
                        </div>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Enter your password">
                        </div>
                        @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" 
                                   class="custom-control-input" 
                                   id="remember" 
                                   name="remember">
                            <label class="custom-control-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                    <!-- Links -->
                    <div class="text-center mt-3">
                        <p class="mb-0">
                            Don't have an account? 
                            <a href="{{ route('register') }}">Register here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection