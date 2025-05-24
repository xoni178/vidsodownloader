<?php

namespace App\Core;

class Response
{
    /**
     * @param string $filePath - file path of the file that will load views
     * 
     * @param array $values - values to pass down to view
     */
    public static function view(string $fileName, array $values = []): void
    {

        $view =  VIEW_FILES_DIR . "/" . $fileName . ".php";
        require  VIEW_FILES_DIR . "/" . "layout.php";
    }

    public static function plainText(string $text)
    {
        echo $text;
    }
}
