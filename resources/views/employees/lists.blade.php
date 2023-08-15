<!DOCTYPE html>
<html>

<head>

    <title>Lists</title>

    @extends('layouts.app')

    <style>
        #btn-search::before {
            content: "\f002";
            font-family: "FontAwesome";
            margin-right: 5px;
            font-size: 12px;
        }

        #btn-reset::before {
            content: "\f021";
            font-family: "FontAwesome";
            margin-right: 5px;
            font-size: 12px;
        }

        .btnClass {
            float: right;
            margin-right: 7%;
        }

        .btnClass td {
            padding-left: 10px;
        }

        .btnDownload {
            background-color: transparent;
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            border: 2px solid #000;


        }

        .btnDownload a {
            color: black;
        }

        .btnDownload a:hover {
            text-decoration: none;
            color: black;
        }

        .btnDownload:hover {
            background-color: #eaeaea;
            border: none;
            box-shadow: 1px 1px grey;

        }

        .buttonTB {
            background-color: transparent;
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            border: 1px solid #000;

        }

        .searchTB {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-bottom: 3%;
        }

        .searchTB td {
            padding: 2%;
        }


        .searchTB td #btn-reset {
            margin-left: 20%;
        }


        .link {
            padding-top: 2%;
            margin-left: 25%;
        }

        body {
            padding-bottom: 20px;
        }

        .col-sm-2 {
            margin-left: 50px;
        }

        .data-list {
            margin-left: 15%;
            margin-right: 15%;
            width: 70%;
        }

        .button-list {
            margin-left: 25%;
            width: 35%;
        }

        .data-list td,
        .data-list th {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .table th {
            font-size: 18px;
            text-align: center;
        }

        .table td {
            text-align: center;
            padding: 7%;
        }

        i {
            font-size: 15px;
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
        #employeeID, #employeeName, #employeeEmail, #employeeCode {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
    </style>
    @if(session()->has('status'))
    <div class="alert alert-success" style="margin-left: 10%; margin-right:10%; text-align:center">
        <span class="close-button">&times;</span>
        {{ session()->get('status') }}
    </div>
    @endif
    @if(session()->has('errors'))
    <div class="alert alert-danger" style="margin-left: 10%; margin-right:10%; text-align:center">
        <span class="close-button">&times;</span>
        {{ session()->get('errors') }}
    </div>
    @endif
</head>
@include('layouts.nav')
<br><br>


<body>

    <h2>{{ trans('messages.employee_list') }}</h2>
    <br>
    <!-- search by employee_id,employee_name,employee_code and employee_email -->
    <form action="{{route('employees.lists')}}" method="get" id="searchForm">
        <div class="searchTB">
            <br>
            <table>
                <tr>
                    <td><input id="employeeID" type="text" name="employeeID" placeholder="{{ trans('messages.enter_employee_id') }}" value="{{request()->employeeID??''}}" class="form-control" autofocus></td>
                    <td><input id="employeeName" type="text" name="employeeName" placeholder="{{ trans('messages.enter_employee_name') }}" value="{{request()->employeeName??''}}" class="form-control"></td>
                </tr>
                <tr>
                    <td><input id="employeeCode" type="text" name="employeeCode" placeholder="{{ trans('messages.enter_employee_code') }}" value="{{request()->employeeCode??''}}" class="form-control"></td>
                    <td><input id="employeeEmail" type="text" name="employeeEmail" placeholder="{{ trans('messages.enter_employee_email') }}" value="{{request()->employeeEmail??''}}" class="form-control"></td>
                    <td><button id="btn-search" type="submit" class="btn btn-primary" onclick="searchInput(event)">{{ trans('messages.search') }} </button></td>
                    <td><a href="{{route('employees.lists')}}" id="btn-reset" class="btn btn-primary">{{ trans('messages.reset') }}</a></td>
                </tr>
            </table>
        </div>
    </form>
    @if ($employees->isEmpty())
    <div class="btnClass">
        <table>
            <tr>
                <td colspan="2">
                    <button type="submit" class="buttonTB" disabled>
                        <i style="font-size:19px" class="fa"> &#xf019; </i> {{ trans('messages.pdf_download') }} </button>
                </td>
                <td colspan="2">
                    <button type="submit" class="buttonTB" disabled>
                        <i style="font-size:19px" class="fa"> &#xf019; </i> {{ trans('messages.excel_download') }} </button>
                </td>
            </tr>
        </table>
        <br>
    </div>
    <div class="container">
        <div style="margin-top: 5%;">
            <h4 style="text-align: center;color: #aeaeae"> {{ trans('messages.no_employee_data_found') }}</h4>
            <br><br>
        </div>
    </div>
    @else
    <span style="margin-left: 10%;">{{ trans('messages.total_rows') }}: {{ $employees->total() }} row(s)</span>
    <div class="btnClass">
        <table>
            <tr>
                <td colspan="2">
                    <form action="{{route('employees.pdf-search-downloads')}}" method="GET">
                        <input type="text" name="employeeID" value="{{request()->employeeID?? ''}}" class="form-control" hidden>
                        <input type="text" name="employeeName" value="{{request()->employeeName?? ''}}" class="form-control" hidden>
                        <input type="text" value="{{request()->employeeCode?? ''}}" name="employeeCode" class="form-control" hidden>
                        <input type="text" value="{{request()->employeeEmail?? ''}}" name="employeeEmail" class="form-control" hidden>
                        <button type="submit" class="btnDownload">
                            <i style="font-size:19px" class="fa"> &#xf019; </i> {{ trans('messages.pdf_download') }} </button>
                    </form>
                </td>
                <td colspan="2">
                    <form action="{{route('employees.excel-downloads')}}" method="GET">
                        <input type="text" name="employeeID" value="{{request()->employeeID?? ''}}" class="form-control" hidden>
                        <input type="text" name="employeeName" value="{{request()->employeeName?? ''}}" class="form-control" hidden>
                        <input type="text" value="{{request()->employeeCode?? ''}}" name="employeeCode" class="form-control" hidden>
                        <input type="text" value="{{request()->employeeEmail?? ''}}" name="employeeEmail" class="form-control" hidden>
                        <button type="submit" class="btnDownload">
                            <i style="font-size:19px" class="fa"> &#xf019; </i> {{ trans('messages.excel_download') }} </button>
                    </form>
                </td>
            </tr>
        </table>
        <br>
    </div>
    <div class="container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2"> {{ trans('messages.employee_id') }} </th>
                    <th rowspan="2"> {{ trans('messages.employee_code') }} </th>
                    <th rowspan="2"> {{ trans('messages.employee_name') }} </th>
                    <th rowspan="2"> {{ trans('messages.employee_email') }} </th>
                    <th colspan="4"> {{ trans('messages.action') }} </th>
                </tr>
                <tr>
                    <th>{{ trans('messages.edit') }}</th>
                    <th>{{ trans('messages.detail') }}</th>
                    <th>Active/Inactive</th>
                    <th>{{ trans('messages.delete') }}</th>
                </tr>
            </thead>
            <tbody>
                <!-- Fetch data from the database -->
                @foreach ($employees as $employee)
                <tr>
                    <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->employee_code }}</td>
                    <td>{{ $employee->employee_name }}</td>
                    <td>{{ $employee->email_address }}</td>

                    @if(!$employee->deleted_at)
                    <td>
                        <!-- Edit button -->
                        <a href="{{route('employees.edit', $employee->id)}}" class="btn btn-primary"><i class='far'>&#xf044;</i>
                    </td>
                    <td>
                        <!-- Detail button -->
                        <a href="{{route('employees.show', $employee->id)}}" class="btn btn-primary"><i class="fa">&#xf05a;</i>
                    </td>
                    <td>
                        <!-- Active button -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationDeactivateModal{{$employee->id}}">
                            Active
                        </button>

                    <td>

                        <!-- Delete button -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationDeleteModal{{$employee->id}}">
                            <i class="fa">&#xf014;</i>
                        </button>


                    </td>
                    @else
                    <td>
                        <!-- Edit button -->
                        <button type="submit" class="btn btn-secondary" disabled><i class='far'>&#xf044;</i></button>
                    </td>
                    <td>
                        <!-- Detail button -->
                        <a href="{{route('employees.show', $employee->id)}}" class="btn btn-primary"><i class="fa">&#xf05a;</i></button>
                    </td>
                    <td>
                        <!-- Inactive button -->
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#confirmationActivateModal{{$employee->id}}">
                            Inactive
                        </button>
                    </td>
                    <td>
                        <!-- Delete button -->
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="fa">&#xf014;</i></button>
                    </td>
                    @endif

                </tr>
                <!--Delete Confirmation Modal -->
                <div class="modal fade" id="confirmationDeleteModal{{$employee->id}}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">{{ trans('messages.confirmation') }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <p><b>{{ trans('messages.delete_confirm') }}</b>
                                    <br>
                                    {{ trans('messages.employee_id') }} : {{$employee->employee_id}}
                                    <br>
                                    {{ trans('messages.employee_name') }} : {{$employee->employee_name}}
                                </p>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('messages.cancel') }}</button>
                                <form action="{{route('employees.delete', $employee->id)}}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{ trans('messages.delete_btn') }}</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!--Deactivate Confirmation Modal -->
                <div class="modal fade" id="confirmationDeactivateModal{{$employee->id}}">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">{{ trans('messages.confirmation') }}</h4>
                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <p><b>{{ trans('messages.deactivate_confirm') }}</b>
                                    <br>
                                    {{ trans('messages.employee_id') }} : {{$employee->employee_id}}
                                    <br>
                                    {{ trans('messages.employee_name') }} : {{$employee->employee_name}}
                                </p>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('messages.cancel') }}</button>
                                <form action="{{ route('employees.inactive', $employee->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">{{ trans('messages.save_change') }}</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!--Activate Confirmation Modal -->
                <div class="modal fade" id="confirmationActivateModal{{$employee->id}}">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">{{ trans('messages.confirmation') }}</h4>
                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <p><b>{{ trans('messages.activate_confirm') }}</b>
                                    <br>
                                    {{ trans('messages.employee_id') }} : {{$employee->employee_id}}
                                    <br>
                                    {{ trans('messages.employee_name') }} : {{$employee->employee_name}}
                                </p>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('messages.cancel') }}</button>
                                <form action="{{ route('employees.active', $employee->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">{{ trans('messages.save_change') }}</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>

        </table>
    </div>
    <footer>

        {{ $employees->links() }}

    </footer>
    @endif
</body>

<script>
    const searchID = document.getElementById('employeeID');
    const searchName = document.getElementById('employeeName');
    const searchCode = document.getElementById('employeeCode');
    const searchEmail = document.getElementById('employeeEmail');

    function searchInput(event) {
        if (searchID.value.trim() === '' && searchName.value.trim() === '' && searchCode.value.trim() === '' && searchEmail.value.trim() === '') {
            event.preventDefault();

        }

    }

    const closeButtons = document.querySelectorAll('.close-button');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Find the parent error-message element and hide it
            const errorMessage = this.parentNode;
            errorMessage.style.display = 'none';
        });
    });
</script>

</html>