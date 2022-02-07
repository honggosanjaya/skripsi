<main class="form-signin">
    <form action="/login" method="POST">
      @csrf

      <h1 class="h3 mb-3 fw-normal">Please Login</h1>
  
      <div class="form-floating">
        <input type="email" name="email" class="form-control
        @error('email') is-invalid @enderror" id="email" placeholder="name@example.com"
        autofocus required value="{{ old('email') }}">
        <label for="email">Email address</label>
        @error('email')
          <div class="invalid-feedback">
            {{ $message }}
          </div> 
        @enderror
      </div>
      <div class="form-floating">
        <input type="password" name="password" class="form-control" id="password" placeholder="Password"
        autofocus required>
        <label for="password">Password</label>
      </div>
  
     
      <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
      
    </form>
    <small class="d-block mt-3 text-center">Not Registered? <a href="/register">Register Now !</a></small>
</main>