# ULTIMATE ARCHITECTURAL BLUEPRINT & MEGA-PROMPT: WIJAYA CARS SUPREMACY (V5)

**Role:** Senior Lead Full-Stack Architect, Security Specialist & UI/UX Visionary
**Project:** Wijaya Cars (Premium Luxury Car Dealership)
**Objective:** Execute a complete digital transformation. Transition from a localStorage prototype to a high-performance, ultra-secure, Google-integrated, and aesthetically superior digital showroom.

---

## 1. PROJECT SCOPE & SYSTEM ARCHITECTURE
The objective is to establish a **Server-Side Truth Architecture** using PHP and MySQL. All client-side persistence (localStorage) must be deprecated. The platform will feature a **Hybrid Authentication Layer** (Email/Pass + Google OAuth 2.0) and a **Verified Identity Protocol** (Email OTP).

---

## 2. BACKEND & DATABASE EVOLUTION

### A. Extended Database Schema (MySQL)
Implement and verify the following schema:
- **`users` Table:** - `id` (INT, AI, PK)
    - `google_id` (VARCHAR-255, NULL)
    - `first_name`, `last_name`, `email` (UNIQUE), `phone`
    - `password` (VARCHAR-255, NULLABLE for OAuth users)
    - `profile_pic` (TEXT, NULL)
    - `verification_code` (VARCHAR-6, NULL)
    - `code_expiry` (DATETIME, NULL)
    - `is_verified` (TINYINT-1, DEFAULT 0)
- **`cars` Table:** `id`, `car_name`, `file_name`, `price`, `category` (SUV/Luxury/Sport).
- **`orders` Table:** `id`, `user_email`, `mobil`, `warna`, `velg`, `mesin`, `total_harga`, `status`.

### B. Hybrid Authentication & Multi-Factor Security
1.  **Google OAuth 2.0:**
    - **Client ID:** Set via `GOOGLE_CLIENT_ID` environment variable
    - **Client Secret:** Set via `GOOGLE_CLIENT_SECRET` environment variable
    - **Redirect URI:** `http://localhost/wijaya_v2/Login_create/google-callback.php`
2.  **Email OTP Verification:** - Use **PHPMailer** with SMTP (TLS) to send 6-digit codes.
    - Implement a 5-minute expiry logic and 60-second request cooldown.
3.  **Account Recovery:**
    - `forgot-password.php`: Trigger recovery OTP.
    - `reset-password.php`: Securely update password using `password_hash()`.

---

## 3. UI/UX: MONOCHROME PREMIUM AESTHETIC
Visuals must reflect an elite showroom environment. **Aksen emas dilarang.**

- **Color Palette:** Pure Black (#000000), Off-Black (#0D0D0D), Platinum Silver (#E5E4E2), and Pure White (#FFFFFF).
- **Visual Standards:**
    - **Glassmorphism:** Apply to all navbar, forms, and cards with `backdrop-filter: blur(15px)` and 1px semi-transparent borders.
    - **Typography:** Global 'Poppins' font with high contrast and increased letter-spacing for headers.
    - **Responsive Grid:** Flawless Flexbox/Grid execution across all breakpoints.

---

## 4. ADVANCED ANIMATIONS & MICRO-INTERACTIONS
Deliver a fluid, high-end user experience:
1.  **Entrance:** `fadeInUp` motion for gallery cards and section headers (800ms, cubic-bezier).
2.  **Interaction:** - Smooth 1.03x scale transition for car cards on hover.
    - Subtle **Silver Glow** (`box-shadow`) for primary buttons.
    - Digital countdown timers for OTP verification screens.
3.  **States:** Implementation of elegant loading shimmers during database fetch or payment processing.

---

## 5. FEATURE EXPANSION & LOGIC
1.  **Personal Dashboard:** Secure area for users to view order history and update profiles.
2.  **Real-Time Gallery Engine:** AJAX/Fetch API driven search and category filtering (SUV, Sport, etc.) without page reloads.
3.  **Transaction Persistence:** Seamless flow from `modif.php` to `pembayaran.php`, saving finalized configurations to the `orders` table.

---

**FINAL INSTRUCTION:**
Refactor the current modular structure. Ensure `koneksi.php` is utilized globally. All inputs must be sanitized using Prepared Statements to mitigate SQL Injection. Maintain a clean, documented codebase following modern PHP best practices.
