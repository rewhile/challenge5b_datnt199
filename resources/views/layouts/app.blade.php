<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Student Management System') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --bs-primary: #000000;
            --bs-secondary: #666666;
            --bs-success: #212529;
            --bs-info: #444444;
            --bs-warning: #888888;
            --bs-danger: #333333;
            --bs-light: #f8f9fa;
            --bs-dark: #212529;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #333;
            background-color: #f9f9f9;
        }
        
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .main-content {
            flex: 1;
        }
        
        .footer {
            margin-top: auto;
            padding: 1rem 0;
            background-color: #ffffff;
            border-top: 1px solid #eaeaea;
        }
        
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-radius: 8px;
        }
        
        .card-header {
            background-color: #000;
            color: #fff;
            border-radius: 8px 8px 0 0 !important;
            font-weight: 600;
        }
        
        .btn {
            border-radius: 4px;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: #000;
            border-color: #000;
        }
        
        .btn-primary:hover {
            background-color: #333;
            border-color: #333;
        }
        
        .btn-secondary {
            background-color: #666;
            border-color: #666;
        }
        
        .btn-info {
            background-color: #444;
            border-color: #444;
            color: white;
        }
        
        .btn-danger {
            background-color: #333;
            border-color: #333;
        }
        
        .btn-outline-primary {
            border-color: #000;
            color: #000;
        }
        
        .btn-outline-primary:hover {
            background-color: #000;
            color: #fff;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }
        
        .bg-primary {
            background-color: #000 !important;
        }
        
        .bg-danger {
            background-color: #333 !important;
        }
        
        .bg-info {
            background-color: #444 !important;
        }
        
        .bg-success {
            background-color: #212529 !important;
        }
        
        .table {
            color: #333;
        }
        
        .dropdown-menu {
            border-radius: 4px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border: 1px solid #eaeaea;
        }
        
        .form-control:focus {
            border-color: #000;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.1);
        }
        
        /* Dark navbar */
        .navbar-dark {
            background-color: #000 !important;
        }
        
        /* Override Bootstrap's default focus styles */
        .btn:focus, .btn:active:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Student Management System') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('assignments.index') }}">Assignments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('challenges.index') }}">Challenges</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->username }} ({{ ucfirst(Auth::user()->role) }})
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}">
                                        My Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 main-content">
            @yield('content')
        </main>
        
        <footer class="footer">
            <div class="container">
                <div class="text-center text-muted">
                    <p class="mb-0">© {{ date('Y') }} Student Management System</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
