<style>
    .fixed-width {
        width: 175px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding-right: 16px;
    }

    .custom-input {
        width: 175px;
        display: flex;
        text-align: right;
        justify-content: right;
        align-items: center;
        padding-right: 16px;
    }
</style>

@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width">Misurazione Filo</span>
        </div>
        <input type="text" class="font-lg form-control custom-input" placeholder="Misurazione Filo" disabled value="676.24">
    </div>
    <div class="input-group input-group-lg mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width">Impulsi</span>
        </div>
        <input type="text" class="font-lg form-control custom-input" placeholder="Impulsi" disabled value="0">
    </div>
    <div class="input-group input-group-lg mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width">Lunghezza totale</span>
        </div>
        <input type="text" class="font-lg form-control custom-input" placeholder="Lunghezza totale" disabled
            value="0.000000 cm">
    </div>
    <div class="input-group input-group-lg mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width">Velocità</span>
        </div>
        <input type="text" class="font-lg form-control custom-input" placeholder="Velocità" disabled
            value="0.000000 m/s">
    </div>
    <div class="input-group input-group-lg mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width">Operatività</span>
        </div>
        <input type="text" class="font-lg form-control custom-input" placeholder="Operatività" disabled value="00:00:00">
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-4">
                <button type="button" class="btn btn-block btn-primary btn-lg">Parametro Spola Inferiore</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection