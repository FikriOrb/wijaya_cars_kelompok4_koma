<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Wijaya Cars</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Beranda/style.css">
    <link rel="stylesheet" href="style-gallery.css?v=20260514-2">
</head>
<body>
    <?php include __DIR__ . '/../navbar.php'; ?>

    <section class="gallery-header">
        <h1>Exclusive Collection</h1>
        <p class="subtitle">Koleksi mobil mewah pilihan terbaik.</p>
    </section>

    <main class="gallery-container">
        <div class="gallery-tools gallery-tools-premium">
            <div class="search-panel">
                <label class="search-box" for="searchInput">
                    <span>Search Collection</span>
                    <div class="search-field">
                        <span class="search-icon" aria-hidden="true"></span>
                        <input id="searchInput" type="search" placeholder="Cari model, contoh: Porsche">
                        <button type="button" class="clear-search" id="clearSearch" aria-label="Clear search"></button>
                    </div>
                </label>
                <p class="result-count" id="resultCount">Memuat koleksi...</p>
            </div>

            <div class="filter-panel">
                <span class="filter-label">Category</span>
                <div class="filter-tabs" aria-label="Category filters">
                    <button type="button" class="filter active" data-category="">All</button>
                    <button type="button" class="filter" data-category="SUV">SUV</button>
                    <button type="button" class="filter" data-category="Luxury">Luxury</button>
                    <button type="button" class="filter" data-category="Sport">Sport</button>
                </div>
            </div>
        </div>

        <div class="grid loading" id="carGrid">
            <div class="shimmer-card"></div>
            <div class="shimmer-card"></div>
            <div class="shimmer-card"></div>
        </div>
    </main>

    <?php include __DIR__ . '/../footer.php'; ?>

    <script>
        const grid = document.getElementById('carGrid');
        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
        const resultCount = document.getElementById('resultCount');
        const filters = document.querySelectorAll('.filter');
        let activeCategory = '';
        let debounce;

        async function loadCars() {
            grid.classList.add('loading');
            resultCount.textContent = 'Memuat koleksi...';
            grid.innerHTML = '<div class="shimmer-card"></div><div class="shimmer-card"></div><div class="shimmer-card"></div>';

            const params = new URLSearchParams({
                search: searchInput.value.trim(),
                category: activeCategory
            });
            const response = await fetch(`../api/cars.php?${params.toString()}`);
            const payload = await response.json();

            grid.classList.remove('loading');
            if (!payload.cars.length) {
                grid.innerHTML = '<p class="empty-state">Unit tidak ditemukan.</p>';
                resultCount.textContent = '0 unit tersedia';
                return;
            }

            resultCount.textContent = `${payload.cars.length} unit tersedia`;

            grid.innerHTML = payload.cars.map((car, index) => `
                <article class="card2" style="animation-delay:${index * 0.04}s">
                    <div class="card2-image-box">
                        <img src="../models/${escapeAttr(car.file_name)}" alt="${escapeAttr(car.car_name)}" loading="lazy">
                    </div>
                    <div class="card2-info">
                        <span class="category-pill">${escapeHtml(car.category)}</span>
                        <h3 class="car2-name">${escapeHtml(car.car_name)}</h3>
                        <div class="price">${escapeHtml(car.price_label)}</div>
                        <a class="btn-beli" href="../modif/modif.php?car_id=${car.id}">
                            <span>Detail Unit</span>
                            <i aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            `).join('');
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, char => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
            })[char]);
        }

        function escapeAttr(value) {
            return escapeHtml(value).replace(/`/g, '&#096;');
        }

        searchInput.addEventListener('input', () => {
            clearSearch.classList.toggle('is-visible', searchInput.value.trim() !== '');
            clearTimeout(debounce);
            debounce = setTimeout(loadCars, 250);
        });

        clearSearch.addEventListener('click', () => {
            searchInput.value = '';
            clearSearch.classList.remove('is-visible');
            searchInput.focus();
            loadCars();
        });

        filters.forEach(button => {
            button.addEventListener('click', () => {
                filters.forEach(item => item.classList.remove('active'));
                button.classList.add('active');
                activeCategory = button.dataset.category;
                loadCars();
            });
        });

        loadCars();
    </script>
</body>
</html>
