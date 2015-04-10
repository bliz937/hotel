function logout() {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "register.php");

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "rl");
    hiddenField.setAttribute("value", "o");
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}