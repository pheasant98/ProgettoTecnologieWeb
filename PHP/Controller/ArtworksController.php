<?php

require_once('Repository/ArtworksRepository.php');

class ArtworksController {
    private $artworks;

    private static function checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions) {
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

        return $message;
    }

    public function __construct() {
        $this->artworks = new ArtworksRepository();
    }

    public function __destruct() {
        unset($this->artworks);
    }

    public function addArtwork($author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan, $image, $user) {
        $message = ArtworksController::checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions);
        //TODO: Sistemare loan come intero
        if ($message === '') {
            if($style === 'Dipinto') {
                if ($this->artworks->postPainting($author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user)) {
                    $message = '<p class="success">Opera inserita correttamente</p>';
                } else {
                    $message = '<p class="error">Errore nell\'inserimento della nuova opera</p>';
                }
            } else {
                if ($this->artworks->postSculture($author, $title, $description, $years, $material, $dimensions, $loan, $image, $user)) {
                    $message = '<p class="success">Opera inserita correttamente</p>';
                } else {
                    $message = '<p class="error">Errore nell\'inserimento della nuova opera</p>';
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
    
                    <p>
                        Nome autore: ' . $row['Autore'] . '
                    </p>
                    
                    <p>
                        Stile: ' . $row['Stile'] . '
                    </p>

                    <p>
                        Data: ' . $row['Datazione'] . '
                    </p>
                    
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
                <dt id="'. $id . $counter . '">
                     <a href="Opera.php?id=' . $row['ID'] . '" aria-label="Vai all\'opera">' . $row['Titolo'] . '</a>
                </dt>
                <dd>
                    <a href="#' . ($result_set->num_rows === $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta l\'opera">Salta l\'opera</a>
    
                    <p>
                        Nome autore: ' . $row['Autore'] . '
                    </p>
                    
                    <p>
                        Stile: ' . $row['Stile'] . '
                    </p>

                    <p>
                        Data: ' . $row['Datazione'] . '
                    </p>
                    
                    <img alt="Immagine dell\'opera ' . $row['Titolo'] . '" src="../' . $row['Immagine'] . '"/>
                </dd>
            ';

            $counter++;
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

    public function updateArtwork($id, $author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan, $image, $user) {
        $message = ArtworksController::checkInput($author, $title, $description, $years, $style, $technique, $material, $dimensions);

        if ($message === '') {
            if($style === 'Dipinto') {
                if ($this->artworks->updateArtwork($id, $author, $title, $description, $years, $technique, $material, $dimensions, $loan, $image, $user)) {
                    $message = '<p class="success">Opera aggiornata correttamente</p>';
                } else {
                    $message = '<p class="error">Errore nell\'aggiornamento dell\'opera</p>';
                }
            } else {
                if ($this->artworks->updateArtwork($id, $author, $title, $description, $years, $technique, $material, $dimensions, $loan, $image, $user)) {
                    $message = '<p class="success">Opera aggiornata correttamente</p>';
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
}

?>
