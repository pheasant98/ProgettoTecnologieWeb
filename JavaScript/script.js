/* GESTIONE DEL MENU AD HAMBURGER */
window.onload = menu;

function touch() {
    const menus = document.getElementById('menu');
    const content = document.getElementById('content');
    const breadcrumbs = document.getElementById('breadcrumbs');

    menus.className === 'show' ? menus.removeAttribute('class') : menus.className = 'show';
    content.className === 'hide' ? content.removeAttribute('class') : content.className = 'hide';
    breadcrumbs.className === 'hide' ? breadcrumbs.removeAttribute('class') : breadcrumbs.className = 'hide';
}

function menu() {
    document.getElementById('hamburgerMenu').addEventListener('click', touch);
}

/* GESTIONE DELLA TIPOLOGIA DELLE OPERE */
function artworkStyleChanged(isModify) {
    const styleInput = document.getElementById('style');
    const style = styleInput.options[styleInput.selectedIndex].value.trim();

    if (style === 'Dipinti') {
        const techniqueInput = document.getElementById('technique');
        const techniqueParent = techniqueInput.parentElement;
        techniqueParent.removeAttribute('class');

        const materialInput = document.getElementById('material');
        const materialParent = materialInput.parentElement;
        materialParent.className = 'hideContent';

        if (isModify) {
            const techniqueInputSkip = document.getElementById('postTechniqueSkip');
            const techniqueParentSkip = techniqueInputSkip.parentElement;
            techniqueParentSkip.removeAttribute('class');

            const materialInputSkip = document.getElementById('postMaterialSkip');
            const materialParentSkip = materialInputSkip.parentElement;
            materialParentSkip.className = 'hideContent';
        }
    } else if (style === 'Sculture') {
        const materialInput = document.getElementById('material');
        const materialParent = materialInput.parentElement;
        materialParent.removeAttribute('class');

        const techniqueInput = document.getElementById('technique');
        const techniqueParent = techniqueInput.parentElement;
        techniqueParent.className = 'hideContent';

        if (isModify) {
            const materialInputSkip = document.getElementById('postMaterialSkip');
            const materialParentSkip = materialInputSkip.parentElement;
            materialParentSkip.removeAttribute('class');

            const techniqueInputSkip = document.getElementById('postTechniqueSkip');
            const techniqueParentSkip = techniqueInputSkip.parentElement;
            techniqueParentSkip.className = 'hideContent';
        }
    }
}

/* GESTIONE DELL'AGGIUNTA E DELLA RIMOZIONE DEGLI ERRORI DAI FORM */
function removeError(input, tags) {
    const parentNode = input.parentNode;

    if (parentNode.children.length > tags) {
        parentNode.removeChild(parentNode.children[1]);
    }
}

function addError(input, error, tags) {
    removeError(input, tags);

    const parentNode = input.parentNode;
    const span = document.createElement("span");

    span.className = "formFieldError";
    span.insertAdjacentHTML('afterbegin', error);

    parentNode.insertBefore(span, parentNode.children[1]);
}

function removeRadioError(input) {
    const parentNode = input.parentElement.parentElement.parentElement;

    if (parentNode.children.length > 2) {
        parentNode.removeChild(parentNode.children[1]);
    }
}

function addRadioError(input, error) {
    removeRadioError(input);

    const parentNode = input.parentElement.parentElement.parentElement;
    const span = document.createElement("span");

    span.className = "formFieldError";
    span.insertAdjacentHTML('afterbegin', error);

    parentNode.insertBefore(span, parentNode.children[1]);
}

function scrollToError() {
    const errors = document.getElementsByClassName('formFieldError');

    let input = errors[0].nextElementSibling;
    while (input.tagName.toLowerCase() !== 'input' && input.tagName.toLowerCase() !== 'textarea' && input.tagName.toLowerCase() !== 'select') {
        input = input.nextElementSibling;
    }

    input.focus();
    errors[0].scrollIntoView({behavior: 'smooth'});
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
    return month > 0 && month <= 12 && day > 0 && day <= daysInMonth(month, year);
}

function getDateFromString(date) {
    const dateArray = date.split('-');
    return new Date(parseInt(dateArray[2]), parseInt(dateArray[1]) - 1, parseInt(dateArray[0]));
}

