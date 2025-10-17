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

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="you@example.com" 
                    required
                    autocomplete="email"
                >
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
                >
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
