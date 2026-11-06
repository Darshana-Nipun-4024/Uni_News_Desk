// Simple check before the form is sent to PHP
function checkLogin() {
  var email = document.getElementById("email").value.trim();
  var password = document.getElementById("password").value;
  var msg = document.getElementById("jsError");

  if (email === "" || password === "") {
    msg.textContent = "Please fill in both fields.";
    return false;
  }
  if (email.indexOf("@") === -1 || email.indexOf("nsbm.ac.lk") === -1) {
    msg.textContent = "Use your university email (ends with nsbm.ac.lk).";
    return false;
  }
  if (password.length < 6) {
    msg.textContent = "Password must be at least 6 characters.";
    return false;
  }
  msg.textContent = "";
  return true;
}

// Show an error that PHP sent back in the address (login.html?error=wrong)
var error = new URLSearchParams(window.location.search).get("error");
if (error === "wrong") {
  document.getElementById("jsError").textContent = "Wrong email or password.";
} else if (error === "empty") {
  document.getElementById("jsError").textContent = "Please enter your email and password.";
}
