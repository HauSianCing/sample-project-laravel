<!DOCTYPE html>
<html>

<head>
    <title>Update Employee Information</title>

    @extends('layouts.app')

    <style>
        img {
            border-radius: 50%;
        }

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
    @include('layouts.nav')
</head>

<body>
    <div class="container">
        <div class="card">
            <h2> {{ trans('messages.employee_info') }} </h2>
            <form action="{{route('employees.update',$employee->id)}}" method="POST" enctype="multipart/form-data">
                <div class="searchTB">
                    @csrf
                    @method('PUT')
                    <table>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <br>
                                @php
                                $photo = $employeePhoto ? asset('photo/'.$employeePhoto) : asset('photo/avatar.png');
                                @endphp
                                <img src="{{ $photo }}" alt="Employee Photo" width="150px" height="150px" style="display: block; margin: 0 auto;">
                            </td>
                        </tr>
                        <tr>
                            <td><label class="text-primary">{{ trans('messages.employee_id') }}</label></td>
                            <td><input type="text" name="employee_id" id="employee_id" class="form-control" value="{{$employee->employee_id}}" readonly></td>
                        </tr>
                        <tr>
                            <td><label class="text-primary">{{ trans('messages.employee_code') }}<span>*</span></label></td>
                            <td><input type="text" class="form-control" name="employee_code" id="employee_code" value="{{$employee->employee_code}}">
                                @error('employee_code')
                                <span>
                                    {{ $message }}
                                </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label class="text-primary">{{ trans('messages.employee_name') }}<span>*</span></label></td>
                            <td><input type="text" class="form-control" name="employee_name" value="{{$employee->employee_name}}">
                                @error('employee_name')
                                <span>
                                    {{ $message }}
                                </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td> <label class="text-primary">{{ trans('messages.nrc_number') }}<span>*</span></label></td>
                            <td><input type="text" class="form-control" name="nrc_number" id="nrc_number" value="{{$employee->nrc_number}}">
                                @error('nrc_number')
                                <span>
                                    {{ $message }}
                                </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label class="text-primary">{{ trans('messages.employee_email') }}<span>*</span></label></td>
                            <td><input type="text" class="form-control" name="email_address" id="email_address" value="{{$employee->email_address}}">
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
                                    <input type="radio" name="gender" value="1" @if ($employee->gender == '1') checked @endif> {{ trans('messages.male') }}
                                </label>
                                <label>
                                    <input type="radio" name="gender" value="2" @if ($employee->gender == '2') checked @endif> {{ trans('messages.female') }}
                                </label>

                            </td>
                        </tr>
                        </tr>
                        <tr>
                            <td><label class="text-primary">{{ trans('messages.date_of_birth') }}<span>*</span></label></td>
                            <td><input type="date" class="form-control" name="date_of_birth" id="date_of_birth" value="{{$employee->date_of_birth}}">
                                @error('date_of_birth')
                                <span>
                                    {{ $message }}
                                </span>
                                @enderror
                            </td>

                        </tr>
                        <tr>
                            <td><label class="text-primary">{{ trans('messages.marital_status') }}</label></td>
                            <td><select name="marital_status" class="form-control">
                                    <option value=" ">--Select--</option>
                                    <option value="1" @if ($employee->marital_status == '1') selected @endif > {{ trans('messages.single') }} </option>
                                    <option value="2" @if ($employee->marital_status == '2') selected @endif > {{ trans('messages.married') }} </option>
                                    <option value="3" @if ($employee->marital_status == '3') selected @endif > {{ trans('messages.divorce') }} </option>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td><label class="text-primary">{{ trans('messages.address') }}</label></td>
                            <td><textarea class="form-control" name="address" id="address">{{$employee->address}}</textarea>
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
                                <input type="file" class="form-control" name="photo" id="photo">
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
                    <button type="submit" class="btn btn-outline-danger"> {{ trans('messages.update') }} </button>
            </form>
        </div>
    </div>
    <br><br>
    <script>
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
    </script>

</body>