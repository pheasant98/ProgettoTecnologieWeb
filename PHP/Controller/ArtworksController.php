<?php

require_once('Repository/ArtworksRepository.php');

class ArtworksController {
    private $artworks;

    public function __construct() {
        $this->artworks = new ArtworksRepository();
    }

    public function getArtworksCount() {
        return mysqli_fetch_assoc($this->artworks->getArtworksCount())['Totale'];
    }

    public function getArtworksCountByStyle($style) {
        return mysqli_fetch_assoc($this->artworks->getArtworksCountByStyle($style))['Totale'];
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

        while($row = mysqli_fetch_assoc($result_set)) {
            $content .= '
                <dt id="'. $id . $counter . '">
                     <a href="ContenutoSingolo.php?type=opera&id=' . $row['ID'] . '" aria-label="Vai all\'opera">' . $row['Titolo'] . '</a>
                </dt>
                <dd>
                    <a href="#' . ($result_set->num_rows == $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta l\'opera">Salta l\'opera</a>
    
                    <p>
                        Nome autore: ' . $row['Autore'] . '
                    </p>
                    
                    <p>
                        Tecnica: ' . $row['Tecnica'] . '
                    </p>

                    <p>
                        Data: ' . $row['Anni'] . '
                    </p>
                    
                    <img alt="Immagine dell\'opera . (titolo)" src="../' . $row['Immagine'] . '"/>
                </dd>
            ';

            $counter++;
        }

        return $content;
    }
}

?>
