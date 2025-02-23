// script.js
const passwordInput = document.getElementById("password");

const togglePasswordIcon = document.getElementById("togglePassword");

// Show icons when typing
passwordInput.addEventListener("input", () => {
    if (passwordInput.value.trim() !== "") {
        lockIcon.style.display = "block"; // Show lock icon
        togglePasswordIcon.style.display = "block"; // Show eye icon
    } else {
        lockIcon.style.display = "none"; // Hide lock icon
        togglePasswordIcon.style.display = "none"; // Hide eye icon
    }
});

// Toggle password visibility
togglePasswordIcon.addEventListener("click", () => {
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePasswordIcon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        passwordInput.type = "password";
        togglePasswordIcon.classList.replace("fa-eye-slash", "fa-eye");
    }
});
