@extends('MF1.index')
@section('main')
{{-- <div class="card mt-2 mb-1">
    <div class="card-body p-0"> --}}

        <div class="row mt-3 font-lg">
            <div class="col-12">
                <ul class="nav nav-tabs" id="tacchiTab" role="tablist" style="font-size: 24px;">
                    <li class="nav-item">
                        <a class="nav-link active" id="INFO-tab" data-toggle="pill" href="#INFO" role="tab" aria-controls="INFO" aria-selected="false"><i class="fa fa-info-circle"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="TC-tab" data-toggle="pill" href="#TC" role="tab" aria-controls="TC" aria-selected="false">TACCHI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="SU-tab" data-toggle="pill" href="#SU" role="tab" aria-controls="SU" aria-selected="false">SUOLE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="PE-tab" data-toggle="pill" href="#PE" role="tab" aria-controls="PE" aria-selected="false">PELLAMI</a>
                    </li>
                </ul>
                <div class="tab-content" id="tacchiTabContent" style="width: 100%;">

                    <div class="tab-pane fade active show" id="INFO" role="tabpanel" aria-labelledby="INFO-tab">
                        <div class="row" style="font-size: 26px;">
                            <div class="col-12">
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <div class="col-12" style="white-space: nowrap;"> Prefisso </div>
                                    <div class="col-12" style="white-space: nowrap;"> <span id="prefisso" class="badge bg-primary font-lg"> -- </span> </div>
                                </div>
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <div class="col-12" style="white-space: nowrap;"> Lotto </div>
                                    <div class="col-12" style="white-space: nowrap;"> <span id="lotto" class="badge bg-primary font-lg"> -- </span> </div>
                                </div>
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <div class="col-12" style="white-space: nowrap;"> Articolo </div>
                                    <div class="col-12"> <span id="articolo" class="badge bg-primary font-lg"> -- </span> </div>
                                </div>
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <div class="col-12" style="white-space: nowrap;"> &nbsp; </div>
                                    <div class="col-12"> <span {{-- id="T1codlavor" --}} class="{{-- badge bg-primary --}} font-lg"> &nbsp; </span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="TC" role="tabpanel" aria-labelledby="TC-tab">
                        <div class="row" style="font-size: 26px;">
                            <div class="col-12">
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <div class="col-12" style="white-space: nowrap;"> Codice Lavorante Tacchi </div>
                                    <div class="col-12" style="white-space: nowrap;"> <span id="TCcodlavor" class="badge bg-primary font-lg"> -- </span> </div>
                                </div>
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <div class="col-12" style="white-space: nowrap;"> Data Lavorazione Tacchi </div>
                                    <div class="col-12" style="white-space: nowrap;"> <span id="TCdata_lavor" class="badge bg-primary font-lg"> -- </span> </div>
                                </div>
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <div class="col-12" style="white-space: nowrap;"> Data Lavorazione Tacchi Madre </div>
                                    <div class="col-12"> <span id="T1data_lavor" class="badge bg-primary font-lg"> -- </span> </div>
                                </div>
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <div class="col-12" style="white-space: nowrap;"> &nbsp; </div>
                                    <div class="col-12"> <span {{-- id="T1codlavor" --}} class="{{-- badge bg-primary --}} font-lg"> &nbsp; </span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="SU" role="tabpanel" aria-labelledby="SU-tab">
                        <div class="row" style="font-size: 26px;">
                            <div class="col-12">
                                <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                                    <table class="table table-bordered" style="zoom: 0.89; font-weight: 400;">
                                        <thead style="text-align: center; line-height: 0; ">
                                            <tr>
                                                <th>Taglia</th>
                                                <th>Situazione</th>
                                                <th>Ordinato</th>
                                            </tr>
                                        </thead>
                                        {{-- 051051500186 --}}
                                        <tbody style=" line-height: 0; ">
                                        @for ($i = 32; $i <= 45; $i += 0.5)
                                            <tr data-CODMIS="{{ $i }}">
                                                <td style="font-weight: bold;" class="text-primary">{{ $i }}</td>
                                                <td style="text-align: center;" class="QTSIT">0</td>
                                                <td style="text-align: center;" class="QTORF">0</td>
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="PE" role="tabpanel" aria-labelledby="PE-tab">
                        <div class="row callout callout-secondary mb-0" style="padding: 0.5rem;">
                            <div class="col-12" style="margin-bottom: 120px;">
                                <div id="PE1situazione" class="row border-bottom" style="font-size: 26px;">
                                    <div class="col-6 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Pellame 1</h5>
                                            <span class="CODART text-primary description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                    <div class="col-3 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Sit.</h5>
                                            <span class="QTSIT description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Ord.</h5>
                                            <span class="QTORF description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                </div>
                                <div id="PE2situazione" class="row border-bottom" style="font-size: 26px;">
                                    <div class="col-6 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Pellame 2</h5>
                                            <span class="CODART text-primary description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                    <div class="col-3 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Sit.</h5>
                                            <span class="QTSIT description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Ord.</h5>
                                            <span class="QTORF description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                </div>
                                <div id="PE3situazione" class="row border-bottom" style="font-size: 26px;">
                                    <div class="col-6 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Pellame 3</h5>
                                            <span class="CODART text-primary description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                    <div class="col-3 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Sit.</h5>
                                            <span class="QTSIT description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="description-block">
                                            <h5 class="description-header" style="font-weight: 900; font-size: 24px;">Ord.</h5>
                                            <span class="QTORF description-text" style="font-size: 20px;"> -- </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- </div>
</div> --}}
@endsection

@section('script')
    <script>
        var forzaFocus = true;
        var $commessa = $("#commessa");

        function gestisciFocus() {
            if (forzaFocus) {
                $commessa.focus();
            }
        }

        $(document).ready(function() {
            gestisciFocus();
            stateButton();
        });

        $(document).on('click touchend', function(e) {
            gestisciFocus();
        });

        $commessa.on('focusout', function(e) {
            if (!$commessa.is(":focus") && forzaFocus) {
                gestisciFocus();
            }
        });

        $(document).on('keydown', function(e) {
            if (!$commessa.is(":focus") && forzaFocus) {
                gestisciFocus();
            }
        });

        $commessa.keypress(function(e) {
            if (e.keyCode === 13) {
                var commessa = $(this).val();
                e.preventDefault();
                console.log(commessa);
                settingsSave('commessa', commessa);
                gestisciFocus();
                $(this).val('');
            }
        });

        function stateButton() {
            $("button[data-state]").each(function() {
                var state = $(this).data("state");
                $(this).closest('div').removeClass('flashing');
                $(this).prop('disabled', false);

                if (state === 1) {
                    setTimeout(() => {
                        $(this).closest('div').addClass('flashing');
                        $(this).prop('disabled', true);
                    }, 50);
                }
            });
        }

        function settingsSave(setting, value) {
            if (!value) {
                Swal.fire({
                    icon: "error",
                    title: "Errore!",
                    text: "Nessun valore inserito!",
                    customClass: {
                        popup: 'zoom-swal-popup'
                    }
                });
                return;
            }

            const settingsInfo = {
                'data_cambio_spola': {
                    successTitle: '<strong>Cambio spola</strong><br>effettuato<br>con successo!',
                    errorTitle: '<strong>Cambio spola</strong><br>non effettuato!'
                },
                'last_barcode': {
                    successTitle: '<strong>Scansione</strong><br>effettuata<br>con successo!',
                    errorTitle: '<strong>Scansione</strong><br>non effettuata!'
                },
                'richiesta_filato': {
                    successTitle: '<strong>Richiesta filato</strong><br>effettuata<br>con successo!',
                    errorTitle: '<strong>Richiesta filato</strong><br>non effettuata!'
                },
                'richiesta_intervento': {
                    successTitle: '<strong>Richiesta intervento</strong><br>effettuata<br>con successo!',
                    errorTitle: '<strong>Richiesta intervento</strong><br>non effettuata!'
                },
                'commessa': {
                    successTitle: '<strong>Commessa</strong><br>salvata<br>con successo!',
                    errorTitle: '<strong>Commessa</strong><br>non salvata!',
                    onSuccess: () => {
                        $('#commessa_view').text(value);
                    }
                }
            };

            const info = settingsInfo[setting];
            const $button = $("[data-state]#btn_" + setting);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSave') }}",
                data: { setting: setting, value: value }
            }).done(function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: info.successTitle,
                        text: " ",
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                    $button.data('state', 1);
                    stateButton();

                    if (typeof info.onSuccess === 'function') {
                        info.onSuccess();
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: info.errorTitle,
                        text: "Errore: " + data.msg,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                }
            }).fail(function(jqXHR, textStatus) {
                console.log("Errore generico!");
                $button.data('state', 0);
                stateButton();
            });
        }
    </script>
@endsection
