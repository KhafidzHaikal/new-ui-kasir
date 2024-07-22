<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login </title>
    <!-- Favicon icon -->
    {{-- <link href="/css/style.css" rel="stylesheet"> --}}
    {{-- <style>
        h3 {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-top: 2rem;
        }

        label {
            display: flex;
            justify-content: center;
        }

        input {
            text-align: center;
        }

        @media (max-width: 768px) {
            img {
                display: none;
            }

            h3 {
                text-align: center;
                margin-left: 50px;
            }

            h4 {
                text-align: center;
                margin-left: 50px;
            }
        }
    </style> --}}
</head>

<body class="h-100">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: sans-serif;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            overflow: hidden
        }

        @media screen and (max-width: 600px; ) {
            body {
                background-size: cover;
                : fixed
            }
        }

        #particles-js {
            height: 100%
        }

        .loginBox {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            min-height: 200px;
            /* background: #000000; */
            box-shadow: 0px 0px 10px #888888;
            border-radius: 10px;
            padding: 40px;
            box-sizing: border-box
        }

        .user {
            margin: 0 auto;
            display: block;
            margin-bottom: 20px
        }

        h3 {
            margin: 0;
            padding: 0 0 20px;
            color: #59238F;
            text-align: center
        }

        .loginBox input {
            width: 100%;
            margin-bottom: 20px
        }

        .loginBox input[type="email"],
        .loginBox input[type="password"] {
            border: none;
            border-bottom: 1px solid #262626;
            outline: none;
            height: 40px;
            color: #000000;
            background: transparent;
            font-size: 16px;
            padding-left: 20px;
            box-sizing: border-box
        }

        .loginBox input[type="email"]:hover,
        .loginBox input[type="password"]:hover {
            color: #000000;
            border: 0.5px solid #000000;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(128, 128, 128, 0.145), 0 0 10px rgba(101, 101, 101, 0.2), 0 0 15px rgba(139, 139, 139, 0.1), 0 2px 0 rgba(0, 0, 0, 0)
        }

        .loginBox input[type="email"]:focus,
        .loginBox input[type="password"]:focus {
            border-bottom: 1px solid #000000
        }

        .inputBox {
            position: relative
        }

        .inputBox span {
            position: absolute;
            top: 10px;
            color: #262626
        }

        .loginBox button[type="submit"] {
            border: none;
            outline: none;
            height: 40px;
            width: 100%;
            font-size: 16px;
            background: #59238F;
            color: #fff;
            border-radius: 20px;
            cursor: pointer
        }

        .loginBox a {
            color: #262626;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            display: block
        }

    </style>
    {{-- <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <form action={{ route('login') }} method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="text" class="form-control" name="email">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="loginBox"> <img class="user" src={{ asset('img/logo.png') }} height="100px" width="100px">
        <h3>LOGIN</h3>
        <form action={{ route('login') }} method="POST">
            @csrf
            <div class="inputBox">
                <input id="email" type="email" name="email" placeholder="Email">
                <input id="pass" type="password" name="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="/vendor/global/global.min.js"></script>
    <script src="/js/quixnav-init.js"></script>
    <script src="/js/custom.min.js"></script>

</body>

</html>
