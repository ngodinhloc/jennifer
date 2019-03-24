<?php

namespace Jennifer\File\Exception;

class CSVException extends FileException
{
    const ERROR_FAILED_TO_LOAD_DATA = 'Failed to load csv data';
    const ERROR_FAILED_TO_PUT_DATA = 'Failed to put to csv file';
    const ERROR_EMPTY_DATA = 'Data is empty. Nothing to put/find';
    const ERROR_EMPTY_HEADER = 'Header is empty';
    const ERROR_INVALID_COLUMN_NAME = 'Invalid column name';
}
