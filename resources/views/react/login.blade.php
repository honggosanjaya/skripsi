
@extends('layouts/react')

@push('CSS')
@endpush

@section('main_content')
<main className='page_main login_page'>
    <div className="page_container pt-5">
        {isLoading && <LoadingIndicator />}
        <h1 className='logo text-center fs-1 mb-5'>salesMan</h1>
        <h1 className='fs-3 text-center fw-bold'>Selamat Datang<span className="iconify ms-2" data-icon="emojione:hand-with-fingers-splayed"></span></h1>
        <h2 className='fs-6 text-center'>Aplikasi web salesMan <br /> {`${company_name}`}</h2>
        {errorAuth && <AlertComponent errorMsg={errorAuth} />}
        {errorAuth == 'Anda mengakses halaman login yang salah' && <p className='mb-3 text-center'>
        Halaman login khusus untuk salesman dan tenaga pengirim, untuk staff lain silahkan login
        <a href="/login" className="custom-form-input"> disini</a>
        </p>}

        <form onSubmit={handleSubmit} className="mt-5">
        <div className="mb-3">
            <label htmlFor="email">Email</label>
            <input
            type="email"
            className="form-control"
            id="email"
            placeholder="Masukkan Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            />
        </div>

        <div className="mb-4">
            <label htmlFor="password">Password</label>
            <div className="input-group">
            <input
                type={hiddenPassword ? 'password' : 'text'}
                className="form-control"
                id="password"
                placeholder="Masukkan Kata Sandi"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                autoComplete="off"
            />
            <button onClick={toggleShow} className="btn btn_showHidePsw">
                <i className="iconify"
                data-icon={hiddenPassword ? "akar-icons:eye-open" : "akar-icons:eye-slashed"}></i>
            </button>
            </div>
        </div>

        <button type="submit" className="btn btn-primary w-100 my-4" disabled={isLoading}>MASUK</button>
        <a href="/logout" className="custom-form-input fs-7 text-center d-block">Login sebagai Tenaga Kantor</a>
        </form>
    </div>
</main>
@endsection
