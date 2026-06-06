<?php require_once __DIR__ . '/../auth.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wijaya Cars</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <?php include __DIR__ . '/../navbar.php'; ?>
 
    <section class="hero scroll-fade">
        <div class="hero-content">
            <h1 class="title">WIJAYA<br>CARS</h1>
 
            <p class="desc">
                Discover the ultimate definition of luxury and speed. 
                We bring you a curated collection of the world's finest automobiles, 
                designed for those who demand excellence in every journey.
            </p>
            <a class="discover-btn" href="../Gallery/gallery.php">DISCOVER</a>
        </div>
        <div class="hero-video-container">
            <video class="hero-video" autoplay muted loop poster="../models/BMW M850i.jpg">
                <source src="https://files.catbox.moe/a1cgp7.mp4" type="video/mp4">
            </video>
        </div>
    </section>
 
    <section class="slide-two">
 
        <section class="specials scroll-fade">
            <div class="top-bar">
                <h2>TODAY'S SPECIALS</h2>
 
                <div class="filters">
                    <button class="filter-btn active" data-category="All">All</button>
                    <button class="filter-btn" data-category="SUV">SUV</button>
                    <button class="filter-btn" data-category="Sport">Sport</button>
                    <button class="filter-btn" data-category="Luxury">Luxury</button>
                    <a href="../Gallery/gallery.php" class="view-all">View All Cars</a>
                </div>
            </div>
 
            <div class="carousel-wrapper">
                <div class="carousel-track" id="carouselTrack">
                    <div class="shimmer-card"></div>
                    <div class="shimmer-card"></div>
                    <div class="shimmer-card"></div>
                    <div class="shimmer-card"></div>
                    <div class="shimmer-card"></div>
                </div>
            </div>
        </section>

        <!-- STATS SECTION -->
        <section class="stats-section scroll-fade">
            <div class="stats-container">
                <div class="stats-item">
                    <h2 class="stats-number">22+</h2>
                    <p class="stats-label">Koleksi Mobil</p>
                </div>
                <div class="stats-item">
                    <h2 class="stats-number">1.200+</h2>
                    <p class="stats-label">Pelanggan Puas</p>
                </div>
                <div class="stats-item">
                    <h2 class="stats-number">10</h2>
                    <p class="stats-label">Tahun Pengalaman</p>
                </div>
                <div class="stats-item">
                    <h2 class="stats-number">Garansi</h2>
                    <p class="stats-label">Resmi</p>
                </div>
            </div>
        </section>

        <section class="banner scroll-fade">
            <div class="banner-left"></div>

            <div class="banner-right">
                <h1>WIJAYA<br>CARS</h1>
                <a href="#" class="follow-btn">Follow us</a>
            </div>
        </section>

        <section class="slide-three scroll-fade">

          <!-- KIRI -->
          <div class="st-left">
            <div class="st-tag">Mengapa Wijaya Cars</div>
            <h1 class="st-heading">
              DRIVEN<br>BY
              <span class="st-heading-outline">EXCELLENCE</span>
            </h1>
            <p class="st-desc">
              Koleksi kendaraan premium pilihan dengan standar kualitas
              tertinggi. Performa, kemewahan, dan keandalan dalam satu tempat.
            </p>
            <div class="st-pills">
              <div class="st-pill">
                <div class="st-pill-icon st-gold">🏆</div>
                <div>
                  <div class="st-pill-title">Garansi Resmi</div>
                  <div class="st-pill-sub">2 tahun atau 50.000 km</div>
                </div>
              </div>
              <div class="st-pill">
                <div class="st-pill-icon st-blue">🚗</div>
                <div>
                  <div class="st-pill-title">Free Test Drive</div>
                  <div class="st-pill-sub">Booking langsung, tanpa biaya</div>
                </div>
              </div>
              <div class="st-pill">
                <div class="st-pill-icon st-green">⚡</div>
                <div>
                  <div class="st-pill-title">Inden & Delivery</div>
                  <div class="st-pill-sub">Pengiriman ke seluruh Indonesia</div>
                </div>
              </div>
            </div>
            <div class="st-cta-row">
              <a href="../Gallery/gallery.php" class="st-btn-primary">Explore Collection</a>
              <a href="../modif/modif.php" class="st-btn-ghost">Book Test Drive</a>
            </div>
          </div>

          <!-- KANAN: Slideshow -->
          <div class="st-right">
            <div class="st-slideshow" id="stSlideshow">

              <div class="st-overlay"></div>

              <!-- Ticker -->
              <div class="st-ticker">
                <div class="st-ticker-inner">
                  <span>Wijaya Cars</span><span>•</span>
                  <span>Luxury Collection</span><span>•</span>
                  <span>Premium Drive</span><span>•</span>
                  <span>Est. Medan</span><span>•</span>
                  <span>Wijaya Cars</span><span>•</span>
                  <span>Luxury Collection</span><span>•</span>
                  <span>Premium Drive</span><span>•</span>
                  <span>Est. Medan</span><span>•</span>
                </div>
              </div>

              <!-- Slides diisi JS -->
              <div id="stSlides"></div>

              <!-- Info overlay -->
              <div class="st-slide-info">
                <div class="st-slide-name" id="stSlideName"></div>
                <div class="st-slide-price" id="stSlidePrice"></div>
                <span class="st-slide-cat" id="stSlideCat"></span>
              </div>

              <!-- Arrow navigasi (muncul saat hover) -->
              <div class="st-nav-hint st-nav-left">
                <div class="st-nav-arrow" id="stPrev">&#8592;</div>
              </div>
              <div class="st-nav-hint st-nav-right">
                <div class="st-nav-arrow" id="stNext">&#8594;</div>
              </div>

              <!-- Dots -->
              <div class="st-dots" id="stDots"></div>

              <!-- Swipe hint -->
              <div class="st-swipe-hint" id="stSwipeHint">&#8592; geser &#8594;</div>

            </div>
          </div>

        </section>

        <section class="bottom-section scroll-fade">

            <div class="bottom-left">
                <img src="../models/Logo.png" class="footer-logo">

                <p>Medan - Indonesia</p>
                <p>+62822-7456-7521</p>
                <p>madyashafwan@gmail.com</p>

                <div class="social-icons">
                    <i class="fab fa-whatsapp"></i>
                    <i class="fab fa-facebook"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-twitter"></i>
                </div>
            </div>

            <div class="bottom-right">
                <form class="contact-form" action="../contact_us/contact.php" method="POST">
                    <div class="row-2">
                        <input type="text" name="first_name" placeholder="first">
                        <input type="text" name="last_name" placeholder="last">
                    </div>
                    <div class="row-2">
                        <input type="email" name="email" placeholder="email">
                        <input type="text" name="phone" placeholder="phone number">
                    </div>
                    <div class="row-1">
                        <input type="text" name="address" placeholder="address">
                        <button type="submit">Send</button>
                    </div>
                </form>
            </div>

        </section>

    <?php include __DIR__ . '/../footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Intersection Observer for scroll animation
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.scroll-fade').forEach(el => {
                observer.observe(el);
            });

            // Fetch and Render Carousel Data
            const track = document.getElementById('carouselTrack');

            function escapeHtml(value) {
                return String(value).replace(/[&<>"']/g, char => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
                })[char]);
            }

            function fetchCars(category) {
                // Remove animation classes and show shimmer loading
                track.classList.remove('animate-30s', 'animate-20s');
                track.innerHTML = `
                    <div class="shimmer-card"></div>
                    <div class="shimmer-card"></div>
                    <div class="shimmer-card"></div>
                    <div class="shimmer-card"></div>
                    <div class="shimmer-card"></div>
                `;
 
                const url = category === 'All' ? '../api/cars.php' : `../api/cars.php?category=${encodeURIComponent(category)}`;
 
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.cars && data.cars.length > 0) {
                            const cardsHtml = data.cars.map(car => {
                                const badgeClass = car.category.toLowerCase();
                                return `
                                    <a href="../Gallery/gallery.php" class="card">
                                        <img src="../models/${escapeHtml(car.file_name)}" alt="${escapeHtml(car.car_name)}">
                                        <span class="badge ${badgeClass}">${escapeHtml(car.category)}</span>
                                        <h3 class="car-name">${escapeHtml(car.car_name)}</h3>
                                        <div class="price">${escapeHtml(car.price_label)}</div>
                                    </a>
                                `;
                            }).join('');
 
                            // Repeat track content if number of cars is small (1 or 2) to prevent gaps
                            const repeatCount = data.cars.length <= 2 ? 4 : 1;
                            let baseHtml = '';
                            for (let i = 0; i < repeatCount; i++) {
                                baseHtml += cardsHtml;
                            }
 
                            // Duplicate for infinite seamless scroll
                            track.innerHTML = baseHtml + baseHtml;
 
                            // Force reflow to reset CSS animation
                            track.offsetHeight;
 
                            // Add appropriate animation duration class
                            const animClass = data.cars.length >= 6 ? 'animate-30s' : 'animate-20s';
                            track.classList.add(animClass);
                        } else {
                            track.innerHTML = '<div class="empty-state">Tidak ada mobil dalam kategori ini.</div>';
                            track.classList.remove('animate-30s', 'animate-20s');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching cars:', error);
                        track.innerHTML = '<div class="empty-state" style="color: #ff6b6b;">Gagal memuat data mobil. Silakan coba lagi.</div>';
                        track.classList.remove('animate-30s', 'animate-20s');
                    });
            }
 
            // Setup Filter Button Click Listeners
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    if (button.classList.contains('active')) return;
 
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
 
                    const category = button.getAttribute('data-category');
                    fetchCars(category);
                });
            });
 
            // Initial fetch on page load
            fetchCars('All');

            // Slide Three Redesign Slideshow Logic
            const ST_SLIDES = [
              {
                img: '../models/porche.jpg',
                name: 'Porsche 911',
                price: 'Rp 2.200.000.000',
                cat: 'Sport',
                catClass: 'st-cat-sport'
              },
              {
                img: '../models/Lamborghini Gallador.jpg',
                name: 'Lamborghini Gallador',
                price: 'Rp 5.800.000.000',
                cat: 'Luxury',
                catClass: 'st-cat-luxury'
              },
              {
                img: '../models/Toyota Alphard 2.5 GAT.jpg',
                name: 'Toyota Alphard 2.5 GAT',
                price: 'Rp 1.100.000.000',
                cat: 'Luxury',
                catClass: 'st-cat-luxury'
              },
              {
                img: '../models/BMW M850i.jpg',
                name: 'BMW M850i',
                price: 'Rp 3.200.000.000',
                cat: 'Luxury',
                catClass: 'st-cat-luxury'
              },
              {
                img: '../models/Mobil_Sport.jpg',
                name: 'Sport Collection',
                price: 'Mulai Rp 1.500.000.000',
                cat: 'Sport',
                catClass: 'st-cat-sport'
              }
            ];

            let cur = 0;
            const slideshowEl = document.getElementById('stSlideshow');
            const slidesContainer = document.getElementById('stSlides');
            const dotsEl = document.getElementById('stDots');

            function goTo(index) {
              const slides = slidesContainer.querySelectorAll('.st-slide');
              const dots = dotsEl.querySelectorAll('.st-dot');
              if (!slides.length || !dots.length) return;

              slides[cur].classList.remove('active');
              dots[cur].classList.remove('active');

              cur = (index % ST_SLIDES.length + ST_SLIDES.length) % ST_SLIDES.length;

              slides[cur].classList.add('active');
              dots[cur].classList.add('active');

              document.getElementById('stSlideName').textContent = ST_SLIDES[cur].name;
              document.getElementById('stSlidePrice').textContent = ST_SLIDES[cur].price;

              const catEl = document.getElementById('stSlideCat');
              catEl.textContent = ST_SLIDES[cur].cat;
              catEl.className = 'st-slide-cat ' + ST_SLIDES[cur].catClass;
            }

            function renderSlideshow() {
              // Render semua gambar
              slidesContainer.innerHTML = ST_SLIDES.map((slide, i) =>
                `<img src="${slide.img}" class="st-slide ${i === 0 ? 'active' : ''}" 
                 alt="${escapeHtml(slide.name)}" loading="lazy">`
              ).join('');

              // Render dots
              dotsEl.innerHTML = ST_SLIDES.map((_, i) =>
                `<div class="st-dot ${i === 0 ? 'active' : ''}" data-index="${i}"></div>`
              ).join('');

              // Render info slide pertama
              document.getElementById('stSlideName').textContent = ST_SLIDES[0].name;
              document.getElementById('stSlidePrice').textContent = ST_SLIDES[0].price;
              const catEl = document.getElementById('stSlideCat');
              catEl.textContent = ST_SLIDES[0].cat;
              catEl.className = 'st-slide-cat ' + ST_SLIDES[0].catClass;

              // Click listeners to dots
              const dotEls = dotsEl.querySelectorAll('.st-dot');
              dotEls.forEach(dot => {
                dot.addEventListener('click', () => {
                  const idx = parseInt(dot.getAttribute('data-index'), 10);
                  goTo(idx);
                  resetTimer();
                });
              });
            }

            // Auto-play timer
            let stTimer = setInterval(() => {
              goTo(cur + 1);
            }, 3200);

            function resetTimer() {
              clearInterval(stTimer);
              stTimer = setInterval(() => {
                goTo(cur + 1);
              }, 3200);
            }

            // Arrow controls
            document.getElementById('stPrev').addEventListener('click', (e) => {
              e.stopPropagation();
              goTo(cur - 1);
              resetTimer();
            });

            document.getElementById('stNext').addEventListener('click', (e) => {
              e.stopPropagation();
              goTo(cur + 1);
              resetTimer();
            });

            // Drag/swipe mouse
            let dragStartX = 0;
            let isDragging = false;
            let hasDragged = false;

            slideshowEl.addEventListener('mousedown', (e) => {
              if (e.button !== 0) return; // Left click only
              dragStartX = e.clientX;
              isDragging = true;
              hasDragged = false;
              slideshowEl.classList.add('st-dragging');
            });

            window.addEventListener('mousemove', (e) => {
              if (!isDragging) return;
              const diff = e.clientX - dragStartX;
              if (Math.abs(diff) > 8) {
                hasDragged = true;
              }
            });

            window.addEventListener('mouseup', (e) => {
              if (!isDragging) return;
              isDragging = false;
              slideshowEl.classList.remove('st-dragging');
              const diff = e.clientX - dragStartX;
              if (Math.abs(diff) > 50) {
                if (diff > 0) {
                  goTo(cur - 1); // Swipe right, show prev
                } else {
                  goTo(cur + 1); // Swipe left, show next
                }
                resetTimer();
              }
            });

            // Touch swipe
            slideshowEl.addEventListener('touchstart', (e) => {
              dragStartX = e.touches[0].clientX;
            });

            slideshowEl.addEventListener('touchend', (e) => {
              if (e.changedTouches.length === 0) return;
              const diff = e.changedTouches[0].clientX - dragStartX;
              if (Math.abs(diff) > 50) {
                if (diff > 0) {
                  goTo(cur - 1);
                } else {
                  goTo(cur + 1);
                }
                resetTimer();
              }
            });

            // Swipe hint timeout
            setTimeout(() => {
              const swipeHint = document.getElementById('stSwipeHint');
              if (swipeHint) swipeHint.classList.add('st-swipe-hidden');
            }, 2800);

            // Render
            renderSlideshow();
        });
    </script>
</body>
</html>
