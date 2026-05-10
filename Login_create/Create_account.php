<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Wijaya Cars</title>
    <link rel="stylesheet" href="tema_lc.css">
</head>
<body>

    <div class="split-screen">
        <div class="left-pane">
            <div class="form-container">
                <div style="margin-bottom: 20px;">
                    <span style="font-weight: 800; font-size: 20px; letter-spacing: 2px; color: white;">WIJAYA CARS</span>
                </div>

                <div class="header-text">
                    <h1>Create Account</h1>
                    <p class="subtitle">
                        Already a member? <a href="Login.php" class="link">Log in</a>
                    </p>
                </div>

                <form id="registerForm">
                    
                    <div class="name-row">
                        <div>
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first-name" placeholder="John" required>
                        </div>
                        <div>
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last-name" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="john.doe@example.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" placeholder="+62" pattern="[0-9]+" title="Only numbers allowed" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" id="password" name="password" placeholder="Create a password" required minlength="8">
                        <p class="password-hint">
                            Minimum 8 characters.
                        </p>
                    </div>

                    <button type="submit" class="btn-submit">CREATE ACCOUNT</button>
                </form>

                <div style="margin-top: 30px; text-align: center;">
                    <a href="../Beranda/index.html" style="color: #666; text-decoration: none; font-size: 13px;">← Back to Home</a>
                </div>
            </div>
        </div>

        <div class="right-pane"></div>
    </div>

    <script>
        const registerForm = document.getElementById('registerForm');

        registerForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            // 1. Ambil data dari input
            const firstName = document.getElementById('first-name').value;
            const lastName = document.getElementById('last-name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;

            // 2. Ambil data user lama dari LocalStorage (jika ada)
            let users = JSON.parse(localStorage.getItem('usersData')) || [];

            // 3. Cek apakah email sudah ada
            const exist = users.find(user => user.email === email);
            if(exist) {
                alert("Email sudah terdaftar! Gunakan email lain.");
                return;
            }

            // 4. Simpan user baru
            users.push({ firstName, lastName, email, phone, password });
            localStorage.setItem('usersData', JSON.stringify(users));

            // 5. Redirect ke Login
            alert("Akun berhasil dibuat! Silakan Login.");
            window.location.href = "Login.php";
        });
    </script>

</body>
</html>