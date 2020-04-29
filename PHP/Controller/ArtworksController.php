<?php

require_once('Repository/ArtworksRepository.php');

class ArtworksController {
    private $artworks;

    public function __construct() {
        $this->artworks = new ArtworksRepository();
    }

    public function getArtworksCount() {
        return mysqli_fetch_assoc($this->artworks->getArtworksCount());
    }

    public function getArtworks($offset) {
        $result_set = $this->artworks->getArtworks($offset);
        $id_ref = "o";
        $button_ref = "buttonBack";
        $counter = 1;
        $content = "";
        while($row = mysqli_fetch_assoc($result_set)) {
            $content .= "
                <dt id=\"$id_ref . '' . $counter\">
                     <a href=\"ContenutoSingolo.php?type=opera&id=" . $row["ID"] . "\" aria-label=\"Vai all'opera\">" . $row["Titolo"] . "</a>
                </dt>
                <dd>
                    <a href=\"#" . ($result_set->num_rows == $counter ? $button_ref : $id_ref . ($counter+1)) . "\" class=\"skipInformation\" aria-label=\"Salta l'opera\">Salta l'opera</a>
    
                    <p>
                        Nome autore: " . $row["Autore"] . "
                    </p>
                    
                    <p>
                        Tecnica: " . $row["Tecnica"] . "
                    </p>

                    <p>
                        Data: " . $row["Anni"] . "
                    </p>
                    
                    <p>
                        Data: " . $row["Immagine"] . "
                    </p>
                    
                    <img alt=\"Immagine dell'opera\" src=\"../" . $row["Immagine"] . "\"/>
                </dd>
            ";
            $counter++;
        }
        return $content;
    }
}