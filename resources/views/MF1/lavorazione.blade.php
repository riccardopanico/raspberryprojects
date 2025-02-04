@extends('MF1.index')
@section('main')
    <div class="col-12 col-sm-6 pr-0 pl-0 pt-1 pb-0">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="INFO-tab" data-toggle="pill" href="#INFO" role="tab"
                            aria-controls="INFO" aria-selected="true" style="font-weight: 550; font-size: 17px;">
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="TC-tab" data-toggle="pill" href="#TC" role="tab"
                            aria-controls="TC" aria-selected="false" style="font-weight: 550; font-size: 17px;">TACCHI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="SU-tab" data-toggle="pill" href="#SU" role="tab"
                            aria-controls="SU" aria-selected="false" style="font-weight: 550; font-size: 17px;">SUOLE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="PE-tab" data-toggle="pill" href="#PE" role="tab"
                            aria-controls="PE" aria-selected="false" style="font-weight: 550; font-size: 17px;">PELLAMI</a>
                    </li>
                </ul>
            </div>
            <div class="card-body" id="tacchiTabContent" style="padding: 0;">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade show active" id="INFO" role="tabpanel" aria-labelledby="INFO-tab"
                        style="padding: 7px;">
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 0; background-color: #ffffff;">
                                    Prefisso</div>
                                <div class="badge bg-primary bg-gradient value-section" id="prefisso"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    ---</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Lotto</div>
                                <div class="badge bg-primary bg-gradient value-section" id="lotto"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    ---</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Articolo</div>
                                <div class="badge bg-primary bg-gradient value-section" id="articolo"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    ---</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; padding-bottom: 3px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Note di lavorazione</div>
                                <div class="value-section" style="padding-top: 0;">
                                    <textarea class="form-control" rows="8" disabled></textarea>
                                </div>
                            </td>
                        </tr>
                    </div>
                    <div class="tab-pane fade" id="TC" role="tabpanel" aria-labelledby="TC-tab"
                        style="padding: 7px;">
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 0; background-color: #ffffff;">
                                    Codice Lavorante Tacchi</div>
                                <div class="badge bg-primary bg-gradient value-section" id="TCcodlavor"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    ---</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Data Lavorazione Tacchi</div>
                                <div class="badge bg-primary bg-gradient value-section" id="TCdata_lavor"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    ---</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Data Lavorazione Tacchi Madre</div>
                                <div class="badge bg-primary bg-gradient value-section" id="T1data_lavor"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    ---</div>
                            </td>
                        </tr>
                    </div>
                    <div class="tab-pane fade" id="SU" role="tabpanel" aria-labelledby="SU-tab">
                        <div style="overflow-y: scroll; width: 371px; height: 523px;">
                            <table class="table" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th style="border-top: none; text-align: center; background-color: #f2f2f2;">Taglia
                                        </th>
                                        <th style="border-top: none; text-align: center; background-color: #f2f2f2;">
                                            Situazione</th>
                                        <th style="border-top: none; text-align: center; background-color: #f2f2f2;">
                                            Ordinato</th>
                                    </tr>
                                </thead>
                                <tbody style="/*display: block;*/ max-height: 475px; overflow-y: scroll;">
                                    @for ($i = 32; $i <= 45; $i += 0.5)
                                        <tr data-CODMIS="{{ $i }}">
                                            <td style="text-align: center; font-weight: bold; border-bottom-color: #c3cad2; background-color: #f2f2f2;"
                                                class="text-primary">{{ $i }}</td>
                                            <td style="text-align: center;" class="QTSIT">0</td>
                                            <td style="text-align: center;" class="QTORF">0</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="PE" role="tabpanel" aria-labelledby="PE-tab">
                        <table class="table table-striped" style="margin-bottom: 0; width: 100%; table-layout: fixed;">
                            <tbody>
                                <tr id="PE1situazione">
                                    <td class="custom-cell" colspan="2">
                                        <div class="header-section" style="padding-bottom: 0; padding-top: 7px;">Pellame 1</div>
                                        <div class="CODART header-section" style="padding-top: 0;">---</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-cell" style="border-right: 1px solid #dee2e6; width: 50%;">
                                        <div class="d-flex justify-content-between" style="display: flex; flex-grow: 1;">
                                            <span class="value-section"
                                                style="font-weight: 500; margin: 7px;">Sit.:</span>
                                            <span class="QTSIT value-section" style="font-weight: 600; margin: 7px;">--</span>
                                        </div>
                                    </td>
                                    <td class="custom-cell">
                                        <div class="d-flex justify-content-between" style="display: flex; flex-grow: 1;">
                                            <span class="value-section"
                                                style="font-weight: 500; margin: 7px;">Ord.:</span>
                                            <span class="QTORF value-section" style="font-weight: 600; margin: 7px;">--</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="PE2situazione">
                                    <td class="custom-cell" colspan="2">
                                        <div class="header-section" style="padding-bottom: 0; padding-top: 7px;">Pellame 2</div>
                                        <div class="CODART header-section" style="padding-top: 0;">---</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-cell" style="border-right: 1px solid #dee2e6; width: 50%;">
                                        <div class="d-flex justify-content-between" style="display: flex; flex-grow: 1;">
                                            <span class="value-section"
                                                style="font-weight: 500; margin: 7px;">Sit.:</span>
                                            <span class="QTSIT value-section" style="font-weight: 600; margin: 7px;">--</span>
                                        </div>
                                    </td>
                                    <td class="custom-cell">
                                        <div class="d-flex justify-content-between" style="display: flex; flex-grow: 1;">
                                            <span class="value-section"
                                                style="font-weight: 500; margin: 7px;">Ord.:</span>
                                            <span class="QTORF value-section" style="font-weight: 600; margin: 7px;">--</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="PE3situazione">
                                    <td class="custom-cell" colspan="2">
                                        <div class="header-section" style="padding-bottom: 0; padding-top: 7px;">Pellame 3</div>
                                        <div class="CODART header-section" style="padding-top: 0;">---</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="custom-cell" style="border-right: 1px solid #dee2e6; width: 50%;">
                                        <div class="d-flex justify-content-between" style="display: flex; flex-grow: 1;">
                                            <span class="value-section"
                                                style="font-weight: 500; margin: 7px;">Sit.:</span>
                                            <span class="QTSIT value-section" style="font-weight: 600; margin: 7px;">--</span>
                                        </div>
                                    </td>
                                    <td class="custom-cell">
                                        <div class="d-flex justify-content-between" style="display: flex; flex-grow: 1;">
                                            <span class="value-section"
                                                style="font-weight: 500; margin: 7px;">Ord.:</span>
                                            <span class="QTORF value-section" style="font-weight: 600; margin: 7px;">--</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                data: {
                    setting: setting,
                    value: value
                }
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
