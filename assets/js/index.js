var floatingMessageSuccess = document.getElementById("float-message-success");
var floatingMessageSuccessCloseButton = document.getElementById("float-message-success-close");

floatingMessageSuccessCloseButton.addEventListener('click', function() {
    floatingMessageSuccess.style.display = "none";
});

var floatingMessageError = document.getElementById("float-message-error");
var floatingMessageErrorCloseButton = document.getElementById("float-message-error-close");

floatingMessageErrorCloseButton.addEventListener('click', function() {
    floatingMessageError.style.display = "none";
});


var header = document.getElementById("header");
var scrollBody = document.getElementById("scroll-body");

scrollBody.style.marginTop = header.offsetHeight + 'px';