<?php

require_once ('Repository/ArtworksRepository.php');
require_once ('Utilities/FileUtilities.php');

class ArtworksController {
    private $artworks;
    private $fileUtilities;

    private function checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions) {
        $message = '';

        if (strlen($author) === 0) {
            $message .= '[L\'autore dell\'opera non può essere vuoto]';
        }

        if (strlen($title) === 0) {
            $message .= '[Il titolo dell\'opera non può essere vuoto]';
        } elseif (strlen($title) < 3) {
            $message .= '[Il titolo dell\'opera deve essere lungo almeno 3 caratteri]';
        }

        if (strlen($description) === 0) {
            $message .= '[La descrizione dell\'opera non può essere vuota]';
        } elseif (strlen($description) < 30) {
            $message .= '[La descrizione dell\'opera deve essere lunga almeno 30 caratteri]';
        }

        if (strlen($years) === 0) {
            $message .= '[La datazione dell\'opera non può essere vuota]';
        }

        if ($style !== 'Scultura' && $style !== 'Dipinto') {
            $message .= '[Lo stile dell\'opera non può essere diverso dalle scelte proposte]';
        }

        if($technique != NULL) {
            if (strlen($technique) === 0) {
                $message .= '[La tecnica dell\'opera non può essere vuota]';
            }
        }

        if($material != NULL) {
            if (strlen($material) === 0) {
                $message .= '[Il materiale dell\'opera non può essere vuoto]';
            }
        }

        if (strlen($dimensions) === 0) {
            $message .= '[La dimensione dell\'opera non può essere vuota]';
        }

        if (!FileUtilities::isSelected()) {
            $message .= '[L\'immagine non è stata selezionata]';
        } elseif (!FileUtilities::isOneAndOnlyOneSelected()) {
            $message .= '[Non è possibile selezionare più di un\'immagine]';
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
        $message = $this->checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions);
        //TODO: Sistemare loan come intero??
        if ($message === '') {
            if($style === 'Dipinto') {
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
                        <dd>
                            ' . $row['Autore'] . '
                        </dd>
                        
                        <dt>
                            Stile: 
                        </dt>
                        <dd>
                            ' . $row['Stile'] . '
                        </dd>
    
                        <dt>
                            Data: 
                        </dt>
                        <dd>
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
                        <dd>
                            ' . $row['Autore'] . '
                        </dd>
                        
                        <dt>
                            Stile: 
                        </dt>
                        <dd>
                            ' . $row['Stile'] . '
                        </dd>
    
                        <dt>
                            Data: 
                        </dt>
                        <dd>
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
        $message = $this->checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions);

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
