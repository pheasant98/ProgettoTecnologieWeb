/* GESTIONE DEL MENU AD HAMBURGER */
window.onload = menu;

function touch() {
    var menus = document.getElementById('menu');
    var content = document.getElementById('content');
    var breadcrumbs = document.getElementById('breadcrumbs');

    menus.className === 'show' ? menus.removeAttribute('class') : menus.className = 'show';
    content.className === 'hide' ? content.removeAttribute('class') : content.className = 'hide';
    breadcrumbs.className === 'hide' ? breadcrumbs.removeAttribute('class') : breadcrumbs.className = 'hide';
}

function menu() {
    document.getElementById('hamburgerMenu').addEventListener('click', touch);
}

/* GESTIONE DELLA TIPOLOGIA DELLE OPERE */
function artworkStyleChanged(isModify) {
    var styleInput = document.getElementById('style');
    var style = styleInput.options[styleInput.selectedIndex].value.trim();

    if (style === 'Dipinti') {
        var techniqueInputD = document.getElementById('technique');
        var techniqueParentD = techniqueInputD.parentElement;
        techniqueParentD.removeAttribute('class');

        var materialInputD = document.getElementById('material');
        var materialParentD = materialInputD.parentElement;
        materialParentD.className = 'hideContent';

        if (isModify) {
            var techniqueInputSkipD = document.getElementById('postTechniqueSkip');
            var techniqueParentSkipD = techniqueInputSkipD.parentElement;
            techniqueParentSkipD.removeAttribute('class');

            var materialInputSkipD = document.getElementById('postMaterialSkip');
            var materialParentSkipD = materialInputSkipD.parentElement;
            materialParentSkipD.className = 'hideContent';
        }
    } else if (style === 'Sculture') {
        var materialInputS = document.getElementById('material');
        var materialParentS = materialInputS.parentElement;
        materialParentS.removeAttribute('class');

        var techniqueInputS = document.getElementById('technique');
        var techniqueParentS = techniqueInputS.parentElement;
        techniqueParentS.className = 'hideContent';

        if (isModify) {
            var materialInputSkipS = document.getElementById('postMaterialSkip');
            var materialParentSkipS = materialInputSkipS.parentElement;
            materialParentSkipS.removeAttribute('class');

            var techniqueInputSkipS = document.getElementById('postTechniqueSkip');
            var techniqueParentSkipS = techniqueInputSkipS.parentElement;
            techniqueParentSkipS.className = 'hideContent';
        }
    }
}

/* GESTIONE DELL'AGGIUNTA E DELLA RIMOZIONE DEGLI ERRORI DAI FORM */
function removeError(input, tags) {
    var parentNode = input.parentNode;

    if (parentNode.children.length > tags) {
        parentNode.removeChild(parentNode.children[1]);
    }
}

function addError(input, error, tags) {
    removeError(input, tags);

    var parentNode = input.parentNode;
    var span = document.createElement('span');

    span.className = 'formFieldError';
    span.insertAdjacentHTML('afterbegin', error);

    parentNode.insertBefore(span, parentNode.children[1]);
}

function removeRadioError(input) {
    var parentNode = input.parentElement.parentElement.parentElement;

    if (parentNode.children.length > 2) {
        parentNode.removeChild(parentNode.children[1]);
    }
}

function addRadioError(input, error) {
    removeRadioError(input);

    var parentNode = input.parentElement.parentElement.parentElement;
    var span = document.createElement('span');

    span.className = 'formFieldError';
    span.insertAdjacentHTML('afterbegin', error);

    parentNode.insertBefore(span, parentNode.children[1]);
}

function scrollToError() {
    var errors = document.getElementsByClassName('formFieldError');

    var input = errors[0].nextElementSibling;
    while (input.tagName.toLowerCase() !== 'input' && input.tagName.toLowerCase() !== 'textarea' && input.tagName.toLowerCase() !== 'select') {
        input = input.nextElementSibling;
    }

    input.focus();
    errors[0].scrollIntoView({behavior: 'smooth'});
}

/* CONTROLLI E GESTIONE DELLE DATE */
function addMonths(date, months) {
    var oldDate = date.getDate();

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
    var dateArray = date.trim().split('-');
    return new Date(parseInt(dateArray[2]), parseInt(dateArray[1]) - 1, parseInt(dateArray[0]));
}

function checkDateFormat(date) {
    var pattern = new RegExp('^\\d{2}-\\d{2}-\\d{4}$');
    return pattern.test(date.trim());
}

function checkDateValidity(date) {
    var dateArray = date.trim().split('-');
    return isDateValid(parseInt(dateArray[0]), parseInt(dateArray[1]), parseInt(dateArray[2]));
}

function checkDate(input) {
    var date = input.value.trim();

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
    var author = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú\'\-`.\\s]+$');

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
    var title = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,:(\-)\\s]+$');

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
    var description = input.value.trim();

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
    var years = input.value.trim();
    var pattern = new RegExp('^\\d{2}$');

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
    var style = input.options[input.selectedIndex].value.trim();

    if (style !== 'Sculture' && style !== 'Dipinti') {
        addError(input, 'Lo stile dell\'opera deve essere Scultura o Dipinto', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkArtworkTechnique(input) {
    var technique = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú\'\-`\\s]+$');

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
    var material = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú\'\-`\\s]+$');

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
    var dimensions = input.value.trim();
    var pattern = new RegExp('^([1-9][0-9]{0,2}|1000)x([1-9][0-9]{0,2}|1000)$');

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
    var yes = inputYes.checked;
    var no = inputNo.checked;

    if (!yes && !no) {
        addError(inputNo, 'Il prestito deve essere scelto tra "Si" e "No"', 2);
        return false;
    } else {
        removeError(inputNo, 2);
        return true;
    }
}

function checkArtworkImage(input) {
    var tags = 2;

    for (var i = 0; i < input.parentElement.children.length; ++i) {
        if (input.parentElement.children[i].tagName.toLowerCase() === 'img') {
            tags = 3;
        }
    }

    if (window.FileList) {
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
    } else {
        // TODO: alternativa per IE9
    }
}

function checkInputArtworkImage(event) {
    var input = document.getElementById('imageUpload');
    var checkImage = checkArtworkImage(input);

    if (checkImage && window.FileReader) {
        if (!document.getElementById('uploadedImage')) {
            var parentNode = input.parentNode;
            var img = document.createElement('img');

            var name = document.getElementById('title').value.trim();

            img.setAttribute('id', 'uploadedImage');
            img.setAttribute('class', ''); // FIXME: aggiungere la giusta classe
            img.setAttribute('alt', 'Immagine inserita per l\'opera' + name)

            parentNode.append(img);
        }

        var target = event.target || window.event.srcElement;
        var fileReader = new FileReader();

        fileReader.onload = function () {
            document.getElementById('uploadedImage').src = fileReader.result;
        }
        fileReader.readAsDataURL(target.files[0]);
    }

    return checkImage;
}

function resetImage() {
    var image = document.getElementById('uploadedImage');
    if (image) {
        image.remove();
    }
}

/* CONTROLLI E GESTIONE DEGLI EVENTI */
function checkEventTitle(input) {
    var title = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,\-:()\\s]+$');
    
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
    var description = input.value.trim();
    
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
    var beginDate = getDateFromString(beginDateInput.value.trim());

    var lowerBound = new Date();
    lowerBound.setHours(0,0,0,0);

    var upperBound = new Date(lowerBound.getFullYear() + 3, lowerBound.getMonth(), lowerBound.getDay());

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
    var beginDate = getDateFromString(beginDateInput.value.trim());
    var endDate = getDateFromString(endDateInput.value.trim());

    var durationBound = getDateFromString(beginDateInput.value.trim());
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
    var type = input.options[input.selectedIndex].value.trim();
    
    if (type !== 'Mostre' && type !== 'Conferenze') {
        addError(input, 'La tipologia dell\'evento deve essere Mostra o Conferenza', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkEventManager(input) {
    var manager = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú0-9\'`.:(\-)\\s]+$');

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
    var title = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,\-:()\\s]+$');

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
    var content = input.value.trim();

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
    var name = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú\'\-`.\\s]+$');
    
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
    var surname = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú\'\-`.\\s]+$');

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
    var date = input.value.trim();

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
        var birthDate = getDateFromString(date);
        var lowerBound = new Date(parseInt('1900'), parseInt('00'), parseInt('01'));
        var upperBound = new Date(parseInt('2006'), parseInt('11'), parseInt('31'));

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
    var male = inputSexMale.checked;
    var female = inputSexFemale.checked;
    var other = inputSexOther.checked;

    if (!male && !female && !other) {
        addRadioError(inputSexOther, 'Il sesso deve essere scelto tra "Maschile", "Femminile" e "Preferisco non dichiarare"');
        return false;
    } else {
        removeRadioError(inputSexOther);
        return true;
    }
}

function checkUserMail(input) {
    var mail = input.value.trim();
    var pattern = new RegExp('^[a-zA-Z0-9.!#$%&\'*+^_`{|}~\-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$');

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
    var username = input.value.trim();
    
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
    var password = input.value.trim();
    var pattern = new RegExp('^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!#$%&\'*+^_`\-{|}~@]).*$');

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
    var password = input.value.trim();
    var pattern = new RegExp('^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!#$%&\'*+^_`\-{|}~@]).*$');
    
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
    if (inputNewPassword.value.trim() !== inputConfirmPassword.value.trim()) {
        addError(inputConfirmPassword, 'Le <span xml:lang="it">password</span> inserite non sono uguali', 2);
        return false;
    } else {
        removeError(inputConfirmPassword, 2);
        return true;
    }
}

