<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login | POS Bengkel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
        :root { --primary-color: #5156be; --bg-soft: #f4f7fe; }
        body { 
            background-color: var(--bg-soft); 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Inter', sans-serif; 
        }
        .login-card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            width: 100%; 
            max-width: 420px; 
            background: #fff; 
        }
        .card-header-custom { 
            background-color: rgba(81, 86, 190, 0.1); 
            border-radius: 15px 15px 0 0; 
            padding: 35px 20px; 
            text-align: center; 
        }
        .login-icon-circle {
            width: 60px; height: 60px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 4px 12px rgba(81, 86, 190, 0.3);
        }
        .form-label { font-size: 0.85rem; color: #6c757d; }
        .input-group-text { background-color: #f8f9fa; border-right: none; color: #adb5bd; }
        .form-control { 
            border-radius: 8px; 
            padding: 11px 15px; 
            background: #f8f9fa; 
            border: 1px solid #e9ecef;
            font-size: 0.95rem;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: none;
        }
        .btn-login { 
            background: var(--primary-color); 
            border: none; 
            border-radius: 8px; 
            padding: 12px; 
            font-weight: 600; 
            color: white; 
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover { background: #3d42a5; transform: translateY(-1px); }
        .bg-soft-primary { background-color: rgba(81, 86, 190, 0.1); }
        .text-primary-custom { color: var(--primary-color); }
    </style>
</head>
<body>

<div class="login-card">
    <div class="card-header-custom">
        <div class="login-icon-circle">
            <i data-feather="tool"></i>
        </div>
        <h4 class="fw-bold text-primary-custom mb-1">POS Bengkel</h4>
        <p class="text-muted small mb-0">Silakan masuk untuk mengelola bengkel Anda</p>
    </div>

    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger border-0 small d-flex align-items-center" role="alert">
                <i data-feather="alert-circle" class="me-2" style="width: 16px;"></i>
                Username atau password salah.
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold">Email / Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="mail" style="width: 16px;"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@bengkel.com" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label fw-bold">Password</label>
                    <a href="#" class="text-primary-custom small text-decoration-none">Lupa password?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="lock" style="width: 16px;"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="rememberCheck">
                <label class="form-check-label small text-muted" for="rememberCheck">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <button type="submit" class="btn-login w-100">
                Masuk <i data-feather="log-in" style="width: 18px;"></i>
            </button>
        </form>
    </div>

    <div class="card-footer bg-transparent border-0 pb-4 text-center">
        <p class="small text-muted">© 2026 POS Bengkel System</p>
    </div>
</div>

<script>
    feather.replace(); // Inisialisasi Feather Icons
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>