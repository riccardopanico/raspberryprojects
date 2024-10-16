
    <!-- jQuery -->
    <script src="{{ asset('build/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('build/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('build/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('build/adminlte/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="{{ asset('build/adminlte/dist/js/demo.js') }}"></script> --}}
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
                    // window.location.href = "logout"
                }
            },
            timeout: 0
        });
    
    </script>
    