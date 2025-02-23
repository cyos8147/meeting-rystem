<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin/login.css') }}" rel="stylesheet"> <!-- Add custom CSS -->
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
     
        <!-- Display validation errors -->
    
        <!-- Login Form -->
          <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf <!-- CSRF protection -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="" required="">
            </div>
    
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required="">
                    <button type="button" class="" onclick="togglePassword()">
                        <i id="toggle-icon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
    
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
    
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/login.js') }}"></script> <!-- Add custom JavaScript -->
</body>

</html>