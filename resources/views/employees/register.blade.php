<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')

<title>Create</title>
<style>
    #fileIcon {
        font-size: 45px;
    }

    .btnExcel {
        margin-left: 70%;
    }

    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }

    h2 {
        text-align: center;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #btnUpload {
        padding-left: 35%;
        padding-right: 35%;
    }

    #btnSave {
        padding-left: 10%;
        padding-right: 10%;
    }

    .btn-login {
        width: 200px;
        height: 40px;
    }

    ul {
        list-style: none;
        padding: 5%;
        margin: 0;
        float: right;
        margin-right: 10%;
    }

    li {
        float: right;
    }


    .radio-group,
    .radio-group label {
        text-align: center;
        padding-right: 5%;
    }

    .card-body {
        padding: 100%;
    }

    tr,
    td {
        padding: 10px;
    }

    span {
        color: red;
    }

    #selectedFileName {
        color: blue;
    }

    .disabled-link {
        pointer-events: none;
        color: gray;
        text-decoration: none;
    }

    input[type="radio"] {
        margin: 16% 0;
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

@if (session()->has('status'))
<div class="alert alert-success" style="margin-left: 10%; margin-right:10%; text-align:center">
    <span class="close-button">&times;</span>
    {{ session()->get('status') }}
</div>
@endif
@include('layouts.nav')
</head>
<!-- for creating new employee interface -->
<br>

<body>
    <form id="registrationForm">
        <div class="row pb-2">
            <div class="radio-group">
                <label>
                    <input type="radio" name="registrationType" value="normal" onclick="handleRadioClick(this)" checked>
                    {{ trans('messages.normal_register') }}
                </label>
                <label>
                    <input type="radio" name="registrationType" value="excel" onclick="handleRadioClick(this)">
                    {{ trans('messages.excel_register') }}
                </label>
            </div>
        </div>
    </form>

    <div id="normalRegisterView">
        <!-- Content for Normal Register view -->
        <br>
        <h2>{{ trans('messages.normal_register') }}</h2>
        <br>
        <div class="container">
            <br><br>
            <form action="{{route('employees.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <table>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.employee_id') }}</label></td>
                        <td><input type="text" name="employee_id" id="employee_id" class="form-control" value="{{$id}}" readonly>
                            @error('employee_id')
                            <span>
                                {{ $message }}
                            </span>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.employee_code') }}<span>*</span></label></td>
                        <td><input type="text" class="form-control" name="employee_code" id="employee_code" value="{{ old('employee_code') }}">
                            @error('employee_code')
                            <span>
                                {{ $message }}
                            </span>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.employee_name') }}<span>*</span></label></td>
                        <td><input type="text" class="form-control" name="employee_name" value="{{ old('employee_name') }}">
                            @error('employee_name')
                            <span>
                                {{ $message }}
                            </span>

                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.nrc_number') }}<span>*</span></label></td>
                        <td><input type="text" class="form-control" name="nrc_number" id="nrc_number" value="{{ old('nrc_number') }}">
                            @error('nrc_number')
                            <span>
                                {{ $message }}
                            </span>

                            @enderror
                        </td>

                    </tr>
                    <tr>
                        <!-- adding password with auto generate key -->
                        <td><label for="password" class="text-primary">{{ trans('messages.password') }}<span>*</span></label></td>
                        <td>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control" name="password">
                                <div class="input-group-append">
                                    <button id="togglePassword" class="btn btn-outline-secondary" type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                            <span>
                                {{ $message }}
                            </span>
                            @enderror
                        </td>
                        <td>
                            <button type="button" id="generatePassword" class="btn btn-primary">{{ trans('messages.generate_password') }}</button>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.employee_email') }}<span>*</span></label></td>
                        <td><input type="text" class="form-control" name="email_address" id="email_address" value="{{ old('email_address') }}">
                            @error('email_address')
                            <span>
                                {{ $message }}
                            </span>

                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.gender') }}</label></td>
                        <td>
                            <label>
                                <input type="radio" name="gender" value="1" {{ old('gender') == "1" ? 'checked' : '' }}> {{ trans('messages.male') }}
                            </label>
                            <label>
                                <input type="radio" name="gender" value="2" {{ old('gender') == "2" ? 'checked' : '' }}> {{ trans('messages.female') }}
                            </label>
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.date_of_birth') }}<span>*</span></label></td>
                        <td><input type="date" class="form-control" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                            <span>
                                {{ $message }}
                            </span>

                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.marital_status') }}</label></td>
                        <td><select name="marital_status" class="form-control">
                                <option value=" ">--{{ trans('messages.select') }}--</option>
                                <option value="1" {{ old('marital_status') == "1" ? "selected" :''}}> {{ trans('messages.single') }} </option>
                                <option value="2" {{ old('marital_status') == "2" ? "selected" :""}}> {{ trans('messages.married') }} </option>
                                <option value="3" {{ old('marital_status') == "3" ? "selected" : '' }}> {{ trans('messages.divorce') }} </option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.address') }}</label></td>
                        <td><textarea class="form-control" name="address" id="address">{{ old('address') }}</textarea>
                            @error('address')
                            <span>
                                {{ $message }}
                            </span>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="text-primary">{{ trans('messages.upload_photo') }}</label></td>
                        <td>
                            <input type="file" class="form-control" name="photo" id="photo" value="{{ old('photo') }}">
                            <div id="errorContainer">
                                @error('photo')
                                <span>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <!-- Display the preview of the selected photo -->
                            <img id="previewPhoto" src="#" alt="Employee Photo" style="display: none;width:100px;height:100px">
                            <br>
                            <button id="removeButton" style="display: none;" class="btn btn-danger">{{ trans('messages.remove') }}</button>
                        </td>
                    </tr>

                </table>
                <br>
                <button type="submit" name="btnSave" id="btnSave" class="btn btn-primary" style="float: right;margin-right: 30%">{{ trans('messages.save') }}</button>
            </form>
            <br><br><br>
        </div>
        <br><br><br>
    </div>



</body>
<script type="text/javascript">
    function handleRadioClick(radio) {
        if (radio.value === 'normal') {
            // Redirect to the normal registration route
            window.location.href = '/employees/register';
        } else if (radio.value === 'excel') {
            // Redirect to the excel registration route
            window.location.href = '/employees/register/excel-registers';
        }
    }
    // to preview photo while selecting
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('previewPhoto');
    const removeButton = document.getElementById('removeButton');
    const errorContainer = document.getElementById('errorContainer');

    photoInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.addEventListener('load', function() {
                photoPreview.src = reader.result;
                photoPreview.style.display = 'block';
                removeButton.style.display = 'block';
                if (errorContainer) { // Hide the error container
                    errorContainer.style.display = 'none';
                }
            });

            reader.readAsDataURL(file);
        }
        if (errorContainer) { //if error exists, then remove it when new photo is uploaded
            reader.addEventListener('load', function() {
                photoInput.value = '';
            });
        }
    });

    removeButton.addEventListener('click', function() {
        event.preventDefault();
        photoPreview.src = '#';
        photoPreview.style.display = 'none';
        removeButton.style.display = 'none';
        photoInput.value = '';
    });
    // to get auto generate password
    function generatePassword() {
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const specialChars = '!@#$%^&*()_-+';
        const numbers = '0123456789';

        const passwordLength = 8;
        let password = '';

        // Ensure at least one character from each category
        password += uppercase.charAt(Math.floor(Math.random() * uppercase.length));
        password += lowercase.charAt(Math.floor(Math.random() * lowercase.length));
        password += specialChars.charAt(Math.floor(Math.random() * specialChars.length));
        password += numbers.charAt(Math.floor(Math.random() * numbers.length));

        // Fill the remaining characters
        for (let i = 4; i < passwordLength; i++) {
            const characters = uppercase + lowercase + specialChars + numbers;
            password += characters.charAt(Math.floor(Math.random() * characters.length));
        }

        // Shuffle the password characters
        password = password.split('').sort(() => Math.random() - 0.5).join('');

        return password;
    }


    // Attach event listener to the "Generate Password" button
    const generatePasswordButton = document.getElementById('generatePassword');
    generatePasswordButton.addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const generatedPassword = generatePassword(8); // Generate a 8-character password
        passwordField.value = generatedPassword;
    });

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

</html>