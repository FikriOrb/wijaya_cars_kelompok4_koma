# Wijaya Cars - Premium Automobile Dealership 🏎️✨

**Wijaya Cars** adalah platform *e-commerce* dan *showroom digital* premium yang dirancang khusus untuk memfasilitasi jual beli mobil mewah dan *sport* (Supercar, SUV, Luxury Cars). Dibangun sebagai proyek unggulan oleh **Kelompok 4**, platform ini menghadirkan pengalaman berbelanja interaktif kelas dunia langsung dari layar Anda.

---

## 🌟 Fitur Unggulan & Tata Cara Penggunaan

Berikut adalah panduan lengkap untuk memaksimalkan seluruh fitur canggih yang ada di dalam website Wijaya Cars:

### 1. Sistem Autentikasi Modern (Login & Register)
Platform ini dilengkapi sistem keamanan ganda untuk menjamin privasi dan keamanan transaksi Anda.
- **Daftar Akun (Register):** Pengguna dapat mendaftar menggunakan Email atau Nomor Telepon.
- **Verifikasi OTP:** Setiap akun baru wajib melewati tahap verifikasi melalui kode OTP (One-Time Password) yang dikirimkan via Email.
- **Google OAuth (SSO):** Tersedia opsi *"Continue with Google"* untuk akses masuk yang cepat dan mulus tanpa perlu menghafal kata sandi.

### 2. Galeri Eksklusif & Filter Cerdas (Gallery)
Eksplorasi koleksi mobil mewah kami dengan mudah:
- Navigasi ke halaman **Galeri** untuk melihat seluruh unit kendaraan.
- Gunakan **Kolom Pencarian** (Search) untuk mencari model spesifik.
- Gunakan **Kategori Filter** (Semua, SUV, Sport, Luxury) untuk menyortir mobil sesuai gaya dan kebutuhan Anda.

### 3. Konfigurator Mobil 3D Interaktif (3D Customization)
Ini adalah fitur bintang dari Wijaya Cars. Pengunjung tidak hanya melihat foto, tetapi berinteraksi langsung dengan kendaraan:
- Klik tombol **"Detail Unit"** pada mobil pilihan Anda di Galeri.
- Anda akan diarahkan ke halaman Konfigurasi (Modifikasi).
- **Visualisasi 3D:** Putar dan perbesar model 3D kendaraan (Porsche, Lamborghini, Ferrari, McLaren) untuk melihat detail dari berbagai sudut (menggunakan file `.glb`).
- **Kustomisasi:** Ubah **Warna Eksterior**, ukuran **Velg**, hingga spesifikasi **Mesin**.
- **Kalkulasi Real-time:** Harga estimasi akan otomatis berubah secara instan (*real-time*) sesuai dengan *parts* modifikasi yang Anda pilih.

### 4. Dasbor Profil & Sistem KYC (Know Your Customer)
Wijaya Cars mewajibkan verifikasi identitas (KYC) demi legalitas dan keamanan transaksi bernilai tinggi:
- Masuk ke menu **Dasbor** (Dashboard).
- **Lengkapi Profil:** Isi kelengkapan nama, nomor telepon, dan alamat pengiriman.
- **Integrasi Google Maps:** Klik tombol *"Buka Peta"* untuk menentukan titik koordinat pasti lokasi pengiriman Anda.
- **Upload KTP (Wajib):** Anda harus mengunggah foto Dokumen Identitas (KTP). Jika dokumen belum diunggah, tombol *Checkout/Pembayaran* akan dikunci oleh sistem.

### 5. Checkout & Pembayaran Transparan (Payment)
Setelah puas melakukan modifikasi, Anda bisa langsung melakukan pemesanan:
- Di halaman Konfigurasi, klik **Lanjut ke Pembayaran**.
- Halaman *Checkout* akan menampilkan **Ringkasan Pemesanan** (Harga dasar, biaya kustomisasi warna/velg/mesin, PPN 11%, dan biaya penanganan).
- Terdapat peringatan tegas jika KTP belum diunggah. Jika KTP sudah terverifikasi, klik **BAYAR** untuk memproses pesanan.

### 6. Pelacakan Pesanan Live (Order Tracking Timeline)
Pantau perjalanan mobil impian Anda dari pabrik hingga ke garasi Anda:
- Buka **Dasbor** dan klik **Lihat Status** pada riwayat pesanan Anda.
- Terdapat *Timeline* interaktif dengan 4 tahapan status:
  1. Menunggu Verifikasi (KYC & Mutasi Pembayaran)
  2. Pesanan Diproses (Tahap Perakitan & Kustomisasi)
  3. Sedang Dikirim (Kendaraan dinaikkan ke Towing VIP)
  4. Pesanan Selesai (Serah terima kendaraan dan dokumen)

### 7. Panel Manajemen Admin (Admin Panel)
(Khusus Staf Wijaya Cars)
- Terletak di direktori `/admin_panel`.
- Memungkinkan staf Wijaya Cars untuk *login* dengan kredensial khusus.
- Fitur ini digunakan untuk meninjau pesanan masuk, memverifikasi KTP pelanggan, memvalidasi bukti pembayaran, dan memperbarui status pelacakan (*order tracking*) secara terpusat.

---

## 🛠️ Teknologi yang Digunakan
- **Front-end:** HTML5, CSS3 (Glassmorphism & Dark Mode Premium), Vanilla JavaScript.
- **Model 3D:** Google `<model-viewer>` (Format `.glb` & `.gltf`).
- **Back-end:** PHP 8+ dengan arsitektur prosedural & PDO.
- **Database:** MySQL / MariaDB.
- **Integrasi Pihak Ketiga:** Google Maps API (Location picker), Google OAuth 2.0 (Login), PHPMailer (OTP System).

---

## 👨‍💻 Dikembangkan Oleh
**Kelompok 4**
Proyek ini dibangun sebagai demonstrasi platform *e-commerce* premium fungsional dengan pengalaman visual tingkat tinggi.

*"Driving the Future of Automobile Commerce."* 🇮🇩