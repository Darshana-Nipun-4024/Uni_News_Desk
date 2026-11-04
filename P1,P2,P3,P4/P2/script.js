const loginForm = document.getElementById("loginForm");
const formError = document.getElementById("formError");

loginForm.addEventListener("submit", function (event) {
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    formError.textContent = "";

    if (!email || !password) {
        event.preventDefault();
        formError.textContent = "Please enter your university email and password.";
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        event.preventDefault();
        formError.textContent = "Please enter a valid email address.";
    }
});
