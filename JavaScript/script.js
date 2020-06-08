/* GESTIONE DEL MENU AD HAMBURGER */
function touch() {
    var menus = document.getElementById('menu');

    menus.className === 'show' ? menus.removeAttribute('class') : menus.className = 'show';
}

function enterTouch(event) {
    if (event.keyCode === 13) {
        touch();
    }
}

/* GESTIONE DELLA TIPOLOGIA DELLE OPERE */
function artworkStyleChanged(isModify) {
    var styleInput = document.getElementById('style');
    var style = styleInput.options[styleInput.selectedIndex].value.trim();

    if (style === 'Dipinto') {
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
    } else if (style === 'Scultura') {
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

/* GESTIONE DELLA TIPOLOGIA DI CONTENUTO PER LA PAGINA GestioneContenuti.php */
function createOption(value, text) {
    var option = document.createElement('option');

    option.value = value;
    option.text = text;

    return option;
}

function contentTypeChanged() {
    var contentInput = document.getElementById('filterContent');
    var contentTypeInput = document.getElementById('filterContentType');

    var content = contentInput.options[contentInput.selectedIndex].value.trim();

    if (content === 'Opera') {
        if (contentTypeInput.querySelector('option[value="Mostra"]')) {
            contentTypeInput.removeChild(contentTypeInput.querySelector('option[value="Mostra"]'));
        }
        if (contentTypeInput.querySelector('option[value="Conferenza"]')) {
            contentTypeInput.removeChild(contentTypeInput.querySelector('option[value="Conferenza"]'));
        }
        if (!contentTypeInput.querySelector('option[value="Dipinto"]')) {
            contentTypeInput.add(createOption('Dipinto', 'Dipinti'));
        }
        if (!contentTypeInput.querySelector('option[value="Scultura"]')) {
            contentTypeInput.add(createOption('Scultura', 'Sculture'));
        }
    } else if (content === 'Evento') {
        if (contentTypeInput.querySelector('option[value="Dipinto"]')) {
            contentTypeInput.removeChild(contentTypeInput.querySelector('option[value="Dipinto"]'));
        }
        if (contentTypeInput.querySelector('option[value="Scultura"]')) {
            contentTypeInput.removeChild(contentTypeInput.querySelector('option[value="Scultura"]'));
        }
        if (!contentTypeInput.querySelector('option[value="Mostra"]')) {
            contentTypeInput.add(createOption('Mostra', 'Mostre'));
        }
        if (!contentTypeInput.querySelector('option[value="Conferenza"]')) {
            contentTypeInput.add(createOption('Conferenza', 'Conferenze'));
        }
    } else if (content === 'Nessun filtro') {
        if (contentTypeInput.querySelector('option[value="Dipinto"]')) {
            contentTypeInput.removeChild(contentTypeInput.querySelector('option[value="Dipinto"]'));
        }
        if (contentTypeInput.querySelector('option[value="Scultura"]')) {
            contentTypeInput.removeChild(contentTypeInput.querySelector('option[value="Scultura"]'));
        }
        if (contentTypeInput.querySelector('option[value="Mostra"]')) {
            contentTypeInput.removeChild(contentTypeInput.querySelector('option[value="Mostra"]'));
        }
        if (contentTypeInput.querySelector('option[value="Conferenza"]')) {
            contentTypeInput.removeChild(contentTypeInput.querySelector('option[value="Conferenza"]'));
        }
    }
}

/* GESTIONE MAPPA DI GOOGLE MAPS */
function checkMapsSupport() {
    if (navigator.appVersion.indexOf("MSIE 10") !== -1 || navigator.appVersion.indexOf("MSIE 9") !== -1) {
        var mapContainer = document.getElementById('mapContainer');
        mapContainer.parentElement.removeChild(mapContainer);
    }
}

/* GESTIONE DELL'AGGIUNTA E DELLA RIMOZIONE DEGLI ERRORI DAI FORM */
function removeError(input, tags) {
    var parentNode = input.parentElement;

    if (parentNode.children.length > tags) {
        parentNode.removeChild(parentNode.children[1]);
    }
}

function addError(input, error, tags) {
    removeError(input, tags);

    var parentNode = input.parentElement;
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

function removeSearchError(input) {
    var parentNode = input.parentElement;

    while (parentNode.children.length > 6) {
        parentNode.removeChild(parentNode.children[parentNode.children.length - 1]);
    }
}

function addSearchError(input, error) {
    removeError(input);

    var parentNode = input.parentElement;
    var span = document.createElement('span');

    span.className = 'formFieldError';
    span.insertAdjacentHTML('afterbegin', error);

    parentNode.appendChild(span);
}

function scrollToError() {
    var errors = document.getElementsByClassName('formFieldError');

    var i = 0;
    while (errors[i].parentElement.className === 'hideContent') {
        i++;
    }

    var input = errors[i].nextElementSibling;
    while (input.tagName.toLowerCase() !== 'input' && input.tagName.toLowerCase() !== 'textarea' && input.tagName.toLowerCase() !== 'select') {
        input = input.nextElementSibling;
    }

    input.focus();
    errors[i].scrollIntoView({behavior: 'smooth'});
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
        addError(input, 'Non è possibile inserire una data vuota', 3);
        return false;
    } else if (!checkDateFormat(date)) {
        addError(input, 'La data inserita non rispetta il formato richiesto', 3);
        return false;
    } else if (!checkDateValidity(date)) {
        addError(input, 'La data inserita non è valida', 3);
        return false;
    } else {
        removeError(input, 3);
        return true;
    }
}

/* CONTROLLI E GESTIONE DELLE OPERE */
function checkArtworkAuthor(input) {
    var author = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú\'\\-`.\\s]+$');

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
    var pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,:(\\-)\\s]+$');

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
    var pattern = new RegExp('^\\d{4}$');

    if (years.length === 0) {
        addError(input, 'Non è possibile inserire una datazione vuota', 3);
        return false;
    } else if (!parseInt(years)) {
        addError(input, 'La datazione dell\'opera deve essere un numero intero', 3);
        return false;
    } else if (!pattern.test(years)) {
        addError(input, 'La datazione non rispetta il formato richiesto', 3);
        return false;
    } else if (parseInt(years) < 1400 || parseInt(years) > parseInt((new Date()).getFullYear().toString())) {
        addError(input, 'La datazione dell\'opera deve essere compresa tra il 1400 e l\'anno corrente', 3);
        return false;
    } else {
        removeError(input, 3);
        return true;
    }
}

