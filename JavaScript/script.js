window.onload = menu;

function touch() {
    const menus = document.getElementById("menu");
    menus.classList.toggle("show");

    const content = document.getElementById("content");
    content.classList.toggle("hide");

    const breadcrumbs = document.getElementById("breadcrumbs");
    breadcrumbs.classList.toggle("hide");
}

function menu() {
    document.getElementById("hamburgerMenu").addEventListener("click", touch);
}

function removeError(input) {
    const parentNode = input.parentNode;

    if (parentNode.children.length > 2) {
        parentNode.removeChild(parentNode.children[2]);
    }
}

function addError(input, error) {
    removeError(input);

    const parentNode = input.parentNode;
    const span = document.createElement("span");

    span.className = "error";
    span.appendChild(document.createTextNode(error));

    parentNode.appendChild(span);
}

function checkReviewTitle(input) {
    const title = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`!?.,:\s()\-]+$');

    if (title.length === 0) {
        addError(input, 'Non è possibile inserire una recensione con un titolo vuoto');
        return false;
    } else if (title.length < 2) {
        addError(input, 'Non è possibile inserire una recensione con un titolo più corto di 2 caratteri');
        return false;
    } else if (title.length > 64) {
        addError(input, 'Non è possibile inserire una recensione con un titolo più lungo di 64 caratteri');
        return false;
    } else if (!pattern.test(title)) {
        addError(input, 'Il titolo inserito contiene dei caratteri non consentiti, è possibile inserire solamente lettere, possibilmente accentate, numeri, parentesi e segni di punteggiatura');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkReviewContent(input) {
    const content = input.value;

    if (content.length === 0) {
        addError(input, 'Non è possibile inserire una recensione con un contenuto vuoto');
        return false;
    } else if (content.length  < 4) {
        addError(input, 'Non è possibile inserire una recensione con un contenuto più corto di 4 caratteri');
        return false;
    } else if (content.length  > 500) {
        addError(input, 'Non è possibile inserire una recensione con un contenuto più lungo di 500 caratteri');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function artworkFormValidation() {

}

function eventFormValidation() {

}

function reviewFormValidation() {
    const title = document.getElementById("title");
    const content = document.getElementById("content");

    const titleResult = checkReviewTitle(title);
    const contentResult = checkReviewContent(content);

    return titleResult && contentResult;
}

function userFormValidation() {

}

function registrationFormValidation() {

}
