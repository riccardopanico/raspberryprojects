@extends('template_1.index')
@section('main')
    <form id="form_impostazioni">
        <div class="input-group input-group-lg mb-2 mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Tempo Commessa</span>
            </div>
            <input type="text" value="{{ $tempo_commessa }}" name="settings[tempo_commessa]" id="tempo_commessa"
                class="font-lg form-control no-border" disabled data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">s</span>
            </div>
        </div>

        <div class="input-group input-group-lg mb-2 mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Consumo Totale</span>
            </div>
            <input type="text" value="{{ $consumo_totale }}" name="settings[consumo_totale]" id="consumo_totale"
                class="font-lg form-control no-border" disabled data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>

        <div class="input-group input-group-lg mb-2 mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Tempo Totale</span>
            </div>
            <input type="text" value="{{ $tempo_totale }}" name="settings[tempo_totale]" id="tempo_totale"
                class="font-lg form-control no-border" disabled data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">s</span>
            </div>
        </div>

        <div class="input-group input-group-lg mb-2 mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Consumo Commessa</span>
            </div>
            <input type="text" value="{{ $consumo_commessa }}" name="settings[consumo_commessa]" id="consumo_commessa"
                class="font-lg form-control no-border" disabled data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>

        <div class="input-group input-group-lg mb-2 mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Parametro Spola</span>
            </div>
            <input type="text" value="{{ $parametro_spola }}" name="settings[parametro_spola]" id="parametro_spola"
                class="font-lg form-control no-border" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>

        <div class="input-group input-group-lg mb-2 mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Fattore Taratura</span>
            </div>
            <input type="text" value="{{ $fattore_taratura }}" name="settings[fattore_taratura]" id="fattore_taratura"
                class="font-lg form-control no-border" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>
        <input type="hidden" name="id_macchina" value="{{ $id_macchina }}">

        <div class="row">
            <div class="col-sm-4 col-md-2">
                <div class="color-palette-set mt-3 mb-3">
                    <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                        id="salva_impostazioni" onclick="settingsSaveAll()">SALVA IMPOSTAZIONI</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            }
        });
        KioskBoard.init({
            keysArrayOfObjects: null,
            keysJsonUrl: "build/kioskboard/dist/kioskboard-keys-english.json",
            // keysNumpadArrayOfNumbers: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
            language: 'it',
            theme: 'material',
            autoScroll: true,
            capsLockActive: true,
            cssAnimations: true,
            cssAnimationsDuration: 360,
            cssAnimationsStyle: 'slide',
            keysSpacebarText: 'Space',
            keysFontFamily: 'sans-serif',
            keysFontWeight: 'bold',
            keysEnterText: '<i class="fas fa-check" style="font-weight: bold;"></i>',
            keysEnterCallback: function() {
                window.scrollTo(0, 0);
            },
            keysEnterCanClose: true
        });
        KioskBoard.run('input');

        function settingsSaveAll() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSaveAll') }}",
                data: $("#form_impostazioni input:enabled").serialize()
            }).done(function(data) {
                if (data.success) {
                    $('#modal-xl').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "<strong>salvato</strong>",
                        text: '',
                        showConfirmButton: true,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                    window.scrollTo(0, 0);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Inserimento non effettuato!",
                        text: "Errore: " + data.msg,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                    window.scrollTo(0, 0);
                }
            }).fail(function(jqXHR, textStatus) {
                console.log("Errore generico!");
            });
        }
    </script>
@endsection
