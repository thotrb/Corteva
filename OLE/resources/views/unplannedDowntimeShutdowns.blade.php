<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Unplanned Downtime Shutdowns</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="{{mix('css/app.css')}}">

    <!-- Styles -->
    <style>

        <!--
        html, body {
            padding: 45px;
            background-color: lightgray;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }-->

    </style>
</head>
<div id="app">
<body>
    <navbar></navbar>
    <unplanned-downtime-shutdowns></unplanned-downtime-shutdowns>
    <script async src="{{mix('js/app.js')}}"></script>
</body>
</div>

</html>
<script>

</script>
