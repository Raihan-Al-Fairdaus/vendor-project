<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DNA Advertising</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<div class="login-page">

    <!-- Background -->
    <div class="bg-gradient"></div>
    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>
    <div class="bg-circle circle-3"></div>

    <div class="particle particle-1"></div>
    <div class="particle particle-2"></div>
    <div class="particle particle-3"></div>
    <div class="particle particle-4"></div>
    <div class="particle particle-5"></div>
    <div class="particle particle-6"></div>

    <div class="login-container">

        <!-- LEFT PANEL -->
        <div class="login-left">

            <div class="gold-line"></div>

            <div class="brand">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="logo"
                    alt="DNA Advertising">

                <h1>
                    <span>DNA</span>
                    Advertising
                </h1>

                <p>
                    Secure Vendor Management Portal
                </p>

            </div>

            <div class="mobile-login-title">

    <h2>Administrator Login</h2>

    <p>
        Secure access to the DNA Advertising dashboard.
    </p>

</div>

            <div class="welcome-text desktop-only">

    <h2>
        Welcome Back!
    </h2>

    <p>
        Access the administrator dashboard to manage vendors,
        monitor registrations and keep every partnership running
        professionally.
    </p>

</div>

            <div class="left-footer desktop-only">

                <div class="feature">

                    <div class="icon">
                        ✓
                    </div>

                    <div>

                        <h4>Secure Login</h4>

                        <span>
                            Protected authentication system
                        </span>

                    </div>

                </div>

                <div class="feature">

                    <div class="icon">
                        ★
                    </div>

                    <div>

                        <h4>Premium Experience</h4>

                        <span>
                            Elegant & modern interface
                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT PANEL -->

        <div class="login-right">

            <div class="login-box">

            <div class="mobile-brand">

    <img
        src="{{ asset('images/logo.png') }}"
        class="mobile-logo"
        alt="DNA Advertising">

    <h1>
        <span>DNA</span> Advertising
    </h1>

    <p>
        Secure Vendor Management Portal
    </p>

</div>


                <a
                    href="{{ route('home') }}"
                    class="back-home">

                    ← Back to Homepage

                </a>

                <div class="heading">

                    <h2>
                        Login
                    </h2>

                    <p>
                        Please login using your administrator account.
                    </p>

                </div>

                @if ($errors->any())

                    <div class="alert-error">

                        {{ $errors->first() }}

                    </div>

                @endif

                <form action="{{ route('admin.login.post') }}" method="POST">

    @csrf

    <div class="form-group">

        <label>Email Address</label>

        <div class="input-box">

            <i class="fa-solid fa-envelope"></i>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
                required
                autofocus>

        </div>

    </div>

    <div class="form-group">

        <label>Password</label>

        <div class="password-box">

            <i class="fa-solid fa-lock"></i>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required>

            <button
                type="button"
                id="togglePassword"
                class="toggle-password">
                <link rel="stylesheet" href="{{ asset('css/login.css') }}">
                <i class="fa-solid fa-eye"></i>

            </button>

        </div>

    </div>

    
    <div class="remember-row">

        <label class="remember">

            <input
                type="checkbox"
                name="remember">

            <span>
                Remember this device

            </span>

        </label>

    </div>

    <button
        type="submit"
        class="login-btn">

        Login to Dashboard

    </button>

</form>
                <div class="copyright">

                    © {{ date('Y') }}
                    DNA Advertising.
                    All Rights Reserved.

                </div>

            </div>

        </div>

    </div>

</div>

<script>

const togglePassword=document.getElementById("togglePassword");

const password=document.getElementById("password");

togglePassword.addEventListener("click",()=>{

    const icon=togglePassword.querySelector("i");

    if(password.type==="password"){

        password.type="text";

        icon.classList.remove("fa-eye");

        icon.classList.add("fa-eye-slash");

    }else{

        password.type="password";

        icon.classList.remove("fa-eye-slash");

        icon.classList.add("fa-eye");

    }

});

document.addEventListener("mousemove",(e)=>{

    const card=document.querySelector(".login-container");

    const x=(window.innerWidth/2-e.pageX)/40;

    const y=(window.innerHeight/2-e.pageY)/40;

    card.style.transform=
    `rotateY(${x}deg) rotateX(${-y}deg)`;

});

document.addEventListener("mouseleave",()=>{

    document.querySelector(".login-container")
    .style.transform="rotateX(0) rotateY(0)";

});

</script>

</body>

</html>