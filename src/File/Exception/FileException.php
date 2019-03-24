<?php

namespace Jennifer\File\Exception;

class FileException extends \Exception
{
    const ERROR_FILE_NOT_EXISTING = 'File not existing. Please check file location';
    const ERROR_EMPTY_FILE_LOCATION = 'Empty file location.';
    const ERROR_FAILED_TO_OPEN_FILE = 'Failed to open file. Please check file location or permission';
}
