<script src="{{ mix('build/js/all.js') }}"></script>
<script>
    $(document).ready(function() {
        let timeout;
        const idleTimeOtherPages = 10000; // 10 secondi per pagine non home
        const idleTimeHomeMenuOpen = 5000; // 5 secondi per home con menu aperto

        function closeMenu() {
            $('[data-widget="pushmenu"]').PushMenu('collapse');
        }

        function isMenuOpen() {
            return !$('body').hasClass('sidebar-collapse');
        }

        function resetTimer() {
            clearTimeout(timeout);
            @if (request()->is('/') || Route::currentRouteName() == 'home')
                if (isMenuOpen()) {
                    timeout = setTimeout(closeMenu, idleTimeHomeMenuOpen);
                }
            @else
                timeout = setTimeout(function() {
                    window.location.href = '{{ route('home') }}';
                }, idleTimeOtherPages);
            @endif
        }

        // Reset del timer ad ogni interazione
        $(document).on('mousemove keypress click scroll', resetTimer);

        // Avvia il timer iniziale
        resetTimer();
    });

    var autocloseMsgInterval = null;
    function clearCountdown() {
        clearInterval(autocloseMsgInterval);
        $('.nav-item.info_badge').show();
        $('#countdown-nav').hide();
    }

    function startCountdown(durationInSec) {
        // Mostra l'elemento countdown
        $('.nav-item.info_badge').hide();
        $('#countdown-nav').fadeIn();

        // Recupera riferimenti
        const $circle = $('#countdown-nav .countdown-circle');
        const $number = $('#countdown-nav .countdown-number');

        // Calcolo circonferenza
        const radius = 22;
        const circumference = 2 * Math.PI * radius;

        // Cerchio inizialmente pieno (offset=0) e rotazione -90deg per partire dall'alto
        $circle.css({
            'stroke-dasharray': circumference,
            'stroke-dashoffset': 0,
            'transform': 'rotate(-90deg)',
            'transform-origin': '50% 50%'
        });

        // Timestamp iniziale
        const startTime = Date.now();

        // Aggiorna countdown a intervalli
        autocloseMsgInterval = setInterval(() => {
            const elapsed = (Date.now() - startTime) / 1000;
            let remaining = durationInSec - elapsed;
            if (remaining < 0) remaining = 0;

            // Aggiorna numero (arrotondato)
            $number.text(Math.ceil(remaining));

            // Aggiorna cerchio al contrario (da 0 a circumference)
            const progress = elapsed / durationInSec;
            const offset = circumference * progress;
            $circle.css('stroke-dashoffset', offset);

            // Se finito, stop e nascondi
            if (remaining <= 0) {
                clearCountdown();
            }
        }, 200); // ogni 200ms: frequenza di aggiornamento
    }

</script>
