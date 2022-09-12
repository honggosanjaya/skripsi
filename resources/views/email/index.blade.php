<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <style>
    .email-box {
      background-color: #1864ab;
      padding: 1.5rem;
      border-radius: 1.5rem 1.5rem 0 0;
    }

    .email-box h2,
    .email-box h1,
    .email-box p {
      color: #fff;
    }

    .confirm-button {
      padding: 1rem;
      background-color: #1864ab;
      color: #fff;
      text-decoration: none;
      display: block;
      margin-top: 3rem;
      margin-left: auto;
      margin-right: auto;
      border-radius: 0.5rem;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="email-box">
      <h2>Halo {{ $details['customer']->nama }}</h2>
      <h1>{{ $details['title'] }}</h1>
      <p>{{ $details['body'] }}</p>
    </div>

    <a href="https://www.google.com/" class="confirm-button">Tekan Untuk Mengonfirmasi Email Anda</a>
  </div>
</body>

</html>
