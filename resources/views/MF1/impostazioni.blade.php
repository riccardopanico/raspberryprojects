@extends('MF1.index')
@section('main')
    <form id="form_impostazioni">
        <div class="card mt-2">
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td style="padding-left: 15px; font-size: 20.8px;">
                                <div style="text-align: center; font-weight: bold;">Consumo Commessa</div>
                                <div style="text-align: center;">{{ $consumo_commessa }} m</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 15px; font-size: 20.8px;">
                                <div style="text-align: center; font-weight: bold;">Tempo Commessa</div>
                                <div style="text-align: center;">{{ $tempo_commessa }} s</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 15px; font-size: 20.8px;">
                                <div style="text-align: center; font-weight: bold;">Consumo Totale</div>
                                <div style="text-align: center;">{{ $consumo_totale }} m</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 15px; font-size: 20.8px;">
                                <div style="text-align: center; font-weight: bold;">Tempo Totale</div>
                                <div style="text-align: center;">{{ $tempo_totale }} s</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- <div class="input-group input-group-lg mb-2 mt-2">
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
        </div> --}}

        <div class="input-group input-group-lg mb-2 mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Parametro Spola</span>
            </div>
            <input type="text" value="{{ $parametro_spola }}" name="settings[parametro_spola]" id="parametro_spola"
                class="font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>

        <div class="input-group input-group-lg mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Fattore Taratura</span>
            </div>
            <input type="text" value="{{ $fattore_taratura }}" name="settings[fattore_taratura]" id="fattore_taratura"
                class="font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>
        <input type="hidden" name="id_macchina" value="{{ $id_macchina }}">

        <div class="row">
            <div class="col-sm-4 col-md-2">
                <div class="color-palette-set mt-3 mb-3">
                    <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                        id="salva_impostazioni" onclick="settingsSaveAll()">SALVA
                        IMPOSTAZIONI</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        function settingsSaveAll() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSaveAll') }}",
                data: $("#form_impostazioni").serialize()
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
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Inserimento non effettuato!",
                        text: "Errore: " + data.msg,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                }
            }).fail(function(jqXHR, textStatus) {
                console.log("Errore generico!");
            });
        }
    </script>
@endsection
