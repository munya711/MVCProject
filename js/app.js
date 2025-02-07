
document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const submitButton = document.querySelector("input[type='submit']");
    
   
    const messageContainer = document.createElement("div");
    messageContainer.style.color = "red";
    passwordInput.parentNode.appendChild(messageContainer);

    function validatePassword() {
        const password = passwordInput.value;
        let errors = [];

        if (password.length < 8) {
            errors.push("At least 8 characters.");
        }
        if (!/[A-Z]/.test(password)) {
            errors.push("At least one uppercase letter.");
        }
        if (!/[a-z]/.test(password)) {
            errors.push("At least one lowercase letter.");
        }
        if (!/[0-9]/.test(password)) {
            errors.push("At least one number.");
        }
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            errors.push("At least one special character.");
        }

        if (errors.length === 0) {
            submitButton.disabled = false;
            submitButton.style.backgroundColor = "#ff4500";
            submitButton.style.color = "#fff";
            messageContainer.innerHTML = "<span style='color:green'>Password is strong!</span>";
        } else {
            submitButton.disabled = true;
            submitButton.style.backgroundColor = "#f8f8f8";
            submitButton.style.color = "#000";
            messageContainer.innerHTML = "Password must have: <br>" + errors.join("<br>");
        }
    }

    passwordInput.addEventListener("input", validatePassword);
    validatePassword(); 
});

