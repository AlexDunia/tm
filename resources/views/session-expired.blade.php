<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Expired | Kaka</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"></script>
    <script>
        // Redirect after 3 seconds
        setTimeout(function() {
            window.location.href = '/login';
        }, 3000);
    </script>
    <style>
        body {
            background: #13121a;
            color: white;
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .logo {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo img {
            height: 40px;
        }

        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: rgba(38, 37, 54, 0.5);
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .icon {
            font-size: 48px;
            color: #C04888;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }

        p {
            margin: 0 0 30px 0;
            opacity: 0.8;
            font-size: 16px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #C04888;
            color: white;
        }

        .btn-secondary {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="logo">
        <a href="/">
            <img src="https://res.cloudinary.com/dnuhjsckk/image/upload/v1746062122/tdlogo_bmlpd8.png" alt="Kaka">
        </a>
    </div>

    <div class="container">
        <div class="card">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <h1>Session Expired</h1>
            <p>Redirecting to login...</p>
            <div class="buttons">
                <a href="/login" class="btn btn-primary">Log In Now</a>
                <a href="/" class="btn btn-secondary">Home</a>
            </div>
        </div>
    </div>
</body>
</html>