function checkDateFormat(date) {
    const pattern = new RegExp('^\\d{2}-\\d{2}-\\d{4}$');
    return pattern.test(date);
}

function checkDateValidity(date) {
    const dateArray = date.split('-');
    return isDateValid(parseInt(dateArray[0]), parseInt(dateArray[1]), parseInt(dateArray[2]));
}

function checkDate(input) {
    const date = input.value;

    if (date.length === 0) {
        addError(input, 'Non è possibile inserire una data vuota', 2);
        return false;
    } else if (!checkDateFormat(date)) {
        addError(input, 'Non è possibile inserire una data espressa in un formato diverso da "gg-mm-aaaa"', 2);
        return false;
    } else if (!checkDateValidity(date)) {
        addError(input, 'La data inserita non è valida', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

/* CONTROLLI E GESTIONE DELLE OPERE */
function checkArtworkAuthor(input) {
    const author = input.value;
    const pattern = new RegExp('^[A-zÀ-ú\'\-`.\\s]+$');

    if (author.length === 0) {
        addError(input, 'Non è possibile inserire un autore vuoto', 2);
        return false;
    } else if (author.length < 5) {
        addError(input, 'Non è possibile inserire un autore più corto di 5 caratteri', 2);
        return false;
    } else if (author.length > 64) {
        addError(input, 'Non è possibile inserire un autore più lungo di 64 caratteri', 2);
        return false;
    } else if (!pattern.test(author)) {
        addError(input, 'L\'autore contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, spazi e i seguenti caratteri speciali \' - . `', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkArtworkTitle(input) {
    const title = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,:(\-)\\s]+$');

    if (title.length === 0) {
        addError(input, 'Non è possibile inserire un titolo vuoto', 2);
        return false;
    } else if (title.length < 2) {
        addError(input, 'Non è possibile inserire un titolo più corto di 2 caratteri', 2);
        return false;
    } else if (title.length > 64) {
        addError(input, 'Non è possibile inserire un titolo più lungo di 64 caratteri', 2);
        return false;
    } else if (!pattern.test(title)) {
        addError(input, 'Il titolo contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , : - ()', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkArtworkDescription(input) {
    const description = input.value;

    if (description.length === 0) {
        addError(input, 'Non è possibile inserire una descrizione vuota', 2);
        return false;
    } else if (description.length < 2) {
        addError(input, 'Non è possibile inserire una descrizione più corta di 2 caratteri', 2);
        return false;
    } else if (description.length > 65535) {
        addError(input, 'Non è possibile inserire una descrizione più lunga di 65535 caratteri', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkArtworkDate(input) {
    const years = input.value;
    const pattern = new RegExp('^\\d{2}$');

    if (years.length === 0) {
        addError(input, 'Non è possibile inserire una datazione vuota', 2);
        return false;
    } else if (!parseInt(years)) {
        addError(input, 'La datazione dell\'opera deve essere un numero intero', 2);
        return false;
    } else if (!pattern.test(years)) {
        addError(input, 'La datazione deve contenere solo l\'anno', 2);
        return false;
    } else if (parseInt(years) < 1400 || parseInt(years) > parseInt((new Date()).getFullYear().toString())) {
        addError(input, 'La datazione dell\'opera deve essere compresa tra il 1400 e l\'anno corrente', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkArtworkStyle(input) {
    const style = input.options[input.selectedIndex].value;

    if (style !== 'Sculture' && style !== 'Dipinti') {
        addError(input, 'Lo stile dell\'opera deve essere Scultura o Dipinto', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkArtworkTechnique(input) {
    const technique = input.value;
    const pattern = new RegExp('^[A-zÀ-ú\'\-`\\s]+$');

    if (technique.length === 0) {
        addError(input, 'Non è possibile inserire una tecnica vuota', 2);
        return false;
    } else if (technique.length < 4) {
        addError(input, 'Non è possibile inserire una tecnica più corta di 4 caratteri', 2);
        return false;
    } else if (technique.length > 64) {
        addError(input, 'Non è possibile inserire una tecnica più lunga di 64 caratteri', 2);
        return false;
    } else if (!pattern.test(technique)) {
        addError(input, 'La tecnica contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, accenti, spazi e i seguenti caratteri speciali \' \ - `', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkArtworkMaterial(input) {
    const material = input.value;
    const pattern = new RegExp('^[A-zÀ-ú\'\-`\\s]+$');

    if (material.length === 0) {
        addError(input, 'Non è possibile inserire un materiale vuoto', 2);
        return false;
    } else if (material.length < 4) {
        addError(input, 'Non è possibile inserire un materiale più corto di 4 caratteri', 2);
        return false;
    } else if (material.length > 32) {
        addError(input, 'Non è possibile inserire un materiale più lungo di 32 caratteri', 2);
        return false;
    } else if (!pattern.test(material)) {
        addError(input, 'Il materiale contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, spazi, \' \ - `', 2);
        return false;
    } else {
        removeError(input, 2);
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
        addError(input, 'Le dimensioni non rispettano il formato richiesto', 3);
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
        addError(inputNo, 'Il prestito deve essere scelto tra "Si" e "No"', 2);
        return false;
    } else {
        removeError(inputNo, 2);
        return true;
    }
}

// TODO: trovare alternativa per IE9

function checkArtworkImage(input, isInsert) {
    const tags = isInsert ? 2 : 3;

    if (input.files.length === 0) {
        addError(input, 'È necessario selezionare un\'immagine', tags);
        return false;
    } else if (input.files.length > 1) {
        addError(input, 'È necessario selezionare una ed una sola immagine', tags);
        return false;
    } else if (input.files[0].size > 512000) {
        addError(input, 'L\'immagine caricata è una dimensione troppo elevata. La dimensione massima accettata è 500<abbr title="Kilo Bytes" xml:lang="en">KB</abbr>', tags);
        return false;
    } else if (input.files[0].type !== 'image/jpeg' && input.files[0].type !== 'image/png') {
        addError(input, 'L\'estensione dell\'immagine non è supportata. L\'estensioni consentite sono .<abbr title="Joint Photographic Experts Group" xml:lang="en">jpeg</abbr>, .<abbr title="Joint Photographic Group" xml:lang="en">jpg</abbr>, .<abbr title="Portable Network Graphics" xml:lang="en">png</abbr>', tags);
        return false;
    } else {
        removeError(input, tags);
        return true;
    }
}

/* CONTROLLI E GESTIONE DEGLI EVENTI */
function checkEventTitle(input) {
    const title = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,\-:()\\s]+$');
    
    if (title.length === 0) {
        addError(input, 'Non è possibile inserire un titolo vuoto', 2);
        return false;
    } else if (title.length < 2) {
        addError(input, 'Non è possibile inserire un titolo più corto di 2 caratteri', 2);
        return false;
    } else if (title.length > 64) {
        addError(input, 'Non è possibile inserire un titolo più lungo di 64 caratteri', 2);
        return false;
    } else if (!pattern.test(title)) {
        addError(input, 'Il titolo contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , - : ()', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkEventDescription(input) {
    const description = input.value;
    
    if (description.length === 0) {
        addError(input, 'Non è possibile inserire una descrizione vuota', 2);
        return false;
    } else if (description.length < 30) {
        addError(input, 'Non è possibile inserire una descrizione più corta di 30 caratteri', 2);
        return false;
    } else if (description.length > 65535) {
        addError(input, 'Non è possibile inserire una descrizione più lunga di 65535 caratteri', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkBeginDate(beginDateInput) {
    const beginDate = getDateFromString(beginDateInput.value);

    const lowerBound = new Date();
    lowerBound.setHours(0,0,0,0);

    const upperBound = new Date(lowerBound.getFullYear() + 3, lowerBound.getMonth(), lowerBound.getDay());

    if (beginDate < lowerBound) {
        addError(beginDateInput, 'Non è possibile inserire una data di inizio evento precedente alla data odierna', 2);
        return false;
    } else if (beginDate > upperBound) {
        addError(beginDateInput, 'Non è possibile inserire una data di inizio evento successiva a tre anni dalla data odierna', 2);
        return false;
    } else {
        removeError(beginDateInput, 2);
        return true;
    }
}

function checkDateComparison(beginDateInput, endDateInput) {
    const beginDate = getDateFromString(beginDateInput.value);
    const endDate = getDateFromString(endDateInput.value);

    let durationBound = getDateFromString(beginDateInput.value);
    durationBound = addMonths(durationBound, 6);

    if (beginDate > endDate) {
        addError(endDateInput, 'Non è possibile inserire una data di fine evento precendente alla data di inizio evento', 2);
        return false;
    } else if (endDate > durationBound) {
        addError(endDateInput, 'Non è possibile inserire un evento che abbia una durata superiore ai sei mesi', 2);
        return false;
    } else {
        removeError(endDateInput, 2);
        return true;
    }
}

function checkEventType(input) {
    const type = input.options[input.selectedIndex].value;
    
    if (type !== 'Mostre' && type !== 'Conferenze') {
        addError(input, 'La tipologia dell\'evento deve essere Mostra o Conferenza', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkEventManager(input) {
    const manager = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`.:(\-)\\s]+$');

    if (manager.length === 0) {
        addError(input, 'Non è possibile inserire un organizzatore vuoto', 2);
        return false;
    } else if (manager.length < 2) {
        addError(input, 'Non è possibile inserire un organizzatore più corto di 2 caratteri', 2);
        return false;
    } else if (manager.length > 32) {
        addError(input, 'Non è possibile inserire un organizzatore più lungo di 32 caratteri', 2);
        return false;
    } else if (!pattern.test(manager)) {
        addError(input, 'L\'organizzatore dell\'evento contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` . : - ()', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

/* CONTROLLI E GESTIONE DELLE RECENSIONI */
function checkReviewTitle(input) {
    const title = input.value;
    const pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,\-:()\\s]+$');

    if (title.length === 0) {
        addError(input, 'Non è possibile inserire una recensione con un oggetto vuoto', 2);
        return false;
    } else if (title.length < 2) {
        addError(input, 'Non è possibile inserire una recensione con un oggetto più corto di 2 caratteri', 2);
        return false;
    } else if (title.length > 64) {
        addError(input, 'Non è possibile inserire una recensione con un oggetto più lungo di 64 caratteri', 2);
        return false;
    } else if (!pattern.test(title)) {
        addError(input, 'L\'oggetto inserito contiene dei caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , \ - : ()', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkReviewContent(input) {
    const content = input.value;

    if (content.length === 0) {
        addError(input, 'Non è possibile inserire una recensione con un contenuto vuoto', 2);
        return false;
    } else if (content.length  < 4) {
        addError(input, 'Non è possibile inserire una recensione con un contenuto più corto di 4 caratteri', 2);
        return false;
    } else if (content.length  > 65535) {
        addError(input, 'Non è possibile inserire una recensione con un contenuto più lungo di 65535 caratteri', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

/* CONTROLLI E GESTIONE DEGLI UTENTI */
function checkUserName(input) {
    const name = input.value;
    const pattern = new RegExp('^[A-zÀ-ú\'\-`.\\s]+$');
    
    if (name.length === 0) {
        addError(input, 'Non è possibile inserire un nome vuoto', 2);
        return false;
    } else if (name.length < 2) {
        addError(input, 'Non è possibile inserire un nome più corto di 2 caratteri', 2);
        return false;
    } else if (name.length > 32) {
        addError(input, 'Non è possibile inserire un nome più lungo di 32 caratteri', 2);
        return false;
    } else if (!pattern.test(name)) {
        addError(input, 'Il nome inserito contiene dei caratteri non consentiti. Quelli possibili sono lettere, anche accentate, spazi e i seguenti caratteri speciali \' \ - ` .', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkUserSurname(input) {
    const surname = input.value;
    const pattern = new RegExp('^[A-zÀ-ú\'\-`.\\s]+$');

    if (surname.length === 0) {
        addError(input, 'Non è possibile inserire un cognome vuoto', 2);
        return false;
    } else if (surname.length < 2) {
        addError(input, 'Non è possibile inserire un cognome più corto di 2 caratteri', 2);
        return false;
    } else if (surname.length > 32) {
        addError(input, 'Non è possibile inserire un cognome più lungo di 32 caratteri', 2);
        return false;
    } else if (!pattern.test(surname)) {
        addError(input, 'Il cognome inserito contiene dei caratteri non consentiti, è possibile inserire solamente lettere, possibilmente accentate, spazi e i seguenti caratteri speciali \' \ - ` .', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkUserDate(input) {
    const date = input.value;

    if (date.length === 0) {
        addError(input, 'Non è possibile inserire una data di nascita vuota', 2);
        return false;
    } else if (!checkDateFormat(date)) {
        addError(input, 'Non è possibile inserire una data di nascita espressa in un formato diverso da "gg-mm-aaaa"', 2);
        return false;
    } else if (!checkDateValidity(date)) {
        addError(input, 'La data di nascita inserita non è valida', 2);
        return false;
    } else {
        const birthDate = getDateFromString(date);
        const lowerBound = new Date(parseInt('1900'), parseInt('00'), parseInt('01'));
        const upperBound = new Date(parseInt('2006'), parseInt('11'), parseInt('31'));

        if (birthDate < lowerBound) {
            addError(input, 'Non è possibile inserire una data di nascita precedente al 01-01-1900', 2);
            return false;
        } else if (birthDate > upperBound) {
            addError(input, 'Non è possibile inserire una data di nascita successiva al 31-12-2006', 2);
            return false;
        } else {
            removeError(input, 2);
            return true;
        }
    }
}

function checkUserSex(inputSexMale, inputSexFemale, inputSexOther) {
    const male = inputSexMale.checked;
    const female = inputSexFemale.checked;
    const other = inputSexOther.checked;

    if (!male && !female && !other) {
        addRadioError(inputSexOther, 'Il sesso deve essere scelto tra "Maschile", "Femminile" e "Preferisco non dichiarare"');
        return false;
    } else {
        removeRadioError(inputSexOther);
        return true;
    }
}

function checkUserMail(input) {
    const mail = input.value;
    const pattern = new RegExp('^[a-zA-Z0-9.!#$%&\'*+^_`{|}~\-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$');

    if (mail.length === 0) {
        addError(input, 'Non è possibile inserire un indirizzo <span xml:lang="en">email</span> vuoto', 2);
        return false;
    } else if (mail.length > 64) {
        addError(input, 'Non è possibile inserire un indirizzo <span xml:lang="en">email</span> più lungo di 64 caratteri', 2);
        return false;
    } else if (!pattern.test(mail)) {
        addError(input, 'L\'indirizzo <span xml:lang="en">email</span> inserito non è valido', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkUserUsername(input) {
    const username = input.value;
    
    if (username.length === 0) {
        addError(input, 'Non è possibile inserire uno <span xml:lang="en">username</span> vuoto', 2);
        return false;
    } else if (username.length < 4) {
        addError(input, 'Non è possibile inserire uno <span xml:lang="en">username</span> più corto di 4 caratteri', 2);
        return false;
    } else if (username.length > 32) {
        addError(input, 'Non è possibile inserire uno <span xml:lang="en">username</span> più lungo di 32 caratteri', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkUserOldPassword(input, tags) {
    const password = input.value;
    const pattern = new RegExp('^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!#$%&\'*+^_`\-{|}~@]).*$');

    if (password.length === 0) {
        addError(input, 'Non è possibile che la <span xml:lang="en">password</span> corrente sia vuota', tags);
        return false;
    } else if (password.length < 8) {
        addError(input, 'Non è possibile che la <span xml:lang="en">password</span> corrente sia più corta di 8 caratteri', tags);
        return false;
    } else if (!pattern.test(password)) {
        addError(input, 'Non è possibile che la <span xml:lang="en">password</span> corrente non soddisfi tutti i requisiti richiesti', tags);
        return false;
    } else {
        removeError(input, tags);
        return true;
    }
}

function checkUserPassword(input, tags) {
    const password = input.value;
    const pattern = new RegExp('^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!#$%&\'*+^_`\-{|}~@]).*$');
    
    if (password.length === 0) {
        addError(input, 'Non è possibile inserire una <span xml:lang="en">password</span> vuota', tags);
        return false;
    } else if (password.length < 8) {
        addError(input, 'Non è possibile inserire una <span xml:lang="en">password</span> più corta di 8 caratteri', tags);
        return false;
    } else if (!pattern.test(password)) {
        addError(input, 'La <span xml:lang="en">password</span> inserita non soddisfa tutti i requisiti richiesti', tags);
        return false;
    } else {
        removeError(input, tags);
        return true;
    }
}

function checkSamePassword(inputNewPassword, inputConfirmPassword) {
    if (inputNewPassword.value !== inputConfirmPassword.value) {
        addError(inputConfirmPassword, 'Le <span xml:lang="it">password</span> inserite non sono uguali', 2);
        return false;
    } else {
        removeError(inputConfirmPassword, 2);
        return true;
    }
}

function checkSameOldPassword(inputOldPassword, inputNewPassword, tags) {
    if (inputOldPassword.value === inputNewPassword.value) {
        addError(inputNewPassword, 'La nuova <span xml:lang="it">password</span> non può essere uguale a quella corrente', tags);
        return false;
    } else {
        removeError(inputNewPassword, tags);
        return true;
    }
}

/* CONTROLLI E GESTIONE DEI FORM */
function artworkFormValidation(isInsert) {
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
    const imageResult = checkArtworkImage(image, isInsert);

    scrollToError();

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
    let beginDateResult = checkDate(beginDate);
    const endDateResult = checkDate(endDate);
    const typeResult = checkEventType(type);
    const managerResult = checkEventManager(manager);

    if (beginDateResult) {
        beginDateResult = checkBeginDate(beginDate);
    }

    let dateComparisonResult = true;
    if (beginDateResult && endDateResult) {
        dateComparisonResult = checkDateComparison(beginDate, endDate);
    }

    scrollToError();

    return titleResult && descriptionResult && beginDateResult && endDateResult && dateComparisonResult && typeResult && managerResult;
}

function reviewFormValidation() {
    const title = document.getElementById('title');
    const description = document.getElementById('reviewDescriptionArea');

    const titleResult = checkReviewTitle(title);
    const contentResult = checkReviewContent(description);

    scrollToError();

    return titleResult && contentResult;
}

function userFormValidation() {
    const name = document.getElementById('name');
    const surname = document.getElementById('surname');
    const date = document.getElementById('date');
    const sexM = document.getElementById('sexM');
    const sexF = document.getElementById('sexF');
    const sexA = document.getElementById('sexA');
    const email = document.getElementById('email');
    const oldPassword = document.getElementById('oldPassword');
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('repetePassword');

    const nameResult = checkUserName(name);
    const surnameResult = checkUserSurname(surname);
    const dateResult = checkUserDate(date);
    const sexResult = checkUserSex(sexM, sexF, sexA);
    const emailResult = checkUserMail(email);

    let oldPasswordResult = true;
    let newPasswordResult = true;
    let confirmPasswordResult = true;
    let samePasswordResult = true;
    let sameOldPasswordResult = true;

    if (oldPassword.value !== '' || newPassword.value !== '' || confirmPassword.value !== '') {
        oldPasswordResult = checkUserOldPassword(oldPassword, 2);
        newPasswordResult = checkUserPassword(newPassword, 3);
        confirmPasswordResult = checkUserPassword(confirmPassword, 2);

        if (oldPasswordResult && newPasswordResult && confirmPasswordResult) {
            samePasswordResult = checkSamePassword(newPassword, confirmPassword);

            if (samePasswordResult) {
                sameOldPasswordResult = checkSameOldPassword(oldPassword, newPassword, 3);
            }
        }
    }

    scrollToError();

    return nameResult && surnameResult && dateResult && sexResult && emailResult && oldPasswordResult && newPasswordResult && confirmPasswordResult && samePasswordResult && sameOldPasswordResult;
}

function registrationFormValidation() {
    const name = document.getElementById('name');
    const surname = document.getElementById('surname');
    const date = document.getElementById('date');
    const sexM = document.getElementById('sexM');
    const sexF = document.getElementById('sexF');
    const sexA = document.getElementById('sexA');
    const email = document.getElementById('email');
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('repetePassword');

    const nameResult = checkUserName(name);
    const surnameResult = checkUserSurname(surname);
    const dateResult = checkUserDate(date);
    const sexResult = checkUserSex(sexM, sexF, sexA);
    const emailResult = checkUserMail(email);
    const usernameResult = checkUserUsername(username);
    const passwordResult = checkUserPassword(password, 3);
    const confirmPasswordResult = checkUserPassword(confirmPassword, 2);

    let samePasswordResult = true;
    if (passwordResult && confirmPasswordResult) {
        samePasswordResult = checkSamePassword(password, confirmPassword);
    }

    scrollToError();

    return nameResult && surnameResult && dateResult && sexResult && emailResult && usernameResult && passwordResult && confirmPasswordResult && samePasswordResult;
}
