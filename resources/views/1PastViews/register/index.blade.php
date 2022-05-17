<head>
  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
</head>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <main class="form-registration">
            <form action="/register" method="POST">
              @csrf

              <h1 class="h3 mb-3 mt-3 fw-normal">Registration Form</h1>
          
              <div class="form-floating my-2">
                <input type="text" name="nama" class="form-control rounded-top
                @error('nama') is-invalid @enderror" id="nama" placeholder="Nama" required 
                value="{{ old('nama') }}">
                <label for="nama">Nama</label>
                @error('nama')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="form-floating my-2">
                <input type="text" name="nomor_telepon" class="form-control 
                @error('username') is-invalid @enderror" id="nomor_telepon" placeholder="Nomor Telepon" required
                value="{{ old('nomor_telepon') }}">
                <label for="nomor_telepon">Nomor Telepon</label>
                @error('nomor_telepon')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Role</label>
                <select class="form-select" name="role">
                    <option value="0">Admin</option>   
                    <option value="1">Supervisor</option>            
                </select>         
            </div>
    

            <!--
            <div class="mb-3">
                <label for="foto_profil" class="form-label">Foto Profil</label>
                <img class="img-preview img-fluid mb-3 col-sm-5">
                <input class="form-control
                @error('foto_profil') is-invalid @enderror" type="file" id="foto_profil" name="foto_profil" onchange="previewImage()"
                width="450" height="450">
                @error('foto_profil')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror   
            </div>
        -->

              <div class="form-floating my-2">
                <input type="email" name="email" class="form-control
                @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" required
                value="{{ old('email') }}">
                <label for="email">Email address</label>
                @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>

              <div class="form-floating my-2">
                <input type="password" name="password" class="form-control rounded-bottom
                @error('password') is-invalid @enderror" id="password" placeholder="Password" required>
                <label for="password">Password</label>
                @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
          
             
              <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Register</button>
              
            </form>
            <small class="d-block mt-3 text-center">Already Registered? <a href="/login">Login</a></small>
        </main>
    </div>
</div>

<script src="{{ mix('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/eventHandle.js') }}"></script>