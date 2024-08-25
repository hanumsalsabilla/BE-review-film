<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            text-align: center;
        }
        h3 {
            color: #333;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
            text-align: center;
        }
        .otp-code {
            display: block;
            width: 50%; 
            margin: 0 auto; 
            padding: 10px;
            font-size: 20px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat {{$name}}</h2>
        <h3>Kode OTP Anda</h3>
        <p>Silakan gunakan kode OTP berikut untuk verifikasi:</p>
        <input type="text" class="otp-code" value={{$otp}} readonly>
        <p>Kode berlaku 5 menit dari sekarang.</p>
        <p><em>Jangan beritahu siapapun tentang kode ini.</em></p>
    </div>
</body>
</html>
