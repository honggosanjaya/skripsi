<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>salesMan</title>
  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">

  <style>
    .container-wrapper {
      width: 100vw;
      height: 100vh;
      background-color: #5372ef;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .box {
      box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
      padding: 2rem;
      background-color: #fff;
      border-radius: 1rem;
    }
  </style>
</head>

<body>
  <div class="container-wrapper">
    <div class="box">
      <h1 class="fs-2 text-center">Selamat Email Anda Berhasil Dikonfirmasi</h1>
      <h1 class="mb-5 fs-4 text-center">Anda adalah {{ $role }}</h1>
      @if ($role == 'salesman' || $role == 'shipper')
        <a href="/spa/login" class="btn btn-primary d-block">Silahkan Login</a>
      @else
        <a href="/login" class="btn btn-primary d-block">Silahkan Login</a>
      @endif
    </div>
  </div>

  <script src="{{ mix('js/bootstrap.js') }}"></script>
</body>

</html>
