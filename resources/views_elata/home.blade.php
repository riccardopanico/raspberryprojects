@extends('index')
@section('main')
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
            <div class="row mt-2">
                <span id="richiesta_manutenzione" onclick="settingsSave('richiesta_manutenzione')" class="{{ $richiesta_manutenzione ? 'd-none' : '' }} col btn btn-app bg-danger m-0">
                    <div class="centra" style="line-height: 1.2;"><i class="fa-lg fas fa-bullhorn"></i><br> <b>RICHIESTA<br>INTERVENTO</b></div>
                </span>
                <span id="conferma_intervento" onclick="settingsSave('conferma_intervento')" class="{{ !$richiesta_manutenzione ? 'd-none' : '' }} col btn btn-app bg-success m-0">
                    <div class="centra" style="line-height: 1.2;"><i class="fa-lg fas fa-bullhorn"></i><br> <b>INTERVENTO<br>EFFETTUATO</b></div>
                </span>
                <span onclick="settingsSave('last_barcode')" class="col btn btn-app bg-secondary m-0">
                    <div class="centra"><i class="fas fa-barcode"></i><br> <b>SCANSIONA</b></div>
                </span>
            </div>
            {{-- <div class="row mt-2"> --}}
                {{-- <span data-toggle="modal" data-target="#manuale-modal" class="col btn btn-app bg-primary m-0">
                    <div class="centra"><i class="fa-lg fas fa-file-pdf"></i><br> <b>MANUALE</b></div>
                </span> --}}
                {{-- <span onclick="scanCode()" data-toggle="modal" data-target="#scan-modal" data-backdrop="static" class="col btn btn-app bg-secondary"> --}}
                {{-- <span onclick="settingsSave('last_barcode')" class="col btn btn-app bg-secondary m-0">
                    <div class="centra"><i class="fas fa-barcode"></i><br> <b>SCANSIONA</b></div>
                </span>
            </div> --}}
            <div class="row mt-2" id="avviso_manutenzione"> </div>
        </div>
    </div>

      <div class="modal fade" id="manuale-modal" style="zoom: 1.1;">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Manuale d'uso</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="scan-modal" >
        <div class="modal-dialog" style="height: 90%;">
            <div class="ocrloader">
                <p>SCANSIONE</p>
                <em></em>
                <span></span>
            </div>
        </div>
      </div>

