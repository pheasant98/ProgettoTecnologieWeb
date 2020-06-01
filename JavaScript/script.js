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

// Funzione per la rimozione di un messaggio di errore per un determinato input
function removeError(input) {
    // Recupero del nodo padre dell'input per rimuovergli il nodo figlio (errore)
    const parentNode = input.parentNode;

    // Se il nodo padre dell'input contiene un numero maggiore di 2 nodi figli, ossia <label> e <input>, allora vuol dire che contiene anche il nodo di errore
    if (parentNode.children.length > 2) {
        // Rimozione dell'ultimo nodo figlio del nodo padre dell'input che risulta essere il nodo di errore in quanto viene sempre aggiunto in coda a quelli presenti
        parentNode.removeChild(parentNode.children[2]);
    }
}

// Funzione per l'aggiunta di un messaggio di errore per un determinato input
function addError(input, error) {
    // Rimozione di un eventuale errore precedente per evitarene la duplicazione
    removeError(input);

    // Recupero del nodo padre dell'input per aggiungergli l'errore come nodo figlio
    const parentNode = input.parentNode;

    // Creazione del nodo contenente il testo d'errore
    const span = document.createElement("span");

    // Impostazione della classe del nodo
    span.className("error");

    // Impostazione del testo del nodo
    // N.B. <var>.innerHTML = ...; non va bene in quanto non viene gestito in modo standard e non gestisce bene l'inserimento di tag HTML annidati
    span.appendChild(document.createTextElement(error));

    // Aggiunta del nodo d'errore al nodo padre dell'input
    parentNode.appendChild(span);
}

// Funzione per il controllo del valore del nome inserito
function checkName(input) {
    // Definizione del pattern con cui controllare l'input inserito
    const pattern = new RegExp('^[a-zA-Z]{3,}$');

    // Controllo dell'input tramite il pattern
    if (pattern.test(input.value)) {
        // Se l'input risulta corretto viene rimosso un eventuale errore precedente e viene ritornato true
        removeError(input);

        return true;
    } else {
        // Se l'input non risulta corretto viene segnalato l'errore e viene ritornato false
        addError(input, "Nome inserito non corretto (almeno 3 caratteri alfabetici)!");

        return false;
    }
}

// Funzione per il controllo del valore del colore inserito
function checkColor() {
    // Definizione del pattern con cui controllare l'input inserito
    const pattern = new RegExp('^[a-zA-Z]{3,}$');

    // Controllo dell'input tramite il pattern
    if (pattern.test(input.value)) {
        // Se l'input risulta corretto viene rimosso un eventuale errore precedente e viene ritornato true
        removeError(input);

        return true;
    } else {
        // Se l'input non risulta corretto viene segnalato l'errore e viene ritornato false
        addError(input, "Colore inserito non corretto (almeno 3 caratteri alfabetici)!");

        return false;
    }
}

// Funzione per il controllo del valore del peso inserito
function checkWeight() {
    // Definizione del pattern con cui controllare l'input inserito
    const pattern = new RegExp('^[1-9][0-9]{0,2}');

    // Controllo dell'input tramite il pattern
    if (pattern.test(input.value)) {
        // Se l'input risulta corretto viene rimosso un eventuale errore precedente e viene ritornato true
        removeError(input);

        return true;
    } else {
        // Se l'input non risulta corretto viene segnalato l'errore e viene ritornato false
        addError(input, "Peso inserito non corretto (valore numerico)!");

        return false;
    }
}

// Funzione per il controllo del valore della descrizione inserito
function checkDescription() {
    // Definizione del pattern con cui controllare l'input inserito
    const pattern = new RegExp('^[^-\s][a-zA-Z0-9_\s-]+$');

    // Controllo dell'input tramite il pattern
    if (pattern.test(input.value)) {
        // Se l'input risulta corretto viene rimosso un eventuale errore precedente e viene ritornato true
        removeError(input);

        return true;
    } else {
        // Se l'input non risulta corretto viene segnalato l'errore e viene ritornato false
        addError(input, "Descrizione inserita non corretta!");

        return false;
    }
}

// Funzione per la validazione del form di inserimento di un personaggio
function formValidation() {
    // Recupero elementi HTML usati per l'inserimento dell'input da parte dell'utente che deve essere controllato
    const name = document.getElementById("nome");
    const color = document.getElementById("colore");
    const weight = document.getElementById("peso");
    const description = document.getElementById("descrizione");

    // N.B. return checkName() && checkColor() && checkWeight() && checkDescription(); Soluzione non ottimale in quanto essendo '&&' un lazy operator e quindi viene visualizzato solo il primo errore!

    // Controllo di tutti i valori di input
    const nameResult = checkName();
    const colorResult = checkColor();
    const weightResult = checkWeight();
    const descriptionResult = checkDescription();

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