function checkSameOldPassword(inputOldPassword, inputNewPassword, tags) {
    if (inputOldPassword.value.trim() === inputNewPassword.value.trim()) {
        addError(inputNewPassword, 'La nuova <span xml:lang="it">password</span> non può essere uguale a quella corrente', tags);
        return false;
    } else {
        removeError(inputNewPassword, tags);
        return true;
    }
}

/* CONTROLLI E GESTIONE DEI FORM */
function artworkFormValidation(isInsert) {
    var author = document.getElementById('author');
    var title = document.getElementById('title');
    var description = document.getElementById('operaDescriptionArea');
    var years = document.getElementById('years');
    var style = document.getElementById('style');
    var technique = document.getElementById('technique');
    var material = document.getElementById('material');
    var dimensions = document.getElementById('dimensions');
    var loanYes = document.getElementById('loanYes');
    var loanNo = document.getElementById('loanNo');
    var image = document.getElementById('imageUpload');

    var authorResult = checkArtworkAuthor(author);
    var titleResult = checkArtworkTitle(title);
    var descriptionResult = checkArtworkDescription(description);
    var yearsResult = checkArtworkDate(years);
    var styleResult = checkArtworkStyle(style);
    var techniqueResult = checkArtworkTechnique(technique);
    var materialResult = checkArtworkMaterial(material);
    var dimensionsResult = checkArtworkDimensions(dimensions);
    var loanResult = checkArtworkLoan(loanYes, loanNo);

    var imageResult = true;
    if (isInsert) {
        imageResult = checkArtworkImage(image);
    }

    scrollToError();

    return authorResult && titleResult && descriptionResult && yearsResult && styleResult && techniqueResult && materialResult && dimensionsResult && loanResult && imageResult;
}

function eventFormValidation() {
    var title = document.getElementById('title');
    var description = document.getElementById('eventDescriptionArea');
    var beginDate = document.getElementById('beginDate');
    var endDate = document.getElementById('endDate');
    var type = document.getElementById('type');
    var manager = document.getElementById('manager');

    var titleResult = checkEventTitle(title);
    var descriptionResult = checkEventDescription(description);
    var beginDateResult = checkDate(beginDate);
    var endDateResult = checkDate(endDate);
    var typeResult = checkEventType(type);
    var managerResult = checkEventManager(manager);

    if (beginDateResult) {
        beginDateResult = checkBeginDate(beginDate);
    }

    var dateComparisonResult = true;
    if (beginDateResult && endDateResult) {
        dateComparisonResult = checkDateComparison(beginDate, endDate);
    }

    scrollToError();

    return titleResult && descriptionResult && beginDateResult && endDateResult && dateComparisonResult && typeResult && managerResult;
}

function reviewFormValidation() {
    var title = document.getElementById('title');
    var description = document.getElementById('reviewDescriptionArea');

    var titleResult = checkReviewTitle(title);
    var contentResult = checkReviewContent(description);

    scrollToError();

    return titleResult && contentResult;
}

function userFormValidation() {
    var name = document.getElementById('name');
    var surname = document.getElementById('surname');
    var date = document.getElementById('date');
    var sexM = document.getElementById('sexM');
    var sexF = document.getElementById('sexF');
    var sexA = document.getElementById('sexA');
    var email = document.getElementById('email');
    var oldPassword = document.getElementById('oldPassword');
    var newPassword = document.getElementById('newPassword');
    var confirmPassword = document.getElementById('repetePassword');

    var nameResult = checkUserName(name);
    var surnameResult = checkUserSurname(surname);
    var dateResult = checkUserDate(date);
    var sexResult = checkUserSex(sexM, sexF, sexA);
    var emailResult = checkUserMail(email);

    var oldPasswordResult = true;
    var newPasswordResult = true;
    var confirmPasswordResult = true;
    var samePasswordResult = true;
    var sameOldPasswordResult = true;

    if (oldPassword.value.trim() !== '' || newPassword.value.trim() !== '' || confirmPassword.value.trim() !== '') {
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
    var name = document.getElementById('name');
    var surname = document.getElementById('surname');
    var date = document.getElementById('date');
    var sexM = document.getElementById('sexM');
    var sexF = document.getElementById('sexF');
    var sexA = document.getElementById('sexA');
    var email = document.getElementById('email');
    var username = document.getElementById('username');
    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('repetePassword');

    var nameResult = checkUserName(name);
    var surnameResult = checkUserSurname(surname);
    var dateResult = checkUserDate(date);
    var sexResult = checkUserSex(sexM, sexF, sexA);
    var emailResult = checkUserMail(email);
    var usernameResult = checkUserUsername(username);
    var passwordResult = checkUserPassword(password, 3);
    var confirmPasswordResult = checkUserPassword(confirmPassword, 2);

    var samePasswordResult = true;
    if (passwordResult && confirmPasswordResult) {
        samePasswordResult = checkSamePassword(password, confirmPassword);
    }

    scrollToError();

    return nameResult && surnameResult && dateResult && sexResult && emailResult && usernameResult && passwordResult && confirmPasswordResult && samePasswordResult;
}
