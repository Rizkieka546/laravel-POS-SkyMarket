@if (session('success') || session('error') || session('info'))
    <div id="notification"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm z-50">
        <div id="notification-content"
            class="relative bg-white text-gray-800 p-10 rounded-2xl shadow-2xl max-w-xl w-full border-l-8 transform translate-y-10 opacity-0 transition-all duration-500 ease-out
            @if (session('success')) border-green-500
            @elseif (session('error')) border-red-500
            @elseif (session('info')) border-blue-500 @endif
            ">
            <!-- Icon + Title -->
            <div class="flex items-center mb-6">
                @if (session('success'))
                    <div class="bg-green-100 text-green-600 rounded-full p-4 mr-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold">Berhasil!</h3>
                @elseif (session('error'))
                    <div class="bg-red-100 text-red-600 rounded-full p-4 mr-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold">Gagal!</h3>
                @elseif (session('info'))
                    <div class="bg-blue-100 text-blue-600 rounded-full p-4 mr-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M12 18h.01" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold">Info</h3>
                @endif
            </div>

            <!-- Message -->
            <p class="text-lg text-gray-700 leading-relaxed">
                {{ session('success') ?? (session('error') ?? session('info')) }}
            </p>

            <!-- Close Button -->
            <button id="close-btn"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-3xl font-bold leading-none focus:outline-none"
                aria-label="Close notification">
                &times;
            </button>
        </div>
    </div>

    <script>
        window.onload = function() {
            const notification = document.getElementById('notification');
            const content = document.getElementById('notification-content');

            // Animate in
            setTimeout(() => {
                content.classList.remove('translate-y-10', 'opacity-0');
                content.classList.add('translate-y-0', 'opacity-100');
            }, 100);

            // Auto hide after 3.5 seconds
            setTimeout(() => {
                content.classList.remove('translate-y-0', 'opacity-100');
                content.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 500);
            }, 1000);

            // Close button handler
            document.getElementById('close-btn').addEventListener('click', function() {
                content.classList.remove('translate-y-0', 'opacity-100');
                content.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 500);
            });
        };
    </script>
@endif
