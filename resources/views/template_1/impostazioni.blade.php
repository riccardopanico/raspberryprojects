@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" class="font-lg form-control" placeholder="Misurazione Filo" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">676.24</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" class="font-lg form-control" placeholder="Impulsi" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">0</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" class="font-lg form-control" placeholder="Lunghezza totale" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">0.000000 cm</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" class="font-lg form-control" placeholder="Velocità" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">0.000000 m/s</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" class="font-lg form-control" placeholder="Operatività" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">00:00:00</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3 mb-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                    data-toggle="modal" data-target="#modal-xl" onclick="openModal('parametroSpola')">PARAMETRO SPOLA
                    INFERIORE</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function openModal(action) {
            var title = '';
            var message = '';
            var footer = '';
            var footerClass = '';

            if (action === 'parametroSpola') {
                title = '<strong>Parametro SPOLA</strong>';
                message = 'Parametro spola inferiore...';
                footer = '<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button><button type="button" class="btn btn-primary">Conferma</button>';
                footerClass = 'modal-footer justify-content-between';
            }

            $('#modal-xl').find('.modal-title').html(title);
            $('#modal-xl').find('.modal-body').html(message);
            $('#modal-xl').find('.modal-footer').html(footer).attr('class', footerClass);

            $('#modal-xl').modal('show');
        }
    </script>
@endsection