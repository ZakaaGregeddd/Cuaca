<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Page</title>
    <link rel="stylesheet" href="{{ asset('css/aboutStyle.css') }}">
    <script>
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('live-time').textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateTime, 1000);
        window.onload = updateTime;
    </script>
</head>
<body>
    
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">Cuaca</a>
            <ul class="navbar-menu">
                <li><a href="/cuaca">Beranda</a></li>
                <li><a href="/cuaca_db">Riwayat</a></li>
                <li><a href="/about">Tentang</a></li>
            </ul>
        </div>
    </nav>
    {{-- <div id="about-intro"> --}}
    <div class="frame-9">
        <div class="frame-22">
            <div class="frame-21">
                <div class="frame-19">
                    <img class="aboutbawah" src="{{ asset('img/aboutatas.png') }}" />
                    <img class="about-tengah" src="{{ asset('img/about-tengah.png') }}" />
                    <img class="aboutbawah2" src="{{ asset('img/aboutbawah.png') }}" />
                </div>
                <div class="frame-20">
                    <img class="zakaatext" src="{{ asset('img/Zakaatext.png') }}" />
                    <img class="muhammad" src="{{ asset('img/muhammad.png') }}" />
                    <img class="shahzada" src="{{ asset('img/Shahzada.png') }}" />
                </div>
            </div>
            <div class="frame-18">
                <img class="fotoosasdasdao-1" src="{{ asset('img/zakaa.png') }}" />
                <div class="_12-00-wib" id="live-time"></div>
            </div>
        </div>
    </div>  
    <div class="konten fade-up">
        <div class="text-konten">
            <h1>Tentang Kami</h1>
            <p>Cuaca adalah website cuaca yang memberikan informasi terkini tentang kondisi cuaca di berbagai lokasi. 
                Dengan website ini pengguna dapat dengan cepat mendapatkan informasi cuaca yang mereka butuhkan.</p>
            <p>Saya berdedikasi untuk memberikan pengalaman terbaik bagi pengguna. 
                Dan saya akan terus berupaya meningkatkan aplikasi ini dengan fitur-fitur baru dan pembaruan berkala.</p>
        </div>
    </div>
    {{-- </div>       --}}
    <script>
        window.addEventListener('load', () => {
            const elementsToFade = [
                '.aboutbawah', '.about-tengah', '.aboutbawah2',
                '.zakaatext', '.muhammad', '.shahzada',
                '.fotoosasdasdao-1', '._12-00-wib', '.frame-9'
            ];

            setTimeout(() => {
                elementsToFade.forEach(selector => {
                    const el = document.querySelector(selector);
                    if (el) {
                        el.classList.add('intro-fade');
                    }
                });
            }, 1700);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fadeElements = document.querySelectorAll('.fade-up');
    
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                    } else {
                        entry.target.classList.remove('show');
                    }
                });
            }, {
                threshold: 0.3
            });
    
            fadeElements.forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>