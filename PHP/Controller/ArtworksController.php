<?php

require_once ('Repository/ArtworksRepository.php');
require_once ('Utilities/FileUtilities.php');

class ArtworksController {
    private $artworks;
    private $fileUtilities;

    private function checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan) {
        $message = '';

        if (strlen($author) === 0) {
            $message .= '[Non è possibile inserire un autore vuoto]';
        } elseif (strlen($author) < 5) {
            $message .= '[Non è possibile inserire un autore più corto di 5 caratteri]';
        } elseif (strlen($author) > 64) {
            $message .= '[Non è possibile inserire un autore più lungo di 64 caratteri]';
        } elseif (!preg_match('/^[A-zÀ-ú\'\-`.\s]+$/', $author)) {
            $message .= '[L\'autore contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, spazi e i seguenti caratteri speciali \' - . `]';
        }

        if (strlen($title) === 0) {
            $message .= '[Non è possibile inserire un titolo vuoto]';
        } elseif (strlen($title) < 2) {
            $message .= '[Non è possibile inserire un titolo più corto di 2 caratteri]';
        } elseif (strlen($title) > 64) {
            $message .= '[Non è possibile inserire un titolo più lungo di 64 caratteri]';
        } elseif (!preg_match('/^[A-zÀ-ú0-9\'`!.,:(\-)\s]+$/', $title)) {
            $message .= '[Il titolo contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , : - ()]';
        }

        if (strlen($description) === 0) {
            $message .= '[Non è possibile inserire una descrizione vuota]';
        } elseif (strlen($description) < 2) {
            $message .= '[Non è possibile inserire una descrizione più corta di 30 caratteri]';
        } elseif (strlen($description) > 500) {
            $message .= '[Non è possibile inserire una descrizione più lunga di 500 caratteri]';
        }

        if (strlen($years) === 0) {
            $message .= '[Non è possibile inserire una datazione vuota]';
        } elseif (!intval($years)) {
            $message .= '[La datazione dell\'opera deve essere un numero intero]';
        } else {
             $formatted_date = DateTime::createFromFormat('Y', $years);
            if ($formatted_date === false) {
                $message .= '[La datazione deve contenere solo l\'anno]';
            } else {
                $years_number = intval($years);
                if ($years_number > date('Y') || $years_number < 1400) {
                    $message .= '[La datazione dell\'opera deve essere compresa tra il 1400 e l\'anno corrente]';
                }
            }
        }

        if ($style !== 'Sculture' && $style !== 'Dipinti') { //FIXME: Quando si sistema nel db si deve mettere Scultura e Dipinto
            $message .= '[Lo stile dell\'opera deve essere Scultura o Dipinto]';
        }

        if($technique != NULL) { //TODO: Sistemare anche controllo dopo aver fatto Javascript.
            if (strlen($technique) === 0) {
                $message .= '[Non è possibile inserire una tecnica vuota]';
            } elseif (strlen($technique) < 4) {
                $message .= '[Non è possibile inserire una tecnica più corta di 4 caratteri]';
            } elseif (strlen($technique) > 64) {
                $message .= '[Non è possibile inserire una tecnica più lunga di 64 caratteri]';
            } elseif (!preg_match('/^[A-zÀ-ú\'\-`\s]+$/', $technique)) {
                $message .= '[La tecnica contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, accenti, spazi e i seguenti caratteri speciali \' \ - `]';
            }
        }

        if($material != NULL) {
            if (strlen($material) === 0) {
                $message .= '[Non è possibile inserire un materiale vuoto]';
            } elseif (strlen($material) < 4) {
                $message .= '[Non è possibile inserire un materiale più corto di 4 caratteri]';
            } elseif (strlen($material) > 32) {
                $message .= '[Non è possibile inserire un materiale più lungo di 32 caratteri]';
            } elseif (!preg_match('/^[A-zÀ-ú\'\-`\s]+$/', $material)) {
                $message .= '[Il materiale contiene caratteri non consentiti. Quelli possibili sono lettere, anche accentate, spazi, \' \ - `]';
            }
        }

        if (strlen($dimensions) === 0) {
            $message .= '[Non è possibile inserire una dimensione vuota]';
        } elseif (!preg_match('/^([1-9][0-9]{0,2}|1000)x([1-9][0-9]{0,2}|1000)$/', $dimensions)) {
            $message .= '[La dimensione contiene caratteri non consentiti. Il formato consentito è specificato nel suggerimento]';
        }

        if ($loan !== 1 && $loan !== 0) {
            $message .= '[Il prestito deve essere scelto tra "Si" e "No"]';
        }

        if (!FileUtilities::isSelected()) {
            $message .= '[L\'immagine non è stata selezionata]';
        } elseif (!FileUtilities::isOneAndOnlyOneSelected()) {
            $message .= '[È necessario selezionare una ed una sola immagine]';
        } elseif (!FileUtilities::isSizeBounded()) {
            $message .= '[L\'immagine selezionata supera la dimensione massima consentita]';
        } elseif (!FileUtilities::isUploaded()) {
            $message .= '[L\'immagine non è stata caricata correttamente]';
        } elseif (!FileUtilities::isCorrectSized()) {
            $message .= '[L\'immagine caricata è una dimensione troppo elevata]';
        } elseif (!$this->fileUtilities->isCorrectExtensioned()) {
            $message .= '[L\'estensione dell\'immagine non è supportata]';
        } elseif (!$this->fileUtilities->isUniqueRenamed()) {
            $message .= '[Non è stato possibile generare un nome univoco per il file. Per favore rinominare il file]';
        }

        return $message;
    }

    public function __construct() {
        $this->artworks = new ArtworksRepository();
        $this->fileUtilities = new FileUtilities();
    }

    public function __destruct() {
        unset($this->artworks);
        unset($this->fileUtilities);
    }

    public function addArtwork($author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan, $user) {
        $message = $this->checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan);
        if ($message === '') {
            if($style === 'Dipinti') {
                if ($this->artworks->postPainting($author, $title, $description, $years, $technique, $dimensions, $loan, $this->fileUtilities->getPath(), $user)) {
                    $message = '<p class="success">L\'opera ' . $title . ' è stata inserita correttamente</p>';
                } else {
                    $message = '<p class="error">Errore nell\'inserimento dell\'opera ' . $title . '</p>';
                }
            } else {
                if ($this->artworks->postSculture($author, $title, $description, $years, $material, $dimensions, $loan, $this->fileUtilities->getPath(), $user)) {
                    $message = '<p class="success">L\'opera ' . $title . ' è stata inserita correttamente</p>';
                } else {
                    $message = '<p class="error">Errore nell\'inserimento dell\'opera ' . $title . '</p>';
                }
            }
        } else {
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message .= '</ul>';
        }

        return $message;
    }

    public function getSearchedArtworksCount($search) {
        $result_set = $this->artworks->getSearchedArtworksCount($search);
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
    }

    public function getArtworksCount() {
        $result_set = $this->artworks->getArtworksCount();
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
    }

    public function getArtworksCountByStyle($style) {
        $result_set = $this->artworks->getArtworksCountByStyle($style);
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
    }

    public function getSearchedArtworks($search, $offset) {
        $result_set = $this->artworks->getSearchedArtworks($search, $offset);

        $id = 'artwork';
        $button = 'buttonBack';
        $counter = 1;
        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <dt id="'. $id . $counter . '">
                     <a href="Opera.php?id=' . $row['ID'] . '" aria-label="Vai all\'opera">' . $row['Titolo'] . '</a>
                </dt>
                <dd>
                    <a href="#' . ($result_set->num_rows === $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta l\'opera">Salta l\'opera</a>
    
                    <dl>
                        <dt>
                            Nome autore: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Autore'] . '
                        </dd>
                        
                        <dt>
                            Stile: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Stile'] . '
                        </dd>
    
                        <dt>
                            Data: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Datazione'] . '
                        </dd>
                    </dl>
                    
                    <img alt="Immagine dell\'opera ' . $row['Titolo'] . '" src="../' . $row['Immagine'] . '"/>
                </dd>
            ';

            $counter++;
        }

        $result_set->free();

        return $content;
    }

    public function getArtworks($style, $offset) {
        if ($style === '') {
            $result_set = $this->artworks->getArtworks($offset);
        } else {
            $result_set = $this->artworks->getArtworksByStyle($style, $offset);
        }

        $id = 'artwork';
        $button = 'buttonBack';
        $counter = 1;
        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <dt id="' . $id . $counter . '">
                     <a href="Opera.php?id=' . $row['ID'] . '" aria-label="Vai all\'opera">' . $row['Titolo'] . '</a>
                </dt>
                <dd>
                    <a href="#' . ($result_set->num_rows === $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta l\'opera">Salta l\'opera</a>
    
                    <dl>
                        <dt>
                            Nome autore: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Autore'] . '
                        </dd>
                        
                        <dt>
                            Stile: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Stile'] . '
                        </dd>
    
                        <dt>
                            Data: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Datazione'] . '
                        </dd>
                    </dl>
                    
                    <img alt="Immagine dell\'opera ' . $row['Titolo'] . '" src="../' . $row['Immagine'] . '"/>
                </dd>
            ';

            $counter++;
        }

        $result_set->free();

        return $content;
    }

    public function getArtworksTitle($style, $offset) {
        if ($style === '') {
            $result_set = $this->artworks->getArtworks($offset);
        } else {
            $result_set = $this->artworks->getArtworksByStyle($style, $offset);
        }

        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <li>
                    <a href="Opera.php?id=' . $row['ID'] . '" aria-label="Vai all\'opera">' . $row['Titolo'] . '</a>
                    
                    <form class="userButton" action="EliminaContenuto.php" method="post" role="form">
                        <fieldset class="hideFieldset">
                            <legend class="hideLegend">Pulsanti di modifica ed eliminazione dell\'opera</legend>
                            
                            <input type="hidden" name="id" value="' . $row['ID'] . '"/>
                            <input type="hidden" name="type" value="Opera"/>
                            
                            <a class="button" href="ModificaOpera.php?id=' . $row['ID'] . '" title="Modifica dettagli opera" role="button" aria-label="Modifica dettagli opera">Modifica</a>
                            <input id="buttonConfirm" class="button" name="submit" type="submit" value="Rimuovi" role="button" aria-label="Rimuovi opera"/>
                        </fieldset>
                    </form>
                </li>
            ';
        }

        $result_set->free();

        return $content;
    }

    public function getArtwork($id) {
        $result_set = $this->artworks->getArtwork($id);
        $row = $result_set->fetch_assoc();
        $result_set->free();
        return $row;
    }

    public function updateArtwork($id, $author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan, $user) {
        $message = $this->checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan);

        if ($message === '') {
            if($style === 'Dipinto') {
                if ($this->artworks->updateArtwork($id, $author, $title, $description, $years, $technique, $material, $dimensions, $loan, $this->fileUtilities->getPath(), $user)) {
                    $message = '';
                } else {
                    $message = '<p class="error">Errore nell\'aggiornamento dell\'opera</p>';
                }
            } else {
                if ($this->artworks->updateArtwork($id, $author, $title, $description, $years, $technique, $material, $dimensions, $loan, $this->fileUtilities->getPath(), $user)) {
                    $message = '';
                } else {
                    $message = '<p class="error">Errore nell\'aggiornamento dell\'opera</p>';
                }
            }
        } else {
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message .= '</ul>';
        }

        return $message;
    }

    public function deleteArtwork($id) {
        $this->artworks->deleteArtwork($id);
    }
}

?>