function checkArtworkStyle(input) {
    var style = input.options[input.selectedIndex].value.trim();

    if (style !== 'Scultura' && style !== 'Dipinto') {
        addError(input, 'Lo stile dell\'opera deve essere Scultura o Dipinto', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkArtworkTechnique(input) {
    var technique = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú\'\\-`\\s]+$');

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
    var pattern = new RegExp('^[A-zÀ-ú\'\\-`\\s]+$');

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
    var pattern = new RegExp('^([1-9][0-9]{0,3})\\sx\\s([1-9][0-9]{0,3})$');

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
        addRadioError(inputNo, 'Il prestito deve essere scelto tra "Si" e "No"');
        return false;
    } else {
        removeRadioError(inputNo);
        return true;
    }
}

function checkArtworkImage(input, isModify) {
    var tags = isModify ? 5 : 3;

    for (var i = 0; i < input.parentElement.children.length; ++i) {
        if (input.parentElement.children[i].tagName.toLowerCase() === 'img') {
            tags++;
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
            addError(input, 'L\'immagine selezionata supera la dimensione massima consentita', tags);
            return false;
        } else if (input.files[0].type !== 'image/jpeg' && input.files[0].type !== 'image/png') {
            addError(input, 'L\'immagine selezionata ha un\'estensione non supportata', tags);
            return false;
        } else {
            removeError(input, tags);
            return true;
        }
    } else {
        // Supporto per IE9
        if (input.value.trim().length === 0) {
            addError(input, 'È necessario selezionare un\'immagine', tags);
            return false;
        } else if (((input.value.trim() || '').match(/\.(jpeg|png|jpg)$/g) || []).length < 1) {
            addError(input, 'L\'immagine selezionata ha un\'estensione non supportata', tags);
            return false;
        } else if (((input.value.trim() || '').match(/\.(jpeg|png|jpg)\s[.(jpeg|png|jpg)]+/g) || []).length >= 1) {
            addError(input, 'È necessario selezionare una ed una sola immagine', tags);
            return false;
        } else {
            removeError(input, tags);
            return true;
        }
    }
}

function createImageElement(input, id) {
    var parentNode = input.parentNode;
    var img = document.createElement('img');

    var name = document.getElementById('title').value.trim();

    img.setAttribute('id', id);
    img.setAttribute('class', 'significantImage');
    img.setAttribute('alt', 'Immagine inserita per l\'opera ' + name);
    img.setAttribute('src', '');

    parentNode.appendChild(img);
}

function checkInputArtworkImage(event) {
    var input = document.getElementById('imageUpload');
    var checkImage = checkArtworkImage(input, false);

    if (checkImage) {
        if (window.FileReader) {
            if (!document.getElementById('uploadedImage')) {
                createImageElement(input, 'uploadedImage');
            }

            var target = event.target || window.event.srcElement;
            var fileReader = new FileReader();

            fileReader.onload = function () {
                document.getElementById('uploadedImage').src = fileReader.result;
            }
            fileReader.readAsDataURL(target.files[0]);
        } else {
            // Supporto per IE9
            if (!document.getElementById('uploadedImageIE')) {
                createImageElement(input, 'uploadedImageIE');
            }

            input.select();
            input.blur();

            var imagePreviewIE = document.getElementById('uploadedImageIE');
            imagePreviewIE.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = input.value;
        }
    } else {
        resetImage(false);
    }

    return checkImage;
}

function checkModifyArtworkImage(event) {
    var input = document.getElementById('imageUpload');
    var checkImage = true;

    if (input.value.trim() !== '') {
        checkImage = checkArtworkImage(input, true);

        if (checkImage) {
            if (window.FileReader) {
                if (!document.getElementById('uploadedImage')) {
                    createImageElement(input, 'uploadedImage');
                }

                var target = event.target || window.event.srcElement;
                var fileReader = new FileReader();

                fileReader.onload = function () {
                    document.getElementById('uploadedImage').src = fileReader.result;
                }
                fileReader.readAsDataURL(target.files[0]);
            } else {
                // Supporto per IE9
                var nameIE = document.getElementById('title').value.trim();
                var previousImage = document.getElementById('uploadedImage');

                if (previousImage) {
                    previousImage.setAttribute('id', 'uploadedImageIE');
                    previousImage.setAttribute('class', 'significantImage');
                    previousImage.setAttribute('alt', 'Immagine inserita per l\'opera ' + nameIE);
                    previousImage.setAttribute('src', '');
                }

                if (!document.getElementById('uploadedImageIE')) {
                    createImageElement(input, 'uploadedImageIE');
                }

                input.select();
                input.blur();

                var imagePreviewIE = document.getElementById('uploadedImageIE');
                imagePreviewIE.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = input.value;
            }
        } else {
            resetImage(false);
        }
    }

    return checkImage;
}

function resetImage(isModify) {
    resetArtworkErrors();

    var input = document.getElementById('imageUpload');
    var image = document.getElementById('uploadedImage');
    var imageIE = document.getElementById('uploadedImageIE');

    if (isModify) {
        if (imageIE) {
            imageIE.parentElement.removeChild(imageIE);
        }

        var previousImage = document.getElementById('previousImage');

        if (!image) {
            createImageElement(input, 'uploadedImage');
        }

        document.getElementById('uploadedImage').src = previousImage.value;
    } else {
        if (image) {
            image.parentElement.removeChild(image);
        }
        if (imageIE) {
            imageIE.parentElement.removeChild(imageIE);
        }
    }

    var tags = isModify ? 5 : 3;

    for (var i = 0; i < input.parentElement.children.length; ++i) {
        if (input.parentElement.children[i].tagName.toLowerCase() === 'img') {
            tags++;
        }
    }

    removeError(input, tags);
}

/* CONTROLLI E GESTIONE DEGLI EVENTI */
function checkEventTitle(input) {
    var title = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,\\-:()\\s]+$');
    
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
        addError(beginDateInput, 'Non è possibile inserire una data di inizio evento precedente alla data odierna', 3);
        return false;
    } else if (beginDate > upperBound) {
        addError(beginDateInput, 'Non è possibile inserire una data di inizio evento successiva a tre anni dalla data odierna', 3);
        return false;
    } else {
        removeError(beginDateInput, 3);
        return true;
    }
}

