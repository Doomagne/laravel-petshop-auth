<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Doomeng's Pet Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo span {
            font-size: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .auth-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .auth-image::before {
            content: '🐾';
            position: absolute;
            font-size: 15rem;
            opacity: 0.1;
            top: -2rem;
            right: -2rem;
        }

        .auth-image h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            z-index: 1;
        }

        .auth-image p {
            font-size: 1.1rem;
            text-align: center;
            line-height: 1.6;
            z-index: 1;
        }

        .pet-icons {
            font-size: 3rem;
            margin-top: 2rem;
            z-index: 1;
        }

        .auth-form {
            padding: 3rem;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h1 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }

        .form-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .divider {
            margin: 1.5rem 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            color: #999;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .auth-card {
                grid-template-columns: 1fr;
            }

            .auth-image {
                display: none;
            }

            .navbar-content {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body class="antialiased">
    @if(request()->is('login') || request()->is('register') || request()->is('register-admin'))
        <div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; flex-direction: column;">
            <nav class="navbar">
                <div class="navbar-content">
                    <div class="logo">
                        <span>🐾</span> Doomeng
                    </div>
                    <ul class="nav-links">
                        <li><a href="/">Home</a></li>
                        <li><a href="#">Shop</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
            </nav>
            <div class="container">
                @yield('content')
            </div>
        </div>
    @else
        <div class="antialiased bg-gray-100 text-gray-800">
            @yield('content')
        </div>
    @endif
    @yield('scripts')
</body>
</html>