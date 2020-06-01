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
    // Recupero del nodo padre dell'input per rimuovergli il nodo figlio (errore)
    const parentNode = input.parentNode;

    // Se il nodo padre dell'input contiene un numero maggiore di 2 nodi figli, ossia <label> e <input>, allora vuol dire che contiene anche il nodo di errore
    if (parentNode.children.length > 2) {
        // Rimozione dell'ultimo nodo figlio del nodo padre dell'input che risulta essere il nodo di errore in quanto viene sempre aggiunto in coda a quelli presenti
        parentNode.removeChild(parentNode.children[2]);
    }
}

function addError(input, error) {
    removeError(input);

    // Recupero del nodo padre dell'input per aggiungergli l'errore come nodo figlio
    const parentNode = input.parentNode;

    // Creazione del nodo contenente il testo d'errore
    const span = document.createElement("span");

    // Impostazione della classe del nodo
    span.className = "error";

    // Impostazione del testo del nodo
    span.appendChild(document.createTextNode(error));

    // Aggiunta del nodo d'errore al nodo padre dell'input
    parentNode.appendChild(span);
}

function checkName(input) {
    const pattern = new RegExp('^[a-zA-Z]{3,}$');

    if (pattern.test(input.value)) {
        removeError(input);
        return true;
    } else {
        addError(input, "Nome inserito non corretto (almeno 3 caratteri alfabetici)!");
        return false;
    }
}

function checkColor(input) {
    const pattern = new RegExp('^[a-zA-Z]{3,}$');

    if (pattern.test(input.value)) {
        removeError(input);
        return true;
    } else {
        addError(input, "Colore inserito non corretto (almeno 3 caratteri alfabetici)!");
        return false;
    }
}

function checkWeight(input) {
    const pattern = new RegExp('^[1-9][0-9]{0,2}');

    if (pattern.test(input.value)) {
        removeError(input);
        return true;
    } else {
        addError(input, "Peso inserito non corretto (valore numerico)!");
        return false;
    }
}

function checkDescription(input) {
    const pattern = new RegExp('^[^-\s][a-zA-Z0-9_\s-]+$');

    if (pattern.test(input.value)) {
        removeError(input);
        return true;
    } else {
        addError(input, "Descrizione inserita non corretta!");
        return false;
    }
}

function formValidation() {
    // Recupero elementi HTML usati per l'inserimento dell'input da parte dell'utente che deve essere controllato
    const name = document.getElementById("nome");
    const color = document.getElementById("colore");
    const weight = document.getElementById("peso");
    const description = document.getElementById("descrizione");

    // Controllo di tutti i valori di input
    const nameResult = checkName(name);
    const colorResult = checkColor(color);
    const weightResult = checkWeight(weight);
    const descriptionResult = checkDescription(description);

    // Restituzione del risultato dei controlli
    return nameResult && colorResult && weightResult && descriptionResult;
}

function artworkFormValidation() {

}

function eventFormValidation() {

}

function reviewFormValidation() {

}

function userFormValidation() {

}

function registrationFormValidation() {

}
