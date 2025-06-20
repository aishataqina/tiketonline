<!-- Carousel Component -->
<div class="container">
    <div id="tiketCarousel">
        <!-- Bootstrap CSS minimal untuk carousel -->
        <style>
            #tiketCarousel {
                width: 100%;
                max-width: 100%;
                margin: 0 auto;
                position: relative;
            }

            #tiketCarousel .carousel {
                position: relative;
            }

            #tiketCarousel .carousel-inner {
                position: relative;
                width: 100%;
                overflow: hidden;
            }

            #tiketCarousel .carousel-item {
                position: relative;
                display: none;
                float: left;
                width: 100%;
                margin-right: -100%;
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
                transition: transform .6s ease-in-out;
            }

            #tiketCarousel .carousel-item.active {
                display: block;
            }

            #tiketCarousel .carousel-item img {
                height: 300px;
                object-fit: cover;
                width: 100%;
                display: block;
            }

            #tiketCarousel .carousel-caption {
                position: absolute;
                right: 15%;
                bottom: 20px;
                left: 15%;
                padding-top: 20px;
                padding-bottom: 20px;
                color: #fff;
                text-align: center;
                background: rgba(0, 0, 0, 0.5);
                border-radius: 10px;
            }

            #tiketCarousel .carousel-control-prev,
            #tiketCarousel .carousel-control-next {
                position: absolute;
                top: 0;
                bottom: 0;
                z-index: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 15%;
                color: #fff;
                text-align: center;
                background: none;
                border: 0;
                opacity: 0.5;
                transition: opacity 0.15s ease;
                text-decoration: none;
            }

            #tiketCarousel .carousel-control-prev {
                left: 0;
            }

            #tiketCarousel .carousel-control-next {
                right: 0;
            }

            #tiketCarousel .carousel-control-prev-icon,
            #tiketCarousel .carousel-control-next-icon {
                display: inline-block;
                width: 2rem;
                height: 2rem;
                background-repeat: no-repeat;
                background-position: 50%;
                background-size: 100% 100%;
            }

            #tiketCarousel .carousel-control-prev-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e");
            }

            #tiketCarousel .carousel-control-next-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            }

            #tiketCarousel .carousel-indicators {
                position: absolute;
                right: 0;
                bottom: 0;
                left: 0;
                z-index: 2;
                display: flex;
                justify-content: center;
                padding: 0;
                margin-right: 15%;
                margin-bottom: 1rem;
                margin-left: 15%;
                list-style: none;
            }

            #tiketCarousel .carousel-indicators [data-bs-target] {
                box-sizing: content-box;
                flex: 0 1 auto;
                width: 30px;
                height: 3px;
                padding: 0;
                margin-right: 3px;
                margin-left: 3px;
                text-indent: -999px;
                cursor: pointer;
                background-color: #fff;
                background-clip: padding-box;
                border: 0;
                border-top: 10px solid transparent;
                border-bottom: 10px solid transparent;
                opacity: .5;
                transition: opacity .6s ease;
            }

            #tiketCarousel .carousel-indicators .active {
                opacity: 1;
            }

            #tiketCarousel .d-block {
                display: block;
            }

            #tiketCarousel .w-100 {
                width: 100%;
            }

            #tiketCarousel .text-shadow {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            }

            #tiketCarousel .mb-8 {
                margin-bottom: 2rem;
            }

            #tiketCarousel .rounded-lg {
                border-radius: 0.5rem;
                overflow: hidden;
            }

            #tiketCarousel .visually-hidden {
                position: absolute !important;
                width: 1px !important;
                height: 1px !important;
                padding: 0 !important;
                margin: -1px !important;
                overflow: hidden !important;
                clip: rect(0, 0, 0, 0) !important;
                white-space: nowrap !important;
                border: 0 !important;
            }

            /* Menambahkan padding untuk container */
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
                max-width: 1200px;
                margin: 0 auto;
            }
        </style>

        <div id="carouselExampleIndicators" class="carousel slide mb-8" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner rounded-lg">
                <div class="carousel-item active">
                    <img src="{{ asset('images/carousel/event-banner-1.jpg') }}" class="d-block w-100"
                        alt="Event Banner 1">
                    <div class="carousel-caption">
                        <h3 class="text-shadow">Temukan Event Menarik</h3>
                        <p class="text-shadow">Berbagai event seru menanti Anda</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel/event-banner-2.jpg') }}" class="d-block w-100"
                        alt="Event Banner 2">
                    <div class="carousel-caption">
                        <h3 class="text-shadow">Konser & Festival</h3>
                        <p class="text-shadow">Nikmati pengalaman musik terbaik</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel/event-banner-3.jpg') }}" class="d-block w-100"
                        alt="Event Banner 3">
                    <div class="carousel-caption">
                        <h3 class="text-shadow">Pesan Tiket Sekarang</h3>
                        <p class="text-shadow">Dapatkan tiket event favoritmu</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Bootstrap JS untuk carousel -->
        <script>
            if (typeof bootstrap === 'undefined') {
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
                script.onload = function() {
                    new bootstrap.Carousel(document.querySelector('#carouselExampleIndicators'));
                };
                document.head.appendChild(script);
            } else {
                new bootstrap.Carousel(document.querySelector('#carouselExampleIndicators'));
            }
        </script>
    </div>
</div>
