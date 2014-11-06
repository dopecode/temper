<?php

namespace Dopecode\Temper;

class Temper
{

    private $path = null;

    /**
     * Create a temp file with given content.
     * 
     * @param string      $content Binary data to populate into file
     * @param string|null $path    Custom temp directory, or system default if null
     * @param string|null $prefix  Custom prefix for temp file to prevent collisions
     */
    public function __construct($content, $path = null, $prefix = null)
    {
        $this->path = tempnam($path, $prefix);
        $handle = fopen($this->path, 'w');
        fwrite($handle, $content);
        fclose($handle);
        register_shutdown_function('\Dopecode\Temper\Temper::shutdown', $this->path);
    }

    /**
     * Delete the temp file.
     * 
     * @return boolean
     */
    public function destroy()
    {
        return self::shutdown($this->path);
    }

    /**
     * Return the file system path of the temp file.
     * 
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * A globally accessible shutdown function to remove remaining temp files.
     * 
     * @param string $path
     */
    public static function shutdown($path)
    {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

}
