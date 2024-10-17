<style>
    .fixed-width {
        width: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width">ID</span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="ID Macchina" disabled value="100">
    </div>
    <div class="input-group input-group-lg mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width"><i class="fas fa-id-card"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="ID Operatore" disabled value="123456789">
    </div>
    <div class="input-group input-group-lg mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width"><i class="fas fa-barcode"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="Commessa" disabled value="01/01/2024">
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-4">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button">Richiesta Filato</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button">Cambio Spola</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button">Richiesta Intervento</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button">Manuale d'uso</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3 mb-4">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button">Scansiona</button>
            </div>
        </div>

        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">
            Launch Extra Large Modal
        </button>
    </div>
@endsection

@section('script')
    <script>
        $('#modal-xl').on('shown.bs.modal', function() {
            var iframe = '<iframe src="{{ asset('pdf/manuale_uso.pdf') }}" width="100%"></iframe>';
            $(this).find('.modal-body').html(iframe);
        });
    </script>
@endsection
