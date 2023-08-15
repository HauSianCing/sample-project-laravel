<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <title>
        @yield('title')
    </title>
    <style>
        .iDClass {
            margin-left: 87%;
        }
        
        .select-wrapper {
            position: relative;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5%;
            background-color: #fff;
            width: 105px;
        }

        .select-wrapper select {
            width: 100%;
            padding: 5%;
            border: none;
            outline: none;
            background-color: transparent;
        }

        h1 {
            color: #1F51FF;
            font-weight: 600;
            font-size: 40px;
        }

        h2 {
            text-align: center;
            color: #1F51FF;
        }

        .card {
            padding: 7%;
            text-align: center;
            border-radius: 15px;
            background-color: #F8F8F8;
            border: none;
        }

        .btnText {
            color: #ffffff;
            font-size: 19px;

        }

        .btnText:hover {
            color: #1F51FF;
            font-weight: bold;
        }

        ul {
            list-style: none;
            padding: 3%;
            float: right;
            margin-right: auto;
        }

        li {
            padding-left: 2px;
            float: right;
        }

        .cardNav {
            padding: 3%;
            background-image: url("/photo/dark_desk.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-color: #cccccc;
            background-position: top;
            height: auto;
        }
    </style>
    @yield('header')
</head>
@yield('content')


@yield('footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>

</html>