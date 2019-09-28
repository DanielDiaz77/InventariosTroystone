@extends('auth.contenido')

@section('login')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group mb-0">
            <div class="card p-4">
                <form class="form-horizontal was-validated" method="POST" action="{{ route('login')}}">
                {{ csrf_field() }}
                    <div class="card-body">
                        <img src="img/logo.png" class="img-responsive" alt="Troystone" />
                        <h1>Acceder</h1>
                        <p class="text-muted">Control de acceso al sistema</p>
                        <div class="form-group mb-3 {{$errors->has('usuario' ? 'is-invalid' : '')}} ">
                            <span class="input-group-addon"><i class="icon-user"></i></span>
                            <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                            {!!$errors->first('usuario', '<span class="invalid-feedback">:message</span>')!!}
                        </div>
                        <div class="form-group mb-4 {{$errors->has('password' ? 'is-invalid' : '')}}">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
                            {!!$errors->first('password', '<span class="invalid-feedback">:message</span>')!!}
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn troybackg-light px-4">Acceder</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card text-white troybackg py-5 d-md-down-none" style="width:44%">
                <div class="card-body text-center">
                    <div>
                        <h2 class="text-center">Sistema de Inventarios TroyStone&reg;</h2>
                        <p>El acceso es autorizado y gestionado por la empresa, en caso de requerirlo, comunicarse a sistemas@troystone.com.mx .</p>
                        <a href="https://www.troystone.com.mx" target="_blank" class="btn btn-light active mt-3">Visitanos!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
    .troybackg {
        background-color: #fc7404 !important;
    }
    .troybackg-light {
        background-color: #fc9c54 !important;
    }
</style>
