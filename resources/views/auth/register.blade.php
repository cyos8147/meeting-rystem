<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>

    <form action="{{ route('register.submit') }}" method="POST">
        @csrf

        <label for="role">Register as:</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
            <option value="advisor">Advisor</option>
        </select><br>

        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="metric_number">Metric Number:</label>
        <input type="text" name="metric_number" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" required><br>

        <button type="submit">Register</button>
    </form>

    <a href="{{ route('login') }}">Already have an account? Login</a>
</body>
</html>
