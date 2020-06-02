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
    } else if (description.length > 700) {
        addError(input, 'Non è possibile inserire una descrizione più lunga di 700 caratteri');
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
    const title = document.getElementById("title");
    const description = document.getElementById("description");
    const beginDate = document.getElementById("beginDate");
    const endDate = document.getElementById("endDate");
    const type = document.getElementById("type");
    const manager = document.getElementById("manager");

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
