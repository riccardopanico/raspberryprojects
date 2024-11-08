@extends('MF1.index')
@section('main')
    <div class="card mt-2">
        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td class="custom-cell">
                            <div class="header-section">Consumo Filato</div>
                            <div class="value-section"><span id="consumo_campionatura">0 m</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-cell">
                            <div class="header-section">Tempo Operatività Macchina</div>
                            <div class="value-section"><span id="tempo_campionatura">0 s</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="custom-cell">
                            <div class="header-section">Tempo Toale Campionatura</div>
                            <div class="value-section"><span id="tempo_totale_campionatura">0 s</span></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-success btn-lg custom-button" style="font-weight: bold;"
                    id="start_campionatura" onclick="signalCampionatura('START')">START</button>
            </div>
        </div>
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-danger btn-lg custom-button" style="font-weight: bold;"
                    id="stop_campionatura" onclick="signalCampionatura('STOP')">STOP</button>
            </div>
        </div>
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                    id="reset_campionatura" onclick="resetPage()">RESET</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#stop_campionatura').prop('disabled', true);

        var campionaturaId = null;

        function signalCampionatura(action) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('signalCampionatura') }}",
                data: {
                    action: action,
                    timestamp: moment().valueOf(),
                    id: campionaturaId,
                    campione: "campione_test",
                }
            }).done(function(data) {
                if (data.success) {
                    if (action === 'START') {
                        // Salva l'ID della nuova campionatura e disabilita il bottone
                        campionaturaId = data.id;
                        $('#start_campionatura').prop('disabled', true);
                        $('#stop_campionatura').prop('disabled', false);
                        // $('#consumo_campionatura').text('0');
                        // $('#tempo_campionatura').text('0');
                    } else if (action === 'STOP') {
                        // Ricalcola il tempo totale e riabilita il bottone
                        campionaturaId = null; // Reset ID per una nuova campionatura
                        $('#start_campionatura').prop('disabled', false);
                        $('#stop_campionatura').prop('disabled', true);
                        // $('#consumo_campionatura').text(data.tempo);
                        // $('#tempo_campionatura').text(data.consumo);
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Errore sulla richiesta!",
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                }
            }).fail(function(jqXHR, textStatus) {
                Swal.fire({
                    icon: "error",
                    title: "Errore generico!",
                    customClass: {
                        popup: 'zoom-swal-popup'
                    }
                });
            });
        }


        function resetPage() {
            window.location.reload();
        }
    </script>
@endsection
