    <script src="{{ mix('build/js/all.js') }}"></script>
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
