<?php

class FileUtilities {
    private $base_path;
    private $accepted_extensions;
    private $extension;
    private $path;

    private function resizeImage($file_name, $extension) {
        $maxDim = 400;
        list($width, $height, $type, $attr) = getimagesize($file_name);

        if ($width > $maxDim || $height > $maxDim) {
            $target_filename = $file_name;
            $ratio = $width / $height;

            if ($ratio > 1) {
                $new_width = $maxDim;
                $new_height = $maxDim / $ratio;
            } else {
                $new_width = $maxDim * $ratio;
                $new_height = $maxDim;
            }

            $src = imagecreatefromstring(file_get_contents($file_name));
            $dst = imagecreatetruecolor($maxDim, $maxDim);

            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefill($dst, 0, 0, $white);

            if ($new_width > $new_height) {
                imagecopyresampled($dst, $src, 0, ($maxDim - $new_height) / 2, 0, 0, $new_width, $new_height, $width, $height);
            } elseif ($new_height > $new_width) {
                imagecopyresampled($dst, $src, ($maxDim - $new_width) / 2, 0, 0, 0, $new_width, $new_height, $width, $height);
            } else {
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            }
            imagedestroy($src);

            if ($extension === 'png') {
                imagepng($dst, $target_filename);
            } elseif ($extension === 'jpeg') {
                imagejpeg($dst, $target_filename);
            }
            imagedestroy($dst);
        }
    }

    public function __construct() {
        $this->base_path = '../Images/Uploaded/';
        $this->accepted_extensions = array('jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'png' => 'image/png');
        $this->extension = '';
        $this->path = '';
    }

    public function __destruct() {
        unset($this->base_path);
        unset($this->accepted_extensions);
        unset($this->extension);
        unset($this->path);
    }

    public static function isEmpty() {
        return $_FILES['image']['name'] === '' || $_FILES['image']['size'] === '';
    }

    public static function isSelected() {
        return isset($_FILES['image']['error']);
    }

    public static function isOneAndOnlyOneSelected() {
        return !is_array($_FILES['image']['error']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE;
    }

    public static function isSizeBounded() {
        return $_FILES['image']['error'] !== UPLOAD_ERR_INI_SIZE && $_FILES['image']['error'] !== UPLOAD_ERR_FORM_SIZE;
    }

    public static function isUploaded() {
        return $_FILES['image']['error'] === UPLOAD_ERR_OK;
    }

    public static function isCorrectSized() {
        return $_FILES['image']['size'] <= 512000;
    }

    public function isCorrectExtensioned() {
        $file_info = new finfo(FILEINFO_MIME_TYPE);
        $this->extension = array_search($file_info->file($_FILES['image']['tmp_name']), $this->accepted_extensions,true);

        if ($this->extension === false) {
            $this->extension = '';
        }

        return $this->extension !== '';
    }

    public function isUniqueRenamed() {
        if ($this->extension === '') {
            return false;
        }

        $file_sha = sha1_file($_FILES['image']['tmp_name']);

        while (file_exists($this->path = sprintf($this->base_path . '%s.%s', $file_sha, $this->extension))) {
            $file_sha .= '_';
        }

        $this->resizeImage($_FILES['image']['tmp_name'], $this->extension);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $this->path) === false) {
            $this->path = '';
        }

        return $this->path !== '';
    }

    public function getPath() {
        return $this->path !== '' ? substr($this->path, 3) : false;
    }
}