@endsection
@section('script')

    <script>
        var FornitoriRecapiti = @json($FornitoriRecapiti);
        var scansioneEffettuata = false;

        var customLayout = {
            'default': [
                '1 2 3 4',
                '5 6 7 8',
                '9 0 - =',
                'q w e r',
                't y u i',
                'o p [ ]',
                'a s d f',
                'g h j k',
                'l z x c',
                'v b n m',
                '` , . /',
                '\\ {shift} {alt} {enter}',
                '{accept} {space} {bksp} {cancel}',
            ],
            'shift': [
                '! @ # $',
                '% ^ & *',
                '( ) _ +',
                'Q W E R',
                'T Y U I',
                'O P { }',
                'A S D F',
                'G H J K',
                'L Z X C',
                'V B N M',
                '~ < > ?',
                '| {shift} {alt} {enter}',
                '{accept} {space} {bksp} {cancel}',
            ],
            'alt': [
                'à è ì ò',
                'ù Á É Í',
                'Ó Ú Ý ã',
                'õ ñ ç €',
                '{empty} {empty} {empty} {empty}',
                '{empty} {empty} {empty} {empty}',
                '{empty} {empty} {empty} {empty}',
                '{empty} {empty} {empty} {empty}',
                '{empty} {empty} {empty} {empty}',
                '{empty} {empty} {empty} {empty}',
                '{empty} {empty} {empty} {empty}',
                '{empty} {shift} {alt} {enter}',
                '{accept} {space} {bksp} {cancel}',
            ]
        };

        var IPCustomLayout = {
            'default': [
                '1 2 3',
                '4 5 6',
                '7 8 9',
                '{bksp} 0 {empty}',
                '{accept} . {cancel}',
            ]
        };

        var NUMCustomLayout = {
            'default': [
                '1 2 3',
                '4 5 6',
                '7 8 9',
                '{bksp} 0 {empty}',
                '{accept} {empty} {cancel}',
            ]
        };

        var TraduzioneTastiera = { 'accept': '✔', 'cancel': 'Chiudi',  'bksp': '\u2190',  };

        $('#manuale-modal').on('shown.bs.modal', function () {
            var iframe = `<iframe src="{{ asset("pdf/manuale_uso.pdf") }}" width="100%" ></iframe>`;
            $(this).find('.modal-body').html(iframe);
        });

        $(document).ready(function(){
            getSettings();
            isConnected();
        });

        function isConnected() {
            $.ajax({
                url: "{{ url('/ping/' . $ip_local_server) }}",
                type: "GET",
                timeout: 5000,
                success: function (data) {
                    if (data.result) {
                        $('.nav-item .fa-wifi').removeClass('text-primary').addClass('text-success');
                    } else {
                        $('.nav-item .fa-wifi').removeClass('text-success').addClass('text-primary');
                    }
                },
                error: function () {
                    $('.nav-item .fa-wifi').removeClass('text-success').addClass('text-primary');
                }
            });
        }

        var isConnectedInterval = setInterval(() => {
            isConnected();
        }, 15000);


        var getSettingsIntervall = setInterval(() => {
            getSettings();
        }, 30000);

        async function autenticazione() {
            const result = await Swal.fire({
                title: 'Autenticazione operatore',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Conferma',
                denyButtonText: 'Annulla',
                input: 'text',
                customClass: {
                    input: 'keyboard_input',
                },
                inputPlaceholder: 'Codice operatore',
                inputValidator: (operatore) => {
                    return new Promise((resolve) => {
                        if (operatore) {

                            $.ajax({
                                type: 'POST',
                                dataType: 'json',
                                async: true,
                                url: "{{ route('autenticaOperazione') }}",
                                data: {
                                    operatore:operatore,
                                }
                            }).done(function(data) {
                                if (data.success) {
                                    resolve();
                                } else {
                                    $('.keyboard_input').val('');
                                    resolve('Autenticazione Fallita!');
                                    setTimeout(function(){$(".keyboard_input").getkeyboard().close();},1);
                                }
                            }).fail(function(jqXHR, textStatus) {
                                Swal.fire('Errore Generico!', '', 'error');
                            });

                        } else {
                            resolve('Devi autenticarti per procedere!');
                        }
                    });
                },
                didOpen: () => {
                    // Cattura l'evento touch su $('.keyboard_input') e stampa in console
                    $('.keyboard_input').on('touchstart', function () {
                        $(this).keyboard({
                            layout: 'custom',
                            customLayout: NUMCustomLayout,
                            css: {
                                buttonDefault: 'custom-button',
                                buttonHover: 'custom-button-hover',
                                buttonAction: 'custom-button-active',
                                buttonDisabled: 'custom-button-disabled'
                            },
                            acceptValid: true,
                            display: TraduzioneTastiera
                        });
                    });
                }
            });

            if (result.isConfirmed) {
                console.log('Confermato:', result.value);
                return result.value;
            }
        }


        async function settingsSave(action, more_info = null) {
            var operatore = null;
            if([
                // OLD
                'richiesta_manutenzione',
                // 'last_barcode',
                'conferma_intervento'

                // // NEW
                // 'last_barcode',
                // 'network_name',
                // 'ip_macchina',
                // 'ip_local_server',
                // 'richiesta_manutenzione',
                // 'conferma_intervento',
                // 'id_macchina',
                // 'gateway',
                // 'dns_nameservers',
                // 'subnet'
            ].includes(action)){
                operatore = await autenticazione();
                if(!operatore) {
                    Swal.fire('Devi autenticarti per procedere!', '', 'error');
                    return;
                }
            }

            const actionOptions = {
                'id_macchina': getIdMacchinaOptions(),
                'gateway': getGatewayOptions(),
                'dns_nameservers': getDnsOptions(),
                'subnet': getSubnetOptions(),

                'last_barcode': getLastBarcodeOptions(),
                'network_name': getNetworkNameOptions(more_info),
                'ip_macchina': getIpMacchinaOptions(),
                'ip_local_server': getIpLocalServerOptions(),
                'richiesta_manutenzione': getRichiestaManutenzioneOptions(),
                'conferma_intervento': getConfermaInterventoOptions()
            };

            const swal_option = {
                title: actionOptions[action].msg,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Conferma',
                denyButtonText: 'Annulla',
                ...actionOptions[action].swalOptions
            };

            Swal.fire(swal_option).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        async: true,
                        url: "{{ route('settingsSave') }}",
                        data: {
                            id_macchina:{{ $id_macchina }},
                            action:action,
                            input:actionOptions[action].input(),
                            more_info:more_info,
                            operatore:operatore,
                        }
                    }).done(function(data) {
                        if (data.success) {
                            if(action == 'last_barcode') {
                                scansioneEffettuata = true;
                            }
                            getSettings();
                        } else {
                            Swal.fire('Errore invio richiesta!', data.msg, 'error');
                        }
                    }).fail(function(jqXHR, textStatus) {
                        Swal.fire('Errore Generico!', '', 'error');
                    });
                }
            });
        }


        function getConfermaInterventoOptions() {
            let inputValue;
            return {
                msg: `Confermare <b><i>INTERVENTO EFFETTUATO</i></b>?`,
                input: () => 0,
                swalOptions: {}
            };
        }

        function getNetworkNameOptions(ssid) {
            let inputValue;
            return {
                msg: `Inesrisci la password per "${ssid}"`,
                input: () => inputValue,
                swalOptions: {
                    input: 'text',
                    inputPlaceholder: 'Password',
                    inputValidator: (value) => validateBasic(value, (validInput) => inputValue = validInput),
                    customClass: {
                        input: 'keyboard_input',
                    },
                    didOpen: () => {
                        $('.keyboard_input').keyboard({
                            layout: 'custom',
                            customLayout: customLayout,
                            css: {
                                buttonDefault: 'custom-button',
                                buttonHover: 'custom-button-hover',
                                buttonAction: 'custom-button-active',
                                buttonDisabled: 'custom-button-disabled'
                            },
                            acceptValid: true,
                            display: TraduzioneTastiera
                        });
                    },
                }
            };
        }

        function getRichiestaManutenzioneOptions() {
            let inputValue;
            const inputOptions = FornitoriRecapiti.reduce((obj, item) => {
                obj[item.ID] = item.COGNOME + ' ' + item.NOME;
                return obj;
            }, {});

            return {
                msg: `Confermare <b><i>RICHIESTA INTERVENTO</i></b>?`,
                input: () => inputValue,
                swalOptions: {
                    input: 'select',
                    inputOptions: inputOptions,
                    inputPlaceholder: 'Seleziona destinatario richiesta!',
                    inputValidator: (value) => validateRequired(value, (validInput) => inputValue = validInput)
                }
            };
        }

        function getIdMacchinaOptions() {
            let inputValue;
            return {
                msg: `Inserisci <b>ID MACCHINA</b>`,
                input: () => inputValue,
                swalOptions: {
                    input: 'text',
                    inputPlaceholder: 'ID Macchina',
                    inputValidator: (value) => validateRequired(value, (validInput) => inputValue = validInput),
                    customClass: {
                        input: 'keyboard_input',
                    },
                    didOpen: () => {
                        $('.keyboard_input').keyboard({
                            layout: 'custom',
                            customLayout: NUMCustomLayout,
                            css: {
                                buttonDefault: 'custom-button',
                                buttonHover: 'custom-button-hover',
                                buttonAction: 'custom-button-active',
                                buttonDisabled: 'custom-button-disabled'
                            },
                            acceptValid: true,
                            display: TraduzioneTastiera
                        });
                    },
                }
            };
        }
        function getGatewayOptions() {
            let inputValue;
            return {
                msg: `Inserisci <b>GATEWAY</b>`,
                input: () => inputValue,
                swalOptions: {
                    input: 'text',
                    inputPlaceholder: 'Gateway',
                    inputValidator: (value) => validateIp(value, (validInput) => inputValue = validInput),
                    customClass: {
                        input: 'keyboard_input',
                    },
                    didOpen: () => {
                        $('.keyboard_input').keyboard({
                            layout: 'custom',
                            customLayout: IPCustomLayout,
                            css: {
                                buttonDefault: 'custom-button',
                                buttonHover: 'custom-button-hover',
                                buttonAction: 'custom-button-active',
                                buttonDisabled: 'custom-button-disabled'
                            },
                            acceptValid: true,
                            display: TraduzioneTastiera
                        });
                    },
                }
            };
        }
        function getDnsOptions() {
            let inputValue;
            return {
                msg: `Inserisci <b>DNS</b>`,
                input: () => inputValue,
                swalOptions: {
                    input: 'text',
                    inputPlaceholder: 'DNS',
                    inputValidator: (value) => validateIp(value, (validInput) => inputValue = validInput),
                    customClass: {
                        input: 'keyboard_input',
                    },
                    didOpen: () => {
                        $('.keyboard_input').keyboard({
                            layout: 'custom',
                            customLayout: IPCustomLayout,
                            css: {
                                buttonDefault: 'custom-button',
                                buttonHover: 'custom-button-hover',
                                buttonAction: 'custom-button-active',
                                buttonDisabled: 'custom-button-disabled'
                            },
                            acceptValid: true,
                            display: TraduzioneTastiera
                        });
                    },
                }
            };
        }
        function getSubnetOptions() {
            let inputValue;
            return {
                msg: `Inserisci <b>SUBNET</b>`,
                input: () => inputValue,
                swalOptions: {
                    input: 'text',
                    inputPlaceholder: 'Subnet',
                    inputValidator: (value) => validateIp(value, (validInput) => inputValue = validInput),
                    customClass: {
                        input: 'keyboard_input',
                    },
                    didOpen: () => {
                        $('.keyboard_input').keyboard({
                            layout: 'custom',
                            customLayout: IPCustomLayout,
                            css: {
                                buttonDefault: 'custom-button',
                                buttonHover: 'custom-button-hover',
                                buttonAction: 'custom-button-active',
                                buttonDisabled: 'custom-button-disabled'
                            },
                            acceptValid: true,
                            display: TraduzioneTastiera
                        });
                    },
                }
            };
        }

        function getLastBarcodeOptions() {
            let inputValue;
            return {
                msg: `Confermare <b><i>SCANSIONE BARCODE</i></b>?`,
                input: () => inputValue,
                swalOptions: {
                    input: 'text', inputPlaceholder: 'Barcode',
                    inputValidator: (value) => validateBasic(value, (validInput) => inputValue = validInput),
                    customClass: {
                        input: 'keyboard_input',
                    },
                    didOpen: () => {
                        // Cattura l'evento touch su $('.keyboard_input') e stampa in console
                        $('.keyboard_input').on('touchstart', function () {
                            $(this).keyboard({
                                layout: 'custom',
                                customLayout: NUMCustomLayout,
                                css: {
                                    buttonDefault: 'custom-button',
                                    buttonHover: 'custom-button-hover',
                                    buttonAction: 'custom-button-active',
                                    buttonDisabled: 'custom-button-disabled'
                                },
                                acceptValid: true,
                                display: TraduzioneTastiera
                            });
                        });
                    }
                },
            };
        }

        function validateBasic(value, setInput) {
            return new Promise((resolve) => { setInput(value); resolve(); });
        }

        function validateRequired(value, setInput) {
            return new Promise((resolve) => {
                if (value) {
                    setInput(value);
                    resolve();
                } else {
                    resolve('Il campo è obbligatorio!');
                }
            });
        }

        function validateIp(value, setInput) {
            return new Promise((resolve) => {
                const ipPattern = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
                if (ipPattern.test(value)){
                    setInput(value);
                    resolve();
                } else {
                    resolve('IP non valido!');
                }
            });
        }

        function getIpMacchinaOptions() {
            let inputValue;
            return {
                msg: `<b><i>INDIRIZZO IP</i></b> da assegnare alla macchina`,
                input: () => inputValue,
                swalOptions: {
                    input: 'text',
                    inputPlaceholder: 'Indirizzo IP',
                    inputValidator: (value) => validateIp(value, (validInput) => inputValue = validInput),
                    customClass: {
                        input: 'keyboard_input',
                    },
                    didOpen: () => {
                        $('.keyboard_input').keyboard({
                            layout: 'custom',
                            customLayout: IPCustomLayout,
                            css: {
                                buttonDefault: 'custom-button',
                                buttonHover: 'custom-button-hover',
                                buttonAction: 'custom-button-active',
                                buttonDisabled: 'custom-button-disabled'
                            },
                            acceptValid: true,
                            display: TraduzioneTastiera
                        });
                    },
                }
            };
        }

        function getIpLocalServerOptions() {
            let inputValue;
            return {
                msg: `<b><i>INDIRIZZO IP</i></b> per la connessione al server`,
                input: () => inputValue,
                swalOptions: {
                    input: 'text',
                    inputPlaceholder: 'Indirizzo IP',
                    inputValidator: (value) => validateIp(value, (validInput) => inputValue = validInput),
                    customClass: {
                        input: 'keyboard_input',
                    },
                    didOpen: () => {
                        $('.keyboard_input').keyboard({
                            layout: 'custom',
                            customLayout: IPCustomLayout,
                            css: {
                                buttonDefault: 'custom-button',
                                buttonHover: 'custom-button-hover',
                                buttonAction: 'custom-button-active',
                                buttonDisabled: 'custom-button-disabled'
                            },
                            acceptValid: true,
                            display: TraduzioneTastiera
                        });
                    },
                }
            };
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
                let situazioneInsufficiente = false;

                $.each(data, function(i, v) {
                    switch (v.codice) {
                        case "TCdata_lavor":
                        case "T1data_lavor":
                            var data = v.valore ? moment(v.valore).format('DD/MM/YYYY') : '--';
                            $(`#${v.codice}`).text(data);
                            break
                        case "PE1situazione":
                        case "PE2situazione":
                        case "PE3situazione":
                            var situazione = JSON.parse(v.valore);

                            if (Array.isArray(situazione) && situazione.length > 0) {
                                var $container = $("#" + v.codice);

                                // Aggiorna i testi in base all'array situazione
                                $container.find(".CODART").text(situazione[0].CODART);
                                $container.find(".QTSIT").text(situazione[0].QTSIT);
                                if (situazione[0].CONSUMO_MINORE_QTSIT == 1) {
                                    situazioneInsufficiente = true;
                                    $container.find(".QTORF").css('color', 'red').text(situazione[0].QTORF);
                                } else {
                                    $container.find(".QTORF").css('color', '').text(situazione[0].QTORF);
                                }
                            } else {
                                // Se l'array è vuoto, imposta tutti i .description-text a "--"
                                $("#" + v.codice + " .description-text").text("--");
                            }
                            break;
                        case "TCcodlavor":
                        case "T1codlavor":
                        case "id_macchina":
                        case "ip_macchina":
                        case "subnet":
                        case "gateway":
                        case "dns_nameservers":
                        case "ip_local_server":
                        case "porta_local_server":
                        case "network_name":
                        case "prefisso":
                        case "lotto":
                        case "articolo":
                            $(`#${v.codice}`).text(v.valore?v.valore:'--');
                            break;
                        case "manuale_uso": break;
                        case "SUsituazione":
                            var situazione = JSON.parse(v.valore);

                            if (Array.isArray(situazione) && situazione.length > 0) {
                                $('tr[data-CODMIS] .QTSIT').text(0);
                                $('tr[data-CODMIS] .QTORF').text(0);
                                if(situazione.success !== false ) {
                                    situazione.forEach(function(item) {
                                        $('tr[data-CODMIS="' + item.CODMIS + '"] .QTSIT').text(item.QTSIT);
                                        if(item.CONSUMO_MINORE_QTSIT == 1) {
                                            situazioneInsufficiente = true;
                                            $('tr[data-CODMIS="' + item.CODMIS + '"] .QTORF').css('color', 'red').text(item.QTORF);
                                        } else {
                                            $('tr[data-CODMIS="' + item.CODMIS + '"] .QTORF').css('color', '').text(item.QTORF);
                                        }
                                    });
                                }
                            } else {
                                $('tr[data-CODMIS] .QTSIT').text(0);
                                $('tr[data-CODMIS] .QTORF').text(0);
                            }
                        case "______":
                            $(`#${v.codice}`).text(v.valore);
                            break;
                        case "richiesta_manutenzione":
                            if(v.valore != '0'){
                                var avviso = `
                                <div class="col-12 alert alert-warning alert-dismissible" style=" margin: 0; height: 65px; ">
                                    <h5 class="font-md"><i class="icon fas fa-exclamation-triangle"></i> Manutenzione Richiesta!</h5>
                                    <div class="font-md"> <p class="font-bold"><i></i></p> </div>
                                </div>`;
                                $('#richiesta_manutenzione').addClass('d-none');
                                $('#conferma_intervento').removeClass('d-none');
                            } else {
                                var avviso = `
                                <div class="col-12 alert alert-success alert-dismissible" style=" margin: 0; height: 65px; ">
                                    <h5 class="font-md"><i class="icon fas fa-check"></i> Macchina in Funzione!</h5>
                                    <div class="font-md"> <p class="font-bold"><i></i></p> </div>
                                </div>`;
                                $('#richiesta_manutenzione').removeClass('d-none');
                                $('#conferma_intervento').addClass('d-none');
                            }
                            $('#avviso_manutenzione').html(avviso);
                            break;
                    }
                });

                if (scansioneEffettuata && situazioneInsufficiente) {
                    scansioneEffettuata = false;
                    Swal.fire('Situazione di magazzino insufficiente!', '', 'error');
                }

            }).fail(function(jqXHR, textStatus) {
                $(`#network_name`).text('--');
                console.log(jqXHR, textStatus);
                Swal.fire('Errore Generico!', '', 'error');
            });
        }

        var ultimoTempoChiamata = 0;
        function retiDisponibili() {
            const tempoCorrente = Date.now();

            if (tempoCorrente - ultimoTempoChiamata >= 10000) {

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    async: true,
                    url: "{{ route('retiDisponibili') }}",
                    data: {}
                }).done(function(ssids) {
                    console.log(ssids);

                    var ssidElements = ssids.map(function(ssid) {
                        return `<a href="#" onclick="settingsSave('network_name', '${ssid}')" class="dropdown-item"> <i class="text-primary fa-sm mr-2 fas fa-wifi"></i>${ssid}</a><div class="dropdown-divider"></div>`;
                    }).join('');

                    var html = `<span class="dropdown-header">RETI DISPONIBILI</span> <div class="dropdown-divider"></div> ${ssidElements}`;

                    $('#retiDisponibili').html(html);

                }).fail(function(jqXHR, textStatus) {
                    console.log(jqXHR, textStatus);
                    if(textStatus != "abort") {
                        Swal.fire('Errore rilevamento reti!', '', 'error');
                    }
                });

                // Aggiorna il timestamp dell'ultima chiamata
                ultimoTempoChiamata = tempoCorrente;
            }
        }

        function shutdown() {

            Swal.fire({
                title: 'Sicuro di voler procedere con lo spegnimento?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Conferma',
                denyButtonText: 'Annulla',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        async: true,
                        url: "{{ route('shutdown') }}",
                        data: {}
                    }).done(function(data) {
                        console.log(data);
                    }).fail(function(jqXHR, textStatus) {
                        console.log(jqXHR, textStatus);
                        if(textStatus != "abort") {
                            Swal.fire('Errore spegnimento!', '', 'error');
                        }
                    });
                }
            });
        }

    </script>
@endsection
