<div id="page-loader" class="fixed inset-0 z-[99999] bg-white flex flex-col items-center justify-center transition-all duration-700 ease-out">
    <!-- Glow effect background -->
    <div class="absolute w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl animate-pulse"></div>
    
    <div class="relative z-10 flex flex-col items-center text-center px-4">
        <!-- Logo container with spinning ring -->
        <div class="relative w-24 h-24 mb-6 flex items-center justify-center">
            <!-- Outer Gradient Rotating Ring -->
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-emerald-500 border-r-pj-gold-500 animate-spin"></div>
            <!-- Inner Pulsing Ring -->
            <div class="absolute inset-2 rounded-full border-2 border-emerald-300 animate-ping opacity-20"></div>
            <!-- Logo Image -->
            <div class="w-14 h-14 rounded-2xl bg-white p-2 shadow-xl flex items-center justify-center border border-gray-100 transform hover:scale-105 transition-transform">
                <img src="{{ asset('images/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung" class="w-full h-full object-contain">
            </div>
        </div>

        <!-- App Title -->
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 mb-1">
            SIGAP
        </h2>
        <p class="text-[11px] font-semibold tracking-widest text-emerald-600 uppercase mb-6">
            Kecamatan Pasirjambu
        </p>

        <!-- Loading Bar -->
        <div class="w-48 h-1.5 bg-gray-100 rounded-full overflow-hidden relative shadow-inner">
            <div class="absolute inset-y-0 bg-gradient-to-r from-emerald-500 via-teal-400 to-pj-gold-500 rounded-full animate-loader-progress w-full"></div>
        </div>
        <p class="text-[11px] font-medium text-gray-400 mt-3.5 animate-pulse">
            Memuat Data...
        </p>
    </div>
</div>

<style>
    @keyframes loaderProgress {
        0% { transform: translateX(-100%); }
        50% { transform: translateX(0%); }
        100% { transform: translateX(100%); }
    }
    .animate-loader-progress {
        animation: loaderProgress 1.5s ease-in-out infinite;
    }
</style>

<script>
    (function() {
        const loader = document.getElementById('page-loader');
        if (!loader) return;

        function hideLoader() {
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(function() {
                loader.style.display = 'none';
            }, 700);
        }

        if (document.readyState === 'complete') {
            setTimeout(hideLoader, 250);
        } else {
            window.addEventListener('load', function() {
                setTimeout(hideLoader, 250);
            });
            // Safety fallback max 3 seconds
            setTimeout(hideLoader, 3000);
        }

        // Show smooth loader on internal page link navigation
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.target && !link.hasAttribute('download')) {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                    try {
                        const url = new URL(link.href, window.location.href);
                        if (url.origin === window.location.origin && url.pathname !== window.location.pathname) {
                            loader.style.display = 'flex';
                            loader.classList.remove('opacity-0', 'pointer-events-none');
                        }
                    } catch (err) {}
                }
            }
        });
    })();
</script>
