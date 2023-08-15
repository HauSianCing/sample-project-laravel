<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Download</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h3,
        p {
            text-align: center;
            padding: 10px;
            font-family: sans-serif;
        }

        table {
            border-collapse: collapse;
            align-items: center;
        }

        table,
        tr,
        td,
        th {
            border: 1px solid blue;
            text-align: center;
            padding: 10px;
            font-family: sans-serif;
            font-size: 13px;
        }
    </style>

</head>
<br>

<body>
    <!-- for downloading pdf format -->
    <div class="container">
        <h3>Employees Search Results</h3>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Employee ID</th>
                    <th>Employee Code</th>
                    <th>Employee Name</th>
                    <th>Email Address</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Marital Status</th>
                    <th>Address</th>
                </tr>
            </thead>

            <tbody>
                <!-- Loop to display the employee data -->
                @foreach ($employees as $employee)
                <!-- Check gender value -->
                @if ($employee->gender == "1")
                @php $gender = "Male"; @endphp
                @elseif ($employee->gender == "2")
                @php $gender = "Female"; @endphp
                @else
                @php $gender = " - "; @endphp
                @endif

                <!-- Check marital status value -->
                @if ($employee->marital_status == "1")
                @php $status = "Single"; @endphp
                @elseif ($employee->marital_status == "2")
                @php $status = "Married"; @endphp
                @elseif ($employee->marital_status == "3")
                @php $status = "Divorce"; @endphp
                @else
                @php $status = " - "; @endphp
                @endif
                @if($employee->address==null)
                @php $address = ' - '; @endphp
                @else
                @php $address = $employee->address; @endphp
                @endif
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->employee_code }}</td>
                    <td>{{ $employee->employee_name }}</td>
                    <td>{{ $employee->email_address }}</td>
                    <td>{{ $gender }}</td>
                    <td>{{ $employee->date_of_birth }}</td>
                    <td>{{ $status }}</td>
                    <td>{{ $address }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</body>

</html>