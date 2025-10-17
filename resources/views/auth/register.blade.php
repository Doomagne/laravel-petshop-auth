@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="auth-card">
    <div class="auth-image">
        <h2>Join Doomeng Patshop!</h2>
        <p>Create an account and start shopping for the best pet supplies</p>
    </div>
    
    <div class="auth-form">
        <div class="form-header">
            <h1>Create YourAccount</h1>
            <p>Put information here!</p>
        </div>

        <form action="{{ url('/register') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="John Doe" 
                    required
                    autocomplete="name"
                >
            </div>

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
                <label for="phone">Phone Number</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    placeholder="+63 912 345 6789" 
                    autocomplete="tel"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Create a strong password" 
                    required
                    autocomplete="new-password"
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Re-enter your password" 
                    required
                    autocomplete="new-password"
                >
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: start; gap: 0.5rem; margin: 0;">
                    <input type="checkbox" name="terms" required style="width: auto; margin-top: 0.2rem;">
                    <span style="font-weight: normal; font-size: 0.9rem;">
                        I agree to the <a href="#" style="color: #667eea; text-decoration: none;">Terms & Conditions</a> and <a href="#" style="color: #667eea; text-decoration: none;">Privacy Policy</a>
                    </span>
                </label>
            </div>

            <button type="submit" class="btn">Create Account</button>
        </form>

        <div class="divider">
            <span>OR</span>
        </div>

        <div class="form-footer">
            Already have an account? <a href="{{ url('/login') }}">Sign in instead</a>
        </div>
    </div>
</div>
@endsection