function checkDateComparison(beginDateInput, endDateInput) {
    var beginDate = getDateFromString(beginDateInput.value.trim());
    var endDate = getDateFromString(endDateInput.value.trim());

    var durationBound = getDateFromString(beginDateInput.value.trim());
    durationBound = addMonths(durationBound, 6);

    if (beginDate > endDate) {
        addError(endDateInput, 'Non è possibile inserire una data di fine evento precendente alla data di inizio evento', 3);
        return false;
    } else if (endDate > durationBound) {
        addError(endDateInput, 'Non è possibile inserire un evento che abbia una durata superiore ai sei mesi', 3);
        return false;
    } else {
        removeError(endDateInput, 3);
        return true;
    }
}

function checkEventType(input) {
    var type = input.options[input.selectedIndex].value.trim();
    
    if (type !== 'Mostra' && type !== 'Conferenza') {
        addError(input, 'La tipologia dell\'evento deve essere Mostra o Conferenza', 2);
        return false;
    } else {
        removeError(input, 2);
        return true;
    }
}

function checkEventManager(input) {
    var manager = input.value.trim();
    var pattern = new RegExp('^[A-zÀ-ú0-9\'`.:(\\-)\\s]+$');

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
    var pattern = new RegExp('^[A-zÀ-ú0-9\'`!.,\\-:()\\s]+$');

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
    var pattern = new RegExp('^[A-zÀ-ú\'\\-`.\\s]+$');
    
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
    var pattern = new RegExp('^[A-zÀ-ú\'\\-`.\\s]+$');

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
        addError(input, 'Non è possibile inserire una data di nascita vuota', 3);
        return false;
    } else if (!checkDateFormat(date)) {
        addError(input, 'La data di nascita non rispetta il formato richiesto', 3);
        return false;
    } else if (!checkDateValidity(date)) {
        addError(input, 'La data di nascita inserita non è valida', 3);
        return false;
    } else {
        var birthDate = getDateFromString(date);
        var lowerBound = new Date(parseInt('1900'), parseInt('00'), parseInt('01'));
        var upperBound = new Date(parseInt('2006'), parseInt('11'), parseInt('31'));

        if (birthDate < lowerBound) {
            addError(input, 'Non è possibile inserire una data di nascita precedente al 01-01-1900', 3);
            return false;
        } else if (birthDate > upperBound) {
            addError(input, 'Non è possibile inserire una data di nascita successiva al 31-12-2006', 3);
            return false;
        } else {
            removeError(input, 3);
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
    var pattern = new RegExp('^[a-zA-Z0-9.!#$%&\'*+^_`{|}~\\-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$');

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
    var pattern = new RegExp('^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!#$%&\'*+^_`\\-{|}~@]).*$');

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
    var pattern = new RegExp('^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!#$%&\'*+^_`\\-{|}~@]).*$');
    
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

/* CONTROLLI E GESTIONE DELLA RICERCA */
function checkSearchFilter(input) {
    var type = input.options[input.selectedIndex].value.trim();

    if (type !== 'Opera' && type !== 'Evento') {
        addSearchError(input, 'La tipologia della ricerca deve essere Opera od Evento');
        return false;
    } else {
        removeSearchError(input);
        return true;
    }
}

function checkSearchText(input) {
    var search = input.value.trim();

    if (search.length > 64) {
        addSearchError(input, 'Il testo ricercato non può essere più lungo di 64 caratteri');
        return false;
    } else {
        removeSearchError(input);
        return true;
    }
}

/* RIMOZIONE DEGLI ERRORI IN RESET */
function resetArtworkErrors() {
    var author = document.getElementById('author');
    var title = document.getElementById('title');
    var description = document.getElementById('operaDescriptionArea');
    var years = document.getElementById('years');
    var style = document.getElementById('style');
    var technique = document.getElementById('technique');
    var material = document.getElementById('material');
    var dimensions = document.getElementById('dimensions');
    var loanNo = document.getElementById('loanNo');

    removeError(author, 2);
    removeError(title, 2);
    removeError(description, 2);
    removeError(years, 3);
    removeError(style, 2);
    removeError(technique, 2);
    removeError(material, 2);
    removeError(dimensions, 3);
    removeRadioError(loanNo);
}

function resetEventErrors() {
    var title = document.getElementById('title');
    var description = document.getElementById('eventDescriptionArea');
    var beginDate = document.getElementById('beginDate');
    var endDate = document.getElementById('endDate');
    var type = document.getElementById('type');
    var manager = document.getElementById('manager');

    removeError(title, 2);
    removeError(description, 2);
    removeError(beginDate, 3);
    removeError(endDate, 3);
    removeError(type, 2);
    removeError(manager, 2);
}

function resetReviewErrors() {
    var title = document.getElementById('title');
    var description = document.getElementById('reviewDescriptionArea');

    removeError(title, 2);
    removeError(description, 2);
}

function resetUserErrors() {
    var name = document.getElementById('name');
    var surname = document.getElementById('surname');
    var date = document.getElementById('date');
    var sexA = document.getElementById('sexA');
    var email = document.getElementById('email');
    var oldPassword = document.getElementById('oldPassword');
    var newPassword = document.getElementById('newPassword');
    var confirmPassword = document.getElementById('repetePassword');

    removeError(name, 2);
    removeError(surname, 2);
    removeError(date, 3);
    removeRadioError(sexA);
    removeError(email, 2);
    removeError(oldPassword, 2);
    removeError(newPassword, 3);
    removeError(confirmPassword, 2);
}

function resetRegistrationErrors() {
    var name = document.getElementById('name');
    var surname = document.getElementById('surname');
    var date = document.getElementById('date');
    var sexA = document.getElementById('sexA');
    var email = document.getElementById('email');
    var username = document.getElementById('username');
    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('repetePassword');

    removeError(name, 2);
    removeError(surname, 2);
    removeError(date, 3);
    removeRadioError(sexA);
    removeError(email, 2);
    removeError(username, 2);
    removeError(password, 3);
    removeError(confirmPassword, 2);
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
    if (isInsert || image.value.trim() !== '') {
        imageResult = checkArtworkImage(image, !isInsert);
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

function searchFormValidation() {
    var filter = document.getElementById('filterSearch');
    var search = document.getElementById('searchText');

    var filterResult = checkSearchFilter(filter);
    var searchResult = checkSearchText(search);

    var stylesheet = document.styleSheets[0];
    var rules = stylesheet.cssRules[stylesheet.cssRules.length - 1];
    var rule = rules.cssRules[0];

    if (filterResult && searchResult) {
        rule.style.height = '12.8em';
    } else {
        rule.style.height = '14.5em';
    }

    return filterResult && searchResult;
}
