/* GESTIONE DEL MENU AD HAMBURGER */
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

/* GESTIONE DELLA TIPOLOGIA DELLE OPERE */

/* GESTIONE DELL'AGGIUNTA E DELLA RIMOZIONE DEGLI ERRORI DAI FORM */
function removeError(input, tags = 2) {
    const parentNode = input.parentNode;

    if (parentNode.children.length > tags) {
        parentNode.removeChild(parentNode.children[tags]);
    }
}

function addError(input, error, tags = 2) {
    removeError(input, tags);

    const parentNode = input.parentNode;
    const span = document.createElement("span");

    span.className = "error";
    span.appendChild(document.createTextNode(error));

    parentNode.appendChild(span);
}

/* CONTROLLI E GESTIONE DELLE DATE */
function addMonths(date, months) {
    const oldDate = date.getDate();

    date.setMonth(date.getMonth() + +months);
    if (date.getDate() !== oldDate) {
        date.setDate(0);
    }

    return date;
}

function daysInMonth(month, year) {
    switch (month) {
        case 2:
            return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0 ? 29 : 28;
        case 4: case 6: case 9: case 11:
            return 30;
        default:
            return 31;
    }
}

function isDateValid(day, month, year) {
    return month >= 0 && month < 12 && day > 0 && day <= daysInMonth(month, year);
}

function getDateFromString(date) {
    const dateArray = date.split('-');
    return new Date(parseInt(dateArray[0]), parseInt(dateArray[1]), parseInt(dateArray[2]));
}

function checkDateFormat(date) {
    const pattern = new RegExp('^\d{2}-\d{2}-\d{4}$');
    return pattern.test(date);
}

function checkDateValidity(date) {
    const dateArray = date.split('-');
    return isDateValid(parseInt(dateArray[0]), parseInt(dateArray[1]), parseInt(dateArray[2]));
}

