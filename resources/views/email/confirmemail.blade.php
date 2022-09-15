<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>salesMan</title>
  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
</head>

<body>
  <div class="px-5 pt-4">
    <h1 class="fs-5">Selamat Email Anda Berhasil Dikonfirmasi</h1>
    <h1>Anda {{ $role }}</h1>
    @if ($role == 'salesman' || $role == 'shipper')
      <a href="/spa/login">Silahkan Login</a>
    @else
      <a href="/login">Silahkan Login</a>
    @endif
  </div>

  <script src="{{ mix('js/bootstrap.js') }}"></script>
</body>

</html>
