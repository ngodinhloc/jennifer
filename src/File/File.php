<?php

namespace Jennifer\File;

abstract class File
{
    /** @var string file name */
    protected $file;

    public function __construct($file = null)
    {
        $this->file = $file;
    }

    public abstract function save();
}