<?php

require_once('Repository/ArtworksRepository.php');

class ArtworksController {
    private $artworks;

    public function __construct() {
        $this->artworks = new ArtworksRepository();
    }

    public function __destruct() {
        unset($this->artworks);
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

        return ' <h3 class="subtitle">' . $row['Titolo'] . '</h3>
                 <p id="operaImage">
                    <img alt="Immagine opera ' . $row['Titolo'] . '" src="../' . $row['Immagine'] . '"/>
                 </p>
                 
                 <dl>
                     <dt>
                         Autore:
                     </dt>
                     <dd>
                         ' . $row['Autore'] . '
                     </dd>
                    
                     <dt>
                        Data:
                     </dt>
                     <dd>
                         ' . $row['Anni'] . '
                     </dd>
                    
                     <dt>
                         Stile:
                     </dt>
                     <dd>
                         ' . $row['Stile'] . '
                     </dd>
                    
                     <dt>
                        Tecnica:
                     </dt>
                     <dd>
                         ' . $row['Tecnica'] . '
                     </dd>
        
                     <dt>
                        Materiale:
                     </dt>
                     <dd>
                         ' . $row['Materiale'] . '
                     </dd>
                     
                     <dt>
                        Dimensione:
                     </dt>
                     <dd>
                         ' . $row['Dimensioni'] . '
                     </dd>
                     
                     <dt>
                        In prestito:
                     </dt>
                     <dd>
                        ' . ($row['Prestito'] == 1 ? 'sì' : 'no') . '
                     </dd>
                 </dl>     
                 <p class="paragraph">
                     ' . $row['Descrizione'] . '
                 </p>
                ';
    }
}

?>
