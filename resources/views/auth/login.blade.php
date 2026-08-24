<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SPK Beasiswa Cerdas Humanis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;
             background:linear-gradient(135deg,#0f2744 0%,#1a3a5c 40%,#1e4976 70%,#2563a8 100%);position:relative;overflow:hidden}
        body::before{content:'';position:absolute;inset:0;
            background:radial-gradient(ellipse at 70% 30%,rgba(232,160,32,0.15) 0%,transparent 60%),
                        radial-gradient(ellipse at 20% 80%,rgba(37,99,168,0.3) 0%,transparent 50%)}
        .bg-text{position:absolute;font-family:'Instrument Serif',serif;color:rgba(255,255,255,0.035);
                 font-size:18vw;top:50%;left:50%;transform:translate(-50%,-50%);white-space:nowrap;pointer-events:none;user-select:none}
        .box{position:relative;z-index:2;background:rgba(255,255,255,0.05);backdrop-filter:blur(20px);
             border:1px solid rgba(255,255,255,0.12);border-radius:24px;padding:44px 40px;
             width:410px;max-width:94vw;box-shadow:0 32px 64px rgba(0,0,0,0.4);
             animation:up .6s ease both}
        @keyframes up{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .logo{text-align:center;margin-bottom:28px}
        .logo-icon{width:90px;height:90px;border-radius:50%;background:#fff;
                   display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;
                   box-shadow:0 8px 28px rgba(232,160,32,0.5);padding:5px;overflow:hidden}
        .logo-icon img{width:100%;height:100%;object-fit:contain}
        .logo h1{color:#fff;font-size:1.3rem;font-weight:700;line-height:1.2}
        .logo p{color:rgba(255,255,255,0.5);font-size:0.78rem;margin-top:4px}
        .f-group{margin-bottom:16px}
        .f-group label{display:block;color:rgba(255,255,255,0.7);font-size:0.8rem;font-weight:600;margin-bottom:6px}
        .f-group input{width:100%;padding:11px 14px;border-radius:10px;background:rgba(255,255,255,0.08);
                       border:1px solid rgba(255,255,255,0.15);color:#fff;font-size:0.88rem;font-family:inherit;transition:border-color .2s}
        .f-group input::placeholder{color:rgba(255,255,255,0.3)}
        .f-group input:focus{outline:none;border-color:#e8a020;background:rgba(255,255,255,0.12)}
        .btn-login{width:100%;padding:12px;border-radius:10px;border:none;
                   background:linear-gradient(135deg,#e8a020,#f59e0b);color:#fff;
                   font-size:0.92rem;font-weight:700;font-family:inherit;cursor:pointer;
                   box-shadow:0 4px 16px rgba(232,160,32,0.4);transition:all .2s;margin-top:6px}
        .btn-login:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(232,160,32,0.5)}
        .hint{margin-top:20px;padding:12px;background:rgba(255,255,255,0.05);border-radius:10px;
              color:rgba(255,255,255,0.5);font-size:0.76rem;line-height:1.8;border:1px solid rgba(255,255,255,0.08)}
        .hint strong{color:rgba(255,255,255,0.8)}
        .error{padding:10px 14px;background:rgba(220,38,38,0.15);border:1px solid rgba(220,38,38,0.4);
               border-radius:8px;color:#fca5a5;font-size:0.8rem;margin-bottom:14px;display:flex;align-items:center;gap:8px}
    </style>
</head>
<body>
<div class="bg-text">SPK</div>
<div class="box">
    <div class="logo">
        <div class="logo-icon"><img src="{{ asset('images/logo-usd.png') }}" alt="Logo USD"></div>
        <h1>Beasiswa Cerdas Humanis</h1>
        <p>Sistem Pendukung Pengambilan Keputusan — Metode MOORA</p>
    </div>

    @if($errors->any())
        <div class="error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="f-group">
            <label>Username / Email</label>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
        </div>
        <div class="f-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> &nbsp;Masuk ke Sistem</button>
    </form>

    
</div>
</body>
</html>