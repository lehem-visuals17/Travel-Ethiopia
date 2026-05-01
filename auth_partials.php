<!-- auth_partials.php -->
<div id="auth-modal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close-btn">&times;</span>

    <!-- LOGIN FORM -->
    <form id="login-form" action="login.php" method="POST">
      <h2>Sign In</h2>
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Login</button>
      <p>New here? <a href="#" id="go-to-signup">Create an account</a></p>
    </form>

    <!-- SIGN UP FORM -->
    <form id="signup-form" action="login.php" method="POST" style="display: none;">
      <h2>Sign Up</h2>
      <input type="text" name="fullname" placeholder="Full Name" required>
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="tel" name="phone" placeholder="Phone Number" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" id="signup-btn" class="btn">Register</button>
      <p>Already have an account? <a href="#" id="go-to-login">Sign In</a></p>
    </form>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("auth-modal");
    const loginPills = document.querySelectorAll(".login-pill");
    const closeBtn = document.querySelector(".close-btn");
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");

    if (!modal) return; // Exit if modal isn't on this page

    // 1. Open Modal (Works for all buttons with .login-pill)
    loginPills.forEach(btn => {
        btn.onclick = () => modal.style.display = "block";
    });

    // 2. Close Modal
    if (closeBtn) closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; };

    // 3. Toggle Logic
    document.getElementById("go-to-signup").onclick = (e) => {
        e.preventDefault();
        loginForm.style.display = "none";
        signupForm.style.display = "block";
    };

    document.getElementById("go-to-login").onclick = (e) => {
        e.preventDefault();
        signupForm.style.display = "none";
        loginForm.style.display = "block";
    };

    // 4. Signup Validation
    signupForm.onsubmit = (e) => {
        const phone = signupForm.querySelector('input[name="phone"]').value;
        const pass = signupForm.querySelector('input[name="password"]').value;
        const confirm = signupForm.querySelector('input[name="confirm_password"]').value;

        if (!/^(09|07)\d{8}$/.test(phone)) {
            e.preventDefault();
            alert("Phone must start with 09 or 07.");
        } else if (pass !== confirm) {
            e.preventDefault();
            alert("Passwords do not match!");
        }
    };
});

</script>
