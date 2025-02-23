<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    </head>
    
    <body>

        <h3>Login for Students and Advisors</h3>

        <div class="container">
            <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
                @csrf

                <!-- Email or Metric Number Input -->
                <label for="login_input"></label>
                <div style="position: relative;">
                    <i class="fas fa-user" style="position: absolute; left: 10px; top: 12px; color: #007bff;"></i>
                    <input type="text" id="login_input" name="login_input" placeholder="Email or Metric Number" required>
                </div>

                <!-- Password Input -->
                <label for="password"></label>
                <div style="position: relative;">
                    <i class="fas fa-lock" style="position: absolute; left: 10px; top: 12px; color: #007bff;"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 10px; top: 12px; cursor: pointer; color: #007bff;"></i>
                </div>

                @if ($errors->has('error'))
                    <div class="error-message">
                        {{ $errors->first('error') }}
                    </div>
                @endif

                <button type="submit">Login</button>
            </form>

            <hr>

            <a href="{{ route('admin.login') }}">
                <button class="admin-login-btn">Login as Admin</button>
            </a>
        </div>

        <!-- JavaScript for Password Toggle -->
        <script>
            document.getElementById('togglePassword').addEventListener('click', function() {
                let passwordField = document.getElementById('password');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            });
        </script>
    </body>
</html>
