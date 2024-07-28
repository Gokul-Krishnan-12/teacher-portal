<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
    <div class="container">
        <div class="login-form">
            <h2>Teacher Portal Login</h2>
            <form id="login-form" method="POST">
                <div class="input-container">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required placeholder="Password">
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="additional-links">
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>
            <div id="error-message" class="error-message"></div>
        </div>
    </div>
    <script src="/js/auth.js"></script>
    <style>
        .container {
            max-width: 400px;
            width: 100%;
            margin: 10% auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .login-form {
            padding: 1rem;
            border-radius: 6px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .input-container {
            position: relative;
            margin-bottom: 10px;
        }

        .input-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100%);
            padding: 12px 10px 12px 30px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .additional-links {
            margin-top: 10px;
            text-align: center;
        }

        .forgot-password {
            color: #777;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        @media (max-width: 480px) {
            .container {
                max-width: 300px;
            }
        }
    </style>
</body>

</html>