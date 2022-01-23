<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        .tableUsers {
            width: 100%;
        }

        .tableUsers th {
            text-align: left;
            background: #000;
            color: #CCC;
        }

        .tableUsers td {
            border: 1px solid #CCC;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .w-50 {
            width: 50%;
        }

        .mt-3 {
            margin-top: 30px;
        }

        .mt-6 {
            margin-top: 60px;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    Report: {{ $name }}
    <h1 class="mb-0 mt-3 center">@yield('title')</h1>
    <div class="pdf-reports">
            @yield('content')
    </div>
</body>

</html>