function checkDate(input) {
    const date = input.value;

    if (date.length === 0) {
        addError(input, 'Non è possibile inserire una data vuota');
        return false;
    } else if (!checkDateFormat(date)) {
        addError(input, 'Non è possibile inserire una data espressa in un formato diverso da "gg-mm-aaaa"');
        return false;
    } else if (!checkDateValidity(date)) {
        addError(input, 'La data di inizio evento inserita non è valida');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

/* CONTROLLI E GESTIONE DELLE OPERE */
function checkArtworkAuthor(input) {
    const author = input.value;
    const pattern = new RegExp('^[A-zÀ-ú\'\-`.\s]+$');

    if (author.length === 0) {
        addError(input, 'Non è possibile inserire un autore vuoto');
        return false;
    } else if (author.length < 5) {
        addError(input, 'Non è possibile inserire un autore più corto di 5 caratteri');
        return false;
    } else if (author.length > 64) {
        addError(input, 'Non è possibile inserire un autore più lungo di 64 caratteri');
        return false;
    } else if (!pattern.test(author)) {
        addError(input, 'L\'autore contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, spazi e i seguenti caratteri speciali \' - . `');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkArtworkTitle(input) {
    const title = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,:(\-)\s]+$');

    if (title.length === 0) {
        addError(input, 'Non è possibile inserire un titolo vuoto');
        return false;
    } else if (title.length < 2) {
        addError(input, 'Non è possibile inserire un titolo più corto di 2 caratteri');
        return false;
    } else if (title.length > 64) {
        addError(input, 'Non è possibile inserire un titolo più lungo di 64 caratteri');
        return false;
    } else if (!pattern.test(title)) {
        addError(input, 'Il titolo contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , : - ()');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkArtworkDescription(input) {
    const description = input.value;

    if (description.length === 0) {
        addError(input, 'Non è possibile inserire una descrizione vuota');
        return false;
    } else if (description.length < 2) {
        addError(input, 'Non è possibile inserire una descrizione più corta di 2 caratteri');
        return false;
    } else if (description.length > 3000) {
        addError(input, 'Non è possibile inserire una descrizione più lunga di 3000 caratteri');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkArtworkDate(input) {
    const years = input.value;
    const pattern = new RegExp('^\d{2}$');

    if (years.length === 0) {
        addError(input, 'Non è possibile inserire una datazione vuota');
        return false;
    } else if (!parseInt(years)) {
        addError(input, 'La datazione dell\'opera deve essere un numero intero');
        return false;
    } else if (!pattern.test(years)) {
        addError(input, 'La datazione deve contenere solo l\'anno');
        return false;
    } else if (parseInt(years) < 1400 || parseInt(years) > parseInt((new Date()).getFullYear().toString())) {
        addError(input, 'La datazione dell\'opera deve essere compresa tra il 1400 e l\'anno corrente');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkArtworkStyle(input) {
    const style = input.options[input.selectedIndex].value;

    if (style !== 'Scultura' && style !== 'Dipinto') {
        addError(input, 'Lo stile dell\'opera deve essere Scultura o Dipinto');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkArtworkTechnique(input) {
    const technique = input.value;
    const pattern = new RegExp('^[A-zÀ-ú\'\-`\s]+$');

    if (technique.length === 0) {
        addError(input, 'Non è possibile inserire una tecnica vuota');
        return false;
    } else if (technique.length < 4) {
        addError(input, 'Non è possibile inserire una tecnica più corta di 4 caratteri');
        return false;
    } else if (technique.length > 64) {
        addError(input, 'Non è possibile inserire una tecnica più lunga di 64 caratteri');
        return false;
    } else if (!pattern.test(technique)) {
        addError(input, 'La tecnica contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, accenti, spazi e i seguenti caratteri speciali \' \ - `');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkArtworkMaterial(input) {
    const material = input.value;
    const pattern = new RegExp('^[A-zÀ-ú\'\-`\s]+$');

    if (material.length === 0) {
        addError(input, 'Non è possibile inserire un materiale vuoto');
        return false;
    } else if (material.length < 4) {
        addError(input, 'Non è possibile inserire un materiale più corto di 4 caratteri');
        return false;
    } else if (material.length > 32) {
        addError(input, 'Non è possibile inserire un materiale più lungo di 32 caratteri');
        return false;
    } else if (!pattern.test(material)) {
        addError(input, 'Il materiale contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, spazi, \' \ - `');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkArtworkDimensions(input) {
    const dimensions = input.value;
    const pattern = new RegExp('^([1-9][0-9]{0,2}|1000)x([1-9][0-9]{0,2}|1000)$');

    if (dimensions.length === 0) {
        addError(input, 'Non è possibile inserire una dimensione vuota', 3);
        return false;
    } else if (!pattern.test(dimensions)) {
        addError(input, 'La dimensione contiene caratteri non consentiti. Il formato consentito è altezzaxlarghezza', 3);
        return false;
    } else {
        removeError(input, 3);
        return true;
    }
}

function checkArtworkLoan(inputYes, inputNo) {
    const yes = inputYes.checked;
    const no = inputNo.checked;

    if (!yes && !no) {
        addError(inputNo, 'Il prestito deve essere scelto tra "Si" e "No"');
        return false;
    } else {
        removeError(inputNo);
        return true;
    }
}

function checkArtworkImage(input) {
    if (input.files.length === 0) {
        addError(input, 'È necessario selezionare un\'immagine');
        return false;
    } else if (input.files.length > 1) {
        addError(input, 'È necessario selezionare una ed una sola immagine');
        return false;
    } else if (input.files[0].size > 512000) {
        addError(input, 'L\'immagine caricata è una dimensione troppo elevata. La dimensione massima accettata è 500<abbr title="Kilo Bytes" xml:lang="en">KB</abbr>');
        return false;
    } else if (input.files[0].type !== 'image/jpeg' && input.files[0].type !== 'image/png') {
        addError(input, 'L\'estensione dell\'immagine non è supportata. L\'estensioni consentite sono .<abbr title="Joint Photographic Experts Group" xml:lang="en">jpeg</abbr>, .<abbr title="Joint Photographic Group" xml:lang="en">jpg</abbr>, .<abbr title="Portable Network Graphics" xml:lang="en">png</abbr>');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

/* CONTROLLI E GESTIONE DEGLI EVENTI */
function checkEventTitle(input) {
    const title = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,\-:()\s]+$');
    
    if (title.length === 0) {
        addError(input, 'Non è possibile inserire un titolo vuoto');
        return false;
    } else if (title.length < 2) {
        addError(input, 'Non è possibile inserire un titolo più corto di 2 caratteri');
        return false;
    } else if (title.length > 64) {
        addError(input, 'Non è possibile inserire un titolo più lungo di 64 caratteri');
        return false;
    } else if (!pattern.test(title)) {
        addError(input, 'Il titolo contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , - : ()');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkEventDescription(input) {
    const description = input.value;
    
    if (description.length === 0) {
        addError(input, 'Non è possibile inserire una descrizione vuota');
        return false;
    } else if (description.length < 30) {
        addError(input, 'Non è possibile inserire una descrizione più corta di 30 caratteri');
        return false;
    } else if (description.length > 65535) {
        addError(input, 'Non è possibile inserire una descrizione più lunga di 65535 caratteri');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkDateComparison(beginDateInput, endDateInput) {
    const beginDate = getDateFromString(beginDateInput.value);
    const endDate = getDateFromString(endDateInput.value);

    const lowerBound = new Date();
    lowerBound.setHours(0,0,0,0);

    const upperBound = new Date(lowerBound.getFullYear() + 3, lowerBound.getMonth(), lowerBound.getDay());
    const durationBound = addMonths(beginDate, 6);

    if (beginDate < lowerBound) {
        addError(endDateInput, 'Non è possibile inserire una data di inizio evento precedente alla data odierna');
        return false;
    } else if (beginDate > upperBound) {
        addError(endDateInput, 'Non è possibile inserire una data di inizio evento successiva a tre anni dalla data odierna');
        return false;
    } else if (beginDate > endDate) {
        addError(endDateInput, 'Non è possibile inserire una data di inizio evento successiva alla data di fine evento');
        return false;
    } else if (endDate > durationBound) {
        addError(endDateInput, 'Non è possibile inserire un evento che abbia una durata superiore ai sei mesi');
        return false;
    } else {
        removeError(endDateInput);
        return true;
    }
}

function checkEventType(input) {
    const type = input.options[input.selectedIndex].value;
    
    if (type !== 'Mostra' && type !== 'Conferenza') {
        addError(input, 'La tipologia dell\'evento deve essere Mostra o Conferenza');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

function checkEventManager(input) {
    const manager = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`.:(\-)\s]+$');

    if (manager.length === 0) {
        addError(input, 'Non è possibile inserire un organizzatore vuoto');
        return false;
    } else if (manager.length < 2) {
        addError(input, 'Non è possibile inserire un organizzatore più corto di 2 caratteri');
        return false;
    } else if (manager.length > 32) {
        addError(input, 'Non è possibile inserire un organizzatore più lungo di 32 caratteri');
        return false;
    } else if (!pattern.test(manager)) {
        addError(input, 'L\'organizzatore dell\'evento contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` . : - ()');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

/* CONTROLLI E GESTIONE DELLE RECENSIONI */
function checkReviewTitle(input) {
    const title = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,\-:()\s]+$');

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
        addError(input, 'Il titolo inserito contiene dei caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , \ - : ()');
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
    } else if (content.length  > 65535) {
        addError(input, 'Non è possibile inserire una recensione con un contenuto più lungo di 65535 caratteri');
        return false;
    } else {
        removeError(input);
        return true;
    }
}

/* CONTROLLI E GESTIONE DEI FORM */
function artworkFormValidation() {
    const author = document.getElementById('author');
    const title = document.getElementById('title');
    const description = document.getElementById('operaDescriptionArea');
    const years = document.getElementById('years');
    const style = document.getElementById('style');
    const technique = document.getElementById('technique');
    const material = document.getElementById('material');
    const dimensions = document.getElementById('dimensions');
    const loanYes = document.getElementById('loanYes');
    const loanNo = document.getElementById('loanNo');
    const image = document.getElementById('imageUpload');

    const authorResult = checkArtworkAuthor(author);
    const titleResult = checkArtworkTitle(title);
    const descriptionResult = checkArtworkDescription(description);
    const yearsResult = checkArtworkDate(years);
    const styleResult = checkArtworkStyle(style);
    const techniqueResult = checkArtworkTechnique(technique);
    const materialResult = checkArtworkMaterial(material);
    const dimensionsResult = checkArtworkDimensions(dimensions);
    const loanResult = checkArtworkLoan(loanYes, loanNo);
    const imageResult = checkArtworkImage(image);

    return authorResult && titleResult && descriptionResult && yearsResult && styleResult && techniqueResult && materialResult && dimensionsResult && loanResult && imageResult;
}

function eventFormValidation() {
    const title = document.getElementById('title');
    const description = document.getElementById('eventDescriptionArea');
    const beginDate = document.getElementById('beginDate');
    const endDate = document.getElementById('endDate');
    const type = document.getElementById('type');
    const manager = document.getElementById('manager');

    const titleResult = checkEventTitle(title);
    const descriptionResult = checkEventDescription(description);
    const beginDateResult = checkDate(beginDate);
    const endDateResult = checkDate(endDate);
    const typeResult = checkEventType(type);
    const managerResult = checkEventManager(manager);

    let dateComparisonResult = true;
    if (beginDateResult && endDateResult) {
        dateComparisonResult = checkDateComparison(beginDate, endDate);
    }

    return titleResult && descriptionResult && beginDateResult && endDateResult && dateComparisonResult && typeResult && managerResult;
}

function reviewFormValidation() {
    const title = document.getElementById('title');
    const content = document.getElementById('description');

    const titleResult = checkReviewTitle(title);
    const contentResult = checkReviewContent(content);

    return titleResult && contentResult;
}

function userFormValidation() {

}

function registrationFormValidation() {

}
