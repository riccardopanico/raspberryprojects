@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width-home">ID</span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="ID Macchina" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">100</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width-home"><i class="fas fa-id-card"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="ID Operatore" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">0010452223</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width-home"><i class="fas fa-barcode"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="Commessa" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">50</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-4">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" data-toggle="modal" data-target="#modal-xl">Richiesta Filato</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" data-toggle="modal" data-target="#modal-xl">Cambio Spola</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" data-toggle="modal" data-target="#modal-xl">Richiesta Intervento</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" data-toggle="modal" data-target="#modal-xl">Manuale d'uso</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3 mb-4">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" data-toggle="modal" data-target="#modal-xl">Scansiona</button>
            </div>
        </div>

        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">
            Launch Extra Large Modal
        </button>
    </div>
@endsection

@section('script')
    <script>
        $('#modal-xl').on('shown.bs.modal', function(e) {
            console.log(e);
            var triggerElement = $(e.relatedTarget);
            var modalBody = $(this).find('.modal-body');
            // var iframe = '<iframe src="{{ asset('pdf/manuale_uso.pdf') }}" width="100%"></iframe>';
            // $(this).find('.modal-body').html(iframe);
        });
    </script>
@endsection
