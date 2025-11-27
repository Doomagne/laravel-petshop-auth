@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="color: #333; font-size: 2rem;">Admin Dashboard</h1>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background: #e74c3c; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    Logout
                </button>
            </form>
        </div>

        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 2rem; border: 1px solid #c3e6cb;">
            <strong>Welcome, {{ Auth::user()->name }}!</strong> You are logged in as an administrator.
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 10px;">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.2rem;">Admin Panel</h3>
                <p style="margin: 0; opacity: 0.9;">Manage your application</p>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.5rem; border-radius: 10px;">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.2rem;">Users</h3>
                <p style="margin: 0; opacity: 0.9;">Manage users</p>
            </div>
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 1.5rem; border-radius: 10px;">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.2rem;">Settings</h3>
                <p style="margin: 0; opacity: 0.9;">Configure settings</p>
            </div>
        </div>

        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;">
            <h2 style="color: #333; margin-bottom: 1rem;">Admin Information</h2>
            <div style="display: grid; gap: 1rem;">
                <div>
                    <strong>Name:</strong> {{ Auth::user()->name }}
                </div>
                <div>
                    <strong>Email:</strong> {{ Auth::user()->email }}
                </div>
                <div>
                    <strong>Role:</strong> Administrator
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


