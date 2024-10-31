function togglePassword() {
    var loginPasswordField = document.querySelector('input[name="password"]');
    var registerPasswordField = document.querySelector('input[name="reg_password"]');
    var checkbox = document.querySelector('input[type="checkbox"]');

    if (checkbox.checked) {
        if (loginPasswordField) loginPasswordField.type = "text";
        if (registerPasswordField) registerPasswordField.type = "text";
    } else {
        if (loginPasswordField) loginPasswordField.type = "password";
        if (registerPasswordField) registerPasswordField.type = "password";
    }
}