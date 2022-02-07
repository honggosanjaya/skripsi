<form action="/logout" method="POST">
    @csrf
    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
</form>

<h1>Welcome Back {{ auth()->user()->nama }}</h1>

<h2>Role : {{ auth()->user()->role }}</h2>

@if (auth()->user()->role === "0")
    <h2>Role anda sebagai Admin</h2>
@else
    <h2>Role anda sebagai Supervisor</h2>
@endif



