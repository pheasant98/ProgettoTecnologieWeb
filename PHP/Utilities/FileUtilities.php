<?php

class FileUtilities {
    private $base_path;
    private $accepted_extensions;
    private $extension;
    private $path;

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

        if (move_uploaded_file($_FILES['image']['tmp_name'], $this->path) === false) {
            $this->path = '';
        }

        return $this->path !== '';
    }

    public function getPath() {
        return $this->path !== '' ? substr($this->path, 3) : false;
    }
}
