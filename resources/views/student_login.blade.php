<form action="{{ route('login.student') }}" method="POST" id="studentLoginForm">
    @csrf
    <h3>Student Login</h3>

    <!-- Metric Number -->
    <div style="position: relative;">
        <i class="fas fa-user" style="position: absolute; left: 10px; top: 12px; color: #007bff;"></i>
        <input type="text" name="metric_number" placeholder="Metric Number" required>
    </div>

    <!-- Password -->
    <div style="position: relative;">
        <i class="fas fa-lock" style="position: absolute; left: 10px; top: 12px; color: #007bff;"></i>
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <!-- Error Message -->
    @if ($errors->has('error'))
        <div class="error-message">
            {{ $errors->first('error') }}
        </div>
    @endif

    <button type="submit">Login as Student</button>
</form>
