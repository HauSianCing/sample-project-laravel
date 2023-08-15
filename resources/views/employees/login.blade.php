<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        input[type="password"]::-ms-reveal {
            display: none;
        }

        html {
            height: 100%;
        }

        body {
            height: 100%;
            background-image: url("/photo/dark_desk.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        h1 {
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            padding-left: 7%;
            padding-bottom: 7%;
            padding-right: 7%;
            text-align: center;
            background-color: #faf5b4;
            border-radius: 15px;
            border: none;
            box-shadow: 10px #cccccc;
        }

        .form-group {
            padding: 15px;
        }

        .btn-login .btn {
            width: 150px;
            height: 40px;
        }

        .close-button {
            position: absolute;
            top: 0;
            right: 0;
            padding: 5px;
            cursor: pointer;
        }

        .close-button:hover {
            color: #721c24;
            font-weight: bold;
        }
    </style>
    @extends('layouts.app')

    <title>Login</title>


</head>
<div class="loginClass">

    <body>
        <br><br>
        <div class="container">

            <div class="card">
                <h1> Employee Registration </h1>
                <br><br>
                @if($errors->any())
                <div class="alert alert-danger" style="margin-left: 10%;margin-right:10%;text-align:center">
                    <span class="close-button">&times;</span>
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    <br>
                    @endforeach
                </div>
                @endif
                <form method="post" action="{{ route('employees.check')}}">
                    @csrf
                    <div class="form-group">
                        <br>
                        <input id="employee_id" type="text" class="form-control" name="employee_id" value="{{ old('employee_id') }}" placeholder="Employee ID" autofocus>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input id="password" type="password" class="form-control" value="{{ old('emp_password') }}" placeholder="Password" name="emp_password">
                            <div class="input-group-append">
                                <button id="togglePassword" class="btn btn-outline-secondary" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="btn-login">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var togglePassword = document.querySelector('#togglePassword');
                var password = document.querySelector('#password');

                togglePassword.addEventListener('click', function() {
                    var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);

                    // Toggle the eye icon based on password visibility
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            });
            const closeButtons = document.querySelectorAll('.close-button');

            // Add click event listener to each close button
            closeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Find the parent error-message element and hide it
                    const errorMessage = this.parentNode;
                    errorMessage.style.display = 'none';
                });
            });
        </script>

    </body>
</div>

</html>