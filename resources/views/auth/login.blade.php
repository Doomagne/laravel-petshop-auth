@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-image">
        <h2>Welcome Back!</h2>
        <p>Login to access your account buy something in my store</p>
    </div>
    
    <div class="auth-form">
        <div class="form-header">
            <h1>Sign In</h1>
            <p>Enter your information here to access your account</p>
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

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="you@example.com" 
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
                    placeholder="Enter your password" 
                    required
                    autocomplete="current-password"
                    class="{{ $errors->has('password') ? 'error' : '' }}"
                >
                @error('password')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                    <input type="checkbox" name="remember" style="width: auto;">
                    <span style="font-weight: normal;">Remember me</span>
                </label>
                <a href="#" style="color: #667eea; text-decoration: none; font-size: 0.9rem;">Forgot Password?</a>
            </div>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="divider">
            <span>OR</span>
        </div>

        <div class="form-footer">
            Don't have an account? <a href="{{ url('/register') }}">Create one now</a>
        </div>
    </div>
</div>
@endsection
