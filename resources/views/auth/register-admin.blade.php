@extends('layouts.app')

@section('title', 'Admin Registration')

@section('content')
<div class="auth-card">
    <div class="auth-image">
        <h2>Admin Registration</h2>
        <p>Create an administrator account to access the admin dashboard</p>
    </div>
    
    <div class="auth-form">
        <div class="form-header">
            <h1>Create Admin Account</h1>
            <p>Fill in the details to register as an administrator</p>
        </div>

        @if ($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #f5c6cb;">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('register-admin.submit') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    placeholder="John Doe" 
                    required
                    autocomplete="name"
                    class="{{ $errors->has('name') ? 'error' : '' }}"
                >
                @error('name')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="admin@example.com" 
                    required
                    autocomplete="email"
                    class="{{ $errors->has('email') ? 'error' : '' }}"
                >
                @error('email')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Create a strong password (min. 6 characters)" 
                    required
                    minlength="6"
                    autocomplete="new-password"
                    class="{{ $errors->has('password') ? 'error' : '' }}"
                >
                @error('password')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Re-enter your password" 
                    required
                    minlength="6"
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn">Register as Admin</button>
        </form>

        <div class="divider">
            <span>OR</span>
        </div>

        <div class="form-footer">
            <p style="margin-bottom: 0.5rem;">
                Already have an account? <a href="{{ route('login') }}">Login</a>
            </p>
            <p style="margin: 0;">
                Regular user? <a href="{{ route('register') }}">Register here</a>
            </p>
        </div>
    </div>
</div>
@endsection






