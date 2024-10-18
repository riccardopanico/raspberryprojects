<!-- jQuery -->
<script src="{{ asset('node_modules/admin-lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('node_modules/admin-lte/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('node_modules/admin-lte/dist/js/demo.js') }}"></script> --}}
<!-- SweetAlert2 -->
<script src="{{ asset('node_modules/admin-lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('node_modules/admin-lte/plugins/toastr/toastr.min.js') }}"></script>
<!-- jquery-ui -->
<link rel="stylesheet" href="{{ asset('node_modules/jquery-ui-dist/jquery-ui.min.css') }}"/>
<script src="{{ asset('node_modules/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<!-- virtual-keyboard -->
<link rel="stylesheet" href="{{ asset('node_modules/virtual-keyboard/dist/css/keyboard.min.css') }}"/>
<script src="{{ asset('node_modules/virtual-keyboard/dist/js/jquery.keyboard.min.js') }}"></script>
<!-- moment -->
<script src="{{ asset('node_modules/moment/min/moment.min.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        statusCode: {
            301: function(responseObject, textStatus, errorThrown) {
                console.log(responseObject, textStatus, errorThrown);
            },
            302: function(responseObject, textStatus, errorThrown) {
                window.location.href = responseObject.responseJSON.url_redirect;
            },
            419: function(responseObject, textStatus, errorThrown) {
                // window.location.href = "{{ route('logout') }}"
            }
        },
        timeout: 0
    });

    function logout(){

        Swal.fire({
            title: "Sicuro di voler uscire?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Conferma',
            denyButtonText: 'Annulla',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.replace("{{ route('logout') }}");

            }
        })

    }

</script>
