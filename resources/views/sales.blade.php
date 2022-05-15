<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Sales</title>
  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
  <link href=" {{ mix('css/sales.css') }}" rel="stylesheet">
  {{-- icon --}}
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</head>

<body>

  <div id="app"></div>

  <script src="{{ mix('js/bootstrap.js') }}"></script>
  <script src="{{ mix('js/react.js') }}"></script>
</body>

</html>
