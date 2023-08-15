<!DOCTYPE html>
<html>

<head>
    <title>Employee detailed Information</title>

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
            padding: 15px;
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
            <h2> {{ trans('messages.employee_info') }}</h2>
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
                    <td class="text-primary">{{ trans('messages.employee_id') }}</td>
                    <td><input type="text" name="employee_id" id="employee_id" class="form-control" value="{{$employee->employee_id}}" disabled></td>
                </tr>
                <tr>
                    <td class="text-primary">{{ trans('messages.employee_code') }}</td>
                    <td><input type="text" class="form-control" name="employee_code" id="employee_code" value="{{$employee->employee_code}}" disabled></td>
                </tr>
                <tr>
                    <td class="text-primary">{{ trans('messages.employee_name') }}</td>
                    <td><input type="text" class="form-control" name="employee_name" value="{{$employee->employee_name}}" disabled></td>
                </tr>
                <tr>
                    <td class="text-primary">{{ trans('messages.nrc_number') }}</td>
                    <td><input type="text" class="form-control" name="nrc_number" id="nrc_number" value="{{$employee->nrc_number}}" disabled></td>
                </tr>
                <tr>
                    <td class="text-primary">{{ trans('messages.employee_email') }}</td>
                    <td><input type="text" class="form-control" name="email_address" id="email_address" value="{{$employee->email_address}}" disabled></td>
                </tr>
                <tr>
                    <td class="text-primary">{{ trans('messages.gender') }}</td>
                    <td>
                        <input type="radio" name="gender" value="1" @if ($employee->gender == '1') checked @endif disabled> {{ trans('messages.male') }}
                        <input type="radio" name="gender" value="2" @if ($employee->gender == '2') checked @endif disabled> {{ trans('messages.female') }}
                    </td>
                </tr>
                </tr>
                <tr>
                    <td class="text-primary">{{ trans('messages.date_of_birth') }}</td>
                    <td><input type="date" class="form-control" name="date_of_birth" id="date_of_birth" value="{{$employee->date_of_birth}}" disabled></td>
                </tr>
                <tr>
                    <td class="text-primary">{{ trans('messages.marital_status') }}</td>
                    <td><select name="marital_status" class="form-control" disabled>
                            <option>--Select--</option>
                            <option value="1" @if ($employee->marital_status == '1') selected @endif > {{ trans('messages.single') }} </option>
                            <option value="2" @if ($employee->marital_status == '2') selected @endif > {{ trans('messages.married') }} </option>
                            <option value="3" @if ($employee->marital_status == '3') selected @endif > {{ trans('messages.divorce') }} </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="text-primary">{{ trans('messages.address') }}</td>
                    <td><textarea class="form-control" name="address" id="address" disabled>{{$employee->address}}</textarea></td>
                </tr>
            </table>
        </div>
    </div>
    <br><br>
</body>