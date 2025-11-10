@extends('layouts.user_type.guest')

@section('content')

  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="container-logo-uvm">
                <img src="/assets/img/logo-uvm.svg" class="logo-uvm" alt="logo uvm">
              </div>
              <div class="card card-plain bg-gray-200 mt-4 card-uvm">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-center text-uvm text-gradient">Inicio de sesión</h3>
                </div>
                <div class="card-body px-3 py-2">
                  <form role="form" method="POST" action="/session">
                    @csrf
                    <label>Email</label>
                    <div class="mb-3">
                      <input type="email" class="form-control" name="email" id="email" placeholder="Correo" value="" aria-label="Email" aria-describedby="email-addon">
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                      <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" value="" aria-label="Password" aria-describedby="password-addon">
                    </div>
                    @error('login')
                      <p class="text-danger text-xs mt-4">{{ $message }}</p>
                    @enderror
                    <div class="form-check form-switch">
                      <input class="form-check-input form-check-input-uvm" type="checkbox" id="rememberMe" checked="">
                      <label class="form-check-label" for="rememberMe">¿Recuerdame?</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-uvm bg-gradient-uvm w-100 mt-4 mb-0">Inicio</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                <small style="display: none" class="text-muted">¿Olvidaste tu contraseña? Restablece 
                  <a href="/login/forgot-password" class="text-uvm text-gradient font-weight-bold">aqui</a>
                </small>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6 bg-gradient-uvm">
                  <img src="/assets/img/fondo.svg" class="svg-imagen" alt="fondo" style="background-color: rgb(191, 0, 33);">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection
@push('login')
<script>
  setInterval(function() {
      fetch('/keep-alive', { method: 'GET', credentials: 'same-origin' });
  }, 600000); // cada 10 minutos
</script>
@endpush
