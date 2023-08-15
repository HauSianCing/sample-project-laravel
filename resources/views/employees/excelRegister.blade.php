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
        padding: 10px 20px;
        text-align: center;
        display: inline-block;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
        border: 1px solid #000;

    }

    .btnExcel a {
        color: black;
    }

    .btnExcel a:hover {
        text-decoration: none;
        color: black;
    }

    .btnExcel:hover {
        background-color: #eaeaea;
        border: none;
        box-shadow: 1px 1px #000;

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
        padding-left: 7%;
        padding-right: 7%;
        
    }

    #btnSave {
        padding-left: 10%;
        padding-right: 10%;
    }

    .btn-login {
        width: 200px;
        height: 40px;
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
        margin: 10px 0;
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

@if (session()->has('validation_import_error'))
@php
$validationErrors = session()->get('validation_import_error');
@endphp
<div class="alert alert-danger" style="margin-left: 10%; margin-right:10%; text-align:center">
    <span class="close-button">&times;</span>
    @if (is_array($validationErrors) && count($validationErrors) > 0)
    @foreach ($validationErrors as $errorMessages)
    @if (is_array($errorMessages))
    @foreach ($errorMessages as $errorMessage)
    {{ $errorMessage }}<br>
    @endforeach
    @else
    {{ $errorMessages }}<br>
    @endif
    @endforeach
    @elseif ($validationErrors)
    {{ $validationErrors }}<br>
    @endif
</div>

@elseif (session()->has('status'))
<div class="alert alert-success" style="margin-left: 10%; margin-right:10%; text-align:center">
    <span class="close-button">&times;</span>
    {{ session()->get('status') }}
</div>
@endif
@include('layouts.nav')
<br>
</head>

<body>
    <form id="registrationForm">
        <div class="row pb-2">
            <div class="radio-group">
                <label>
                    <input type="radio" name="registrationType" value="normal" onclick="handleRadioClick(this)">
                    Normal Register
                </label>
                <label>
                    <input type="radio" name="registrationType" value="excel" onclick="handleRadioClick(this)" checked>
                    Excel Register
                </label>
            </div>
        </div>
    </form>
    <div id="excelRegisterView">
        <!-- Content for Excel Register view -->
        <br>
        <h2>Excel Register</h2>
        <div class="btnExcel">
            <a href="{{route('employees.export-excels')}}"><i style="font-size:24px" class="fa"> &#xf019; </i> Download Excel Form</a>
        </div>
        <div class="container">
            <form action="{{route('employees.import-excels')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="excelFile">Upload Excel File</label>
                            <br><br>
                            <input type="file" name="excelFile" id="excelFile" class="form-control-file" style="display: none;">
                            <button type="button" id="chooseFileBtn" class="btn">
                                <i id="fileIcon" class="fa fa-file"></i></button>
                            <span id="selectedFileName"></span>

                        </div>
                    </div>
                    <br>
                    <button type="submit" name="btnUpload" id="btnUpload" class="btn btn-primary" > Upload </button>
                    <br>
                </div>
            </form>

        </div>
        <br><br>
    </div>

</body>
<script>
    function handleRadioClick(radio) {
        if (radio.value === 'normal') {
            // Redirect to the normal registration route
            window.location.href = '/employees/register';
        } else if (radio.value === 'excel') {
            // Redirect to the excel registration route
            window.location.href = '/employees/register/excel-registers';
        }
    }
    const fileInput = document.getElementById('excelFile');
    const chooseFileBtn = document.getElementById('chooseFileBtn');
    const selectedFileName = document.getElementById('selectedFileName');

    // Add click event listener to the button
    chooseFileBtn.addEventListener('click', function() {
        fileInput.click(); // Programmatically trigger the file selection dialog
    });

    // Add change event listener to the file input
    fileInput.addEventListener('change', function() {
        const fileName = this.value.split('\\').pop(); // Extract only the file name from the file path
        selectedFileName.textContent = fileName; // Display the selected file name
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