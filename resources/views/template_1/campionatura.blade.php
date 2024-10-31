<style>
    #form_campionatura .row {
        margin-left: 0;
        margin-right: 0;
    }

    #form_campionatura .col-12 {
        padding-left: 0;
        padding-right: 0;
    }
</style>
@extends('template_1.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container">
            <div class="row ">
                <div class="col-sm-12">
                    <h1 class="m-0" style="font-weight: bold; text-align: center;">CAMPIONATURA</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('main')
    <form id="form_campionatura">
        <div class="card mt-2">
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td style="padding-left: 15px; font-size: 20.8px;">Consumo Commessa</td>
                            <td style="float: right; padding-right: 15px; font-size: 20.8px; border: 0;"><span id="tempo_commessa" >0</span> m</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 15px; font-size: 20.8px;">Tempo Commessa</td>
                            <td style="float: right; padding-right: 15px; font-size: 20.8px; border: 0;"><span id="consumo_totale">0</span> m</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
        <div class="row w-100">
            <div class="col-12">
                <div class="color-palette-set mt-3 mb-3">
                    <button type="button" class="btn btn-block btn-success btn-lg custom-button" style="font-weight: bold;"
                            id="start_campionatura" onclick="signalCampionatura('START')">START</button>
                </div>
            </div>
            <div class="col-12">
                <div class="color-palette-set mt-3 mb-3">
                    <button type="button" class="btn btn-block btn-danger btn-lg custom-button" style="font-weight: bold;"
                            id="stop_campionatura" onclick="signalCampionatura('STOP')">STOP</button>
                </div>
            </div>
            <div class="col-12">
                <div class="color-palette-set mt-3 mb-3">
                    <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                            id="reset_campionatura" onclick="resetPage()">RESET</button>
                </div>
            </div>
        </div>
    </form>
    
@endsection

@section('script')
    <script>
        $('#stop_campionatura').prop('disabled', true);
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
                data: $("#form_campionatura input:enabled").serialize()
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
                Swal.fire({
                    icon: "error",
                    title: "Errore generico!",
                    customClass: {
                        popup: 'zoom-swal-popup'
                    }
                });
            });
        }

        let campionaturaId = null;
        let isStarted = false;

        function signalCampionatura(action) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('signalCampionatura') }}",
                data: $("#form_campionatura input:enabled").serialize() 
                    + "&timestamp=" + new Date().toISOString() 
                    + "&action=" + action 
                    + "&campione=" + "campione_test"
                    + (campionaturaId ? "&id=" + campionaturaId : "")
            }).done(function(data) {
                if (data.success) {
                    if (action === 'START') {
                        // Salva l'ID della nuova campionatura e disabilita il bottone
                        campionaturaId = data.id;
                        $('#start_campionatura').prop('disabled', true);
                        $('#stop_campionatura').prop('disabled', false);
                        isStarted = true;
                    } else if (action === 'STOP') {
                        // Ricalcola il tempo totale e riabilita il bottone
                        $('#tempo_commessa').text(data.tempo);
                        $('#consumo_totale').text(data.consumo);
                        $('#start_campionatura').prop('disabled', false);
                        $('#stop_campionatura').prop('disabled', true);
                        campionaturaId = null;  // Reset ID per una nuova campionatura
                        isStarted = false;
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
