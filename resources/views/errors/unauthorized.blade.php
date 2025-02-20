<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops! Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    .blink {
        animation: blink 0.5s infinite;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-10px);
        }

        50% {
            transform: translateX(10px);
        }

        75% {
            transform: translateX(-10px);
        }
    }

    .shake {
        animation: shake 0.3s infinite;
    }

    @keyframes annoying {
        0% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(3deg);
        }

        50% {
            transform: rotate(-3deg);
        }

        75% {
            transform: rotate(3deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    .annoying {
        animation: annoying 0.1s infinite;
    }
    </style>
</head>

<body class="flex items-center justify-center h-screen bg-gradient-to-r from-red-400 to-red-700 text-white">
    <audio autoplay>
        <source src="https://www.myinstants.com/media/sounds/error-sound.mp3" type="audio/mpeg">
    </audio>

    <div class="max-w-lg text-center bg-white shadow-2xl rounded-2xl p-10 border border-gray-300 text-black annoying">
        <h1 class="text-6xl font-extrabold text-red-600 blink mb-4">666</h1>
        <h2 class="text-3xl font-bold text-gray-900 shake">🤡 SERIUS MAU KE SINI?!</h2>
        <p class="text-gray-700 mt-3 text-lg">Hei! Apa kamu yakin ini tempat yang benar? <br> Sudah pasti bukan! Hahaha
            😜</p>

        <div class="mt-8 relative">
            <a href="javascript:history.back()" id="backButton"
                class="bg-red-600 hover:bg-red-800 text-white font-semibold px-6 py-3 rounded-lg transition-all block relative cursor-pointer">
                😏 Kembali Saja Deh
            </a>
        </div>
    </div>

    <script>
    const button = document.getElementById('backButton');
    let isMoving = false;

    button.addEventListener('mouseover', function() {
        if (!isMoving) {
            isMoving = true;
            let x = Math.random() * 300 - 150;
            let y = Math.random() * 300 - 150;
            button.style.transform = `translate(${x}px, ${y}px)`;
            setTimeout(() => {
                isMoving = false;
            }, 500);
        }
    });
    </script>
</body>

</html>