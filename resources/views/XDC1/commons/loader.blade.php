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
        display: none;
        /* Nascondi di default */
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
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
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
<!-- Loader -->
<div id="custom-loader-overlay">
    <div class="custom-loader-container">
        <div class="custom-spinner"></div>
        {{-- <div class="custom-loader-text">Caricamento commessa...</div> --}}
    </div>
</div>
<script>
    function toggleLoader(show) {
        if (show) {
            $("#custom-loader-overlay").fadeIn(300);
        } else {
            $("#custom-loader-overlay").fadeOut(300);
        }
    }

    $(document).ajaxStart(function() {
        toggleLoader(true);
    }).ajaxStop(function() {
        toggleLoader(false);
    });
</script>
