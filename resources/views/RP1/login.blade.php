@extends(env('APP_NAME') . '.index')

<style>
#logo_img{
    position: absolute;
    top: 50%;
    transform: translateY(-70%);
}
.dark-mode .custom-control-label::before, .dark-mode .custom-file-label, .dark-mode .custom-file-label::after, .dark-mode .custom-select, .dark-mode .form-control:not(.form-control-navbar):not(.form-control-sidebar), .dark-mode .input-group-text {
    background-color: #fff !important;
    color: #343a40 !important;
}
</style>

@section('main')
    <div class="row">
        <div class="col-5">
            <div id="logo_img"> <a href="{{ route('home') }}"> <img class="img-fluid" src="{{ asset('img/niva.png') }}" alt="Niva Logo"> </a> </div>
            @if ($error)
                <div class="alert alert-danger alert-dismissible mt-2">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"
                        style="padding: 5px 10px;">×</button>
                    <h5 class="m-0" style="font-size: 18px;">
                        <i class="icon fas fa-ban"></i>Attenzione!<br>
                        <b> {{ $error }} </b>
                    </h5>
                </div>
            @endif
        </div>
        <div class="col-7" style="padding-left: 25px;">
            <div>
                <form action="{{ route('signin') }}" method="POST">
                    @csrf
                    <div id="tastierino_input" class="row">
                        <div class="col-12" role="group">
                            <div class="input-group input-group-lg mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text fixed-width-home"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="password" id="badge" name="badge" class="font-lg form-control"
                                    placeholder="Inserisci PIN" autofocus>
                            </div>
                        </div>
                    </div>

                    <div id="tastierino" class="row">
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
        </div>
    </div>

@endsection

@section('script')
    <script>
        setInterval(() => {
            $('#badge').focus();
        }, 1000);

        function inputNumpad(n) {
            $('#badge').val($('#badge').val() + n);
        }

        function cancelNumpad() {
            var currentValue = $('#badge').val();
            $('#badge').val(currentValue.slice(0, -1));
        }
    </script>
@endsection
