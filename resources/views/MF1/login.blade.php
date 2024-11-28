@extends('MF1.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container">
            <div class="row" style="margin-top: 8px;">
                <div class="col-sm-12">
                    <h1 class="mt-0" style="font-weight: bold; text-align: center;">
                        AUTENTICAZIONE</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('navbar')
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="zoom: 1.27;">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item" style="zoom: 1.23;">
                    <a class="nav-link" href="#" role="button" style="padding: 0; margin: 0;">
                        <img src="{{ asset('img/niva.png') }}" alt="Niva Logo"
                            style="width: 163px; height: 41.5px; margin-left: 3px;">
                    </a>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item" style="zoom: 1.23;">
                <a class="nav-link" href="#" role="button" style="padding: 0; font-size: 28px;">
                    <b style="margin-right: 3px;">ID&nbsp;<span>{{ $device_id }}</span></b>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('main')
    <div class="container">

        @if ($error)
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 class="m-0" style="font-size: 23px;">
                    <i class="icon fas fa-ban"></i>Attenzione!<br>
                    <b> {{ $error }} </b>
                </h5>
            </div>
        @endif

        <form action="{{ route('signin') }}" method="POST">
            @csrf
            {{-- <div class="input-group input-group-lg mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text fixed-width-home">ID</span>
                </div>
                <input type="text" class="font-lg form-control" placeholder="ID Macchina" disabled>
                <div class="input-group-append">
                    <span class="input-group-text" style="color: #6c757d">{{ $device_id }}</span>
                </div>
            </div> --}}
            <div class="input-group input-group-lg mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text fixed-width-home"><i class="fas fa-id-card"></i></span>
                </div>
                <input type="password" id="id_operatore" name="id_operatore" class="font-lg form-control"
                    placeholder="ID Operatore" autofocus>
            </div>

            <div class="row">
                <div class="col-12 btn-group-vertical" role="group">
                    <div class="btn-group" style="background: #fff">
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(1)">1</button>
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(2)">2</button>
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(3)">3</button>
                    </div>
                    <div class="btn-group" style="background: #fff">
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(4)">4</button>
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(5)">5</button>
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(6)">6</button>
                    </div>
                    <div class="btn-group" style="background: #fff">
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(7)">7</button>
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(8)">8</button>
                        <button type="button" class="font-lg btn btn-outline-secondary py-3"
                            style="font-size: 27px; font-weight: bold;" onclick="inputNumpad(9)">9</button>
                    </div>
                    <div class="btn-group" style="background: #fff">
                        <button style="width: 33.333%; font-size: 27px; font-weight: bold;" type="button"
                            class="font-lg btn btn-outline-secondary py-3" onclick="cancelNumpad()"><i
                                class="fas fa-backspace"></i></button>
                        <button style="width: 33.333%; font-size: 27px; font-weight: bold;" type="button"
                            class="font-lg btn btn-outline-secondary py-3" onclick="inputNumpad(0)">0</button>
                        <button style="width: 33.333%; font-size: 27px; font-weight: bold;" type="submit"
                            class="font-lg btn btn-primary py-3"><i class="icon fas fa-check"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        setInterval(() => {
            $('#id_operatore').focus();
        }, 1000);

        function inputNumpad(n) {
            $('#id_operatore').val($('#id_operatore').val() + n);
        }

        function cancelNumpad() {
            var currentValue = $('#id_operatore').val();
            $('#id_operatore').val(currentValue.slice(0, -1));
        }
    </script>
@endsection
