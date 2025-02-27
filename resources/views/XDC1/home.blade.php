@extends(env('APP_NAME') . '.index')
@section('main')
    <style>
        /* Stile del loader */
        #custom-loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 9999;
            display: none; /* Nascondi di default */
        }

        /* Contenitore interno per garantire il centraggio */
        .custom-loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: absolute;
            top: 50% !important;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Spinner */
        .custom-spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #fff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: custom-spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes custom-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Testo sotto lo spinner */
        .custom-loader-text {
            color: white;
            font-size: 20px;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        /* Pulsanti di test */
        .custom-loader-btn {
            margin: 20px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
        }

        .custom-loader-btn:hover {
            background: #0056b3;
        }
    </style>
<input id="commessa" name="commessa">
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
                                    --</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Lotto</div>
                                <div class="badge bg-primary bg-gradient value-section" id="lotto"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    --</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Articolo</div>
                                <div class="badge bg-primary bg-gradient value-section" id="articolo"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    --</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; padding-bottom: 3px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Note di lavorazione</div>
                                <div class="value-section" style="padding-top: 0;">
                                    <textarea class="form-control" rows="5" disabled id="note_lavorazione" style="font-size: 24px;"></textarea>
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
                                    --</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Data Lavorazione Tacchi</div>
                                <div class="badge bg-primary bg-gradient value-section" id="TCdata_lavor"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    --</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell p-0">
                                <div class="header-section"
                                    style="text-align: left; font-size: 24px; padding-left: 8px; padding-top: 7px; border-top: 1px solid #dee2e6; background-color: #ffffff;">
                                    Data Lavorazione Tacchi Madre</div>
                                <div class="badge bg-primary bg-gradient value-section" id="T1data_lavor"
                                    style="text-align: left; font-size: 15px; padding: 5px; margin-left: 12px; margin-bottom: 12px;">
                                    --</div>
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
                            <tbody id="PE1situazione">
                                <tr>
                                    <td class="custom-cell" colspan="2">
                                        <div class="header-section" style="padding-bottom: 0; padding-top: 7px;">Pellame 1</div>
                                        <div class="CODART header-section" style="padding-top: 0;">--</div>
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
                        <table class="table table-striped" style="margin-bottom: 0; width: 100%; table-layout: fixed;">
                            <tbody id="PE2situazione">
                                <tr>
                                    <td class="custom-cell" colspan="2">
                                        <div class="header-section" style="padding-bottom: 0; padding-top: 7px;">Pellame 2</div>
                                        <div class="CODART header-section" style="padding-top: 0;">--</div>
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
                        <table class="table table-striped" style="margin-bottom: 0; width: 100%; table-layout: fixed;">
                            <tbody id="PE3situazione">
                                <tr>
                                    <td class="custom-cell" colspan="2">
                                        <div class="header-section" style="padding-bottom: 0; padding-top: 7px;">Pellame 3</div>
                                        <div class="CODART header-section" style="padding-top: 0;">--</div>
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

    <!-- Loader -->
    <div id="custom-loader-overlay">
        <div class="custom-loader-container">
            <div class="custom-spinner"></div>
            {{-- <div class="custom-loader-text">Caricamento commessa...</div> --}}
        </div>
    </div>

@endsection

@section('script')
    <script>

        function toggleLoader(show) {
            if (show) {
                $("#custom-loader-overlay").fadeIn(300);
            } else {
                $("#custom-loader-overlay").fadeOut(300);
            }
        }

        $(document).ajaxStart(function() {
            $("#custom-loader-overlay").fadeIn(300); // Mostra il loader all'inizio di una richiesta AJAX
        }).ajaxStop(function() {
            $("#custom-loader-overlay").fadeOut(300); // Nasconde il loader al termine della richiesta
        });

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
            getSettings();
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
                        getSettings();
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

        function getSettings() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                async: true,
                url: "{{ route('getSettings') }}",
                data: {}
            }).done(function(data) {
                console.log(data);

                // Mappatura diretta delle chiavi
                Object.keys(data).forEach(function(key) {
                    let valore = data[key];

                    switch (key) {
                        case "TCdata_lavor":
                        case "T1data_lavor":
                            $(`#${key}`).text(valore ? moment(valore).format('DD/MM/YYYY') : '--');
                            break;
                        case "PE1situazione":
                        case "PE2situazione":
                        case "PE3situazione":
                            let situazione = JSON.parse(valore);
                            let $container = $(`#${key}`);
                            if (Array.isArray(situazione) && situazione.length > 0) {
                                let first = situazione[0];
                                $container.find(".CODART").text(first.CODART);
                                $container.find(".QTSIT").text(first.QTSIT);
                                let qtorfElem = $container.find(".QTORF");
                                if (first.CONSUMO_MINORE_QTSIT == 1) {
                                    qtorfElem.css('color', 'red').text(first.QTORF);
                                } else {
                                    qtorfElem.css('color', '').text(first.QTORF);
                                }
                            } else {
                                $container.find(".QTSIT").text("--");
                                $container.find(".QTORF").text("--");
                            }
                            break;
                        case "SUsituazione":
                            let suSituazione = JSON.parse(valore);
                            if (Array.isArray(suSituazione) && suSituazione.length > 0) {
                                $('tr[data-CODMIS] .QTSIT').text(0);
                                $('tr[data-CODMIS] .QTORF').text(0);
                                suSituazione.forEach(function(item) {
                                    let row = $(`tr[data-CODMIS="${item.CODMIS}"]`);
                                    row.find(".QTSIT").text(item.QTSIT);
                                    let qtorfElem = row.find(".QTORF");
                                    if (item.CONSUMO_MINORE_QTSIT == 1) {
                                        qtorfElem.css('color', 'red').text(item.QTORF);
                                    } else {
                                        qtorfElem.css('color', '').text(item.QTORF);
                                    }
                                });
                            } else {
                                $('tr[data-CODMIS] .QTSIT').text(0);
                                $('tr[data-CODMIS] .QTORF').text(0);
                            }
                            break;
                        case "richiesta_manutenzione":
                            let avviso = valore != '0' ? `
                                <div class="col-12 alert alert-warning alert-dismissible" style="margin: 0; height: 65px;">
                                    <h5 class="font-md"><i class="icon fas fa-exclamation-triangle"></i> Manutenzione Richiesta!</h5>
                                </div>` : `
                                <div class="col-12 alert alert-success alert-dismissible" style="margin: 0; height: 65px;">
                                    <h5 class="font-md"><i class="icon fas fa-check"></i> Macchina in Funzione!</h5>
                                </div>`;
                            $('#avviso_manutenzione').html(avviso);
                            $('#richiesta_manutenzione').toggleClass('d-none', valore != '0');
                            $('#conferma_intervento').toggleClass('d-none', valore == '0');
                            break;
                        case "TCcodlavor":
                        case "T1codlavor":
                        case "id_macchina":
                        case "indirizzo_ip":
                        case "subnet_mask":
                        case "gateway":
                        case "dns
                        case "ip_local_server":
                        case "porta_local_server":
                        case "network_name":
                        case "prefisso":
                        case "lotto":
                        case "articolo":
                            $(`#${key}`).text(valore ? valore : '--');
                            break;
                        case "note_lavorazione":
                            $(`#${key}`).val(valore ? valore : '');
                            break;
                        default:
                            $(`#${key}`).text(valore);
                            break;
                    }
                });

            }).fail(function(jqXHR, textStatus) {
                console.log(jqXHR, textStatus);
                Swal.fire('Errore Generico!', '', 'error');
            });
        }

    </script>
@endsection
