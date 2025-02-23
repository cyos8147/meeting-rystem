<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        h1 {
            font-size: 50px;
            color: #dc3545;
            margin: 0 0 20px;
            animation: fadeIn 2s ease-in-out;
        }
        p {
            font-size: 18px;
            color: #6c757d;
            margin: 0 0 20px;
            animation: slideUp 2s ease-in-out;
        }
        a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            animation: bounce 2s infinite;
        }
        a:hover {
            background: #0056b3;
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
        @keyframes slideUp {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
    </style>
</head>
<body>
    <div>
        <h1>403 - Forbidden</h1>
        <p>You do not have permission to access this page.</p>
        <a href="{{ route('login') }}">Return to Login Page</a>
    </div>
</body>
</html>
