{{--
    PWA: lets the app be installed to a phone's home screen and open
    standalone (no browser address bar). Included from every layout that
    renders its own <head>.
--}}
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#4f46e5">

{{-- Browser tab / Android launcher --}}
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icons/icon-192.png') }}">
<link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/icons/icon-512.png') }}">

{{-- iOS home screen (Safari ignores the manifest for these) --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="E-Perpus">
<link rel="apple-touch-icon" href="{{ asset('images/icons/apple-touch-icon.png') }}">

<script>
    // Service workers need a secure context: HTTPS in production, or localhost
    // while developing. Anything else (e.g. a plain-HTTP LAN IP) is skipped.
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(function (e) {
                console.warn('Service worker registration failed:', e);
            });
        });
    }
</script>
