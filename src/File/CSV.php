<?php

namespace Jennifer\File;

use Jennifer\File\Exception\CSVException;

class CSV extends File implements FileInterface
{
    /** @var array */
    protected $header;

    /** @var array data */
    protected $data;

    /**
     * CSV constructor.
     *
     * @param string $file location
     * @param array $data data
     * @param array $header header
     */
    public function __construct1($file = null, $data = null, $header = [])
    {
        parent::__construct($file);
        $this->data = $data;
        $this->header = $header;
    }

    /**
     * Get all values of a column
     *
     * @param string|int $column column name or index
     * @return array
     * @throws \Jennifer\File\Exception\CSVException
     */
    public function getColumn($column)
    {
        $index = $column;
        if (empty($this->data)) {
            throw new CSVException(CSVException::ERROR_EMPTY_DATA);
        }
        if (is_string($column)) {
            $index = $this->findColumnIndex($column);
            if ($index === false) {
                throw new CSVException(CSVException::ERROR_INVALID_COLUMN_NAME . ": " . $column);
            }
        }

        $result = [];
        foreach ($this->data as $row) {
            $result[] = $row[$index];
        }

        return $result;
    }

    /**
     * @param string $column column name
     * @return bool|int
     * @throws \Jennifer\File\Exception\CSVException
     */
    public function findColumnIndex($column)
    {
        if (empty($this->header)) {
            throw new CSVException(CSVException::ERROR_EMPTY_HEADER);
        }
        foreach ($this->header as $index => $val) {
            if ($column == $val) {
                return $index;
            }
        }

        return false;
    }

    /**
     * Find rows which have column = value
     *
     * @param string|int $column column name or column index
     * @param string $value search for
     * @param bool $first true only get first row, false return all found rows
     * @return array empty if not found
     * @throws \Jennifer\File\Exception\CSVException
     */
    public function getRows($column, $value, $first = false)
    {
        $index = $column;
        if (empty($this->data)) {
            throw new CSVException(CSVException::ERROR_EMPTY_DATA);
        }
        if (is_string($column)) {
            $index = $this->findColumnIndex($column);
            if ($index === false) {
                throw new CSVException(CSVException::ERROR_INVALID_COLUMN_NAME . ": " . $column);
            }
        }
        $found = [];
        foreach ($this->data as $row) {
            if ($row[$index] == $value) {
                $found[] = $row;
                if ($first) {
                    return $row;
                }
            }
        }

        return $found;
    }

    /**
     * Put data from object data to file
     *
     * @param bool $includeHeader put header to file
     * @param bool $verbose verbose
     * @return $this
     * @throws \Jennifer\File\Exception\CSVException
     */
    public function save($includeHeader = false, $verbose = false)
    {
        if (!$this->file) {
            throw new CSVException(CSVException::ERROR_EMPTY_FILE_LOCATION);
        }
        if (empty($this->data)) {
            throw new CSVException(CSVException::ERROR_EMPTY_DATA);
        }

        if ($includeHeader && !empty($this->header)) {
            $this->data = array_merge([$this->header], $this->data);
        }

        $file = fopen($this->file, "w");
        if (!$file) {
            throw new CSVException(CSVException::ERROR_FAILED_TO_OPEN_FILE . ": " . $this->file);
        }

        foreach ($this->data as $index => $line) {
            if ($verbose) {
                echo "----- Writing line: $index \n";
            }
            try {
                fputcsv($file, $line, ',');
            } catch (\Exception $exception) {
                throw new CSVException(CSVException::ERROR_FAILED_TO_PUT_DATA . $exception->getMessage());
            }
        }

        fclose($file);

        return $this;
    }

    /**
     * Load data from file to object array
     *
     * @param bool $includeHeader header included at first row of data
     * @return \Jennifer\File\CSV
     * @throws \Jennifer\File\Exception\CSVException
     */
    public function load($includeHeader = false)
    {
        if (!file_exists($this->file)) {
            throw new CSVException(CSVException::ERROR_FILE_NOT_EXISTING . ": " . $this->file);
        }

        $file = fopen($this->file, "r");
        if (!$file) {
            throw new CSVException(CSVException::ERROR_FAILED_TO_OPEN_FILE . ": " . $this->file);
        }

        $data = [];
        try {
            while ($row = fgetcsv($file)) {
                $data[] = $row;
            }
        } catch (\Exception $exception) {
            throw new CSVException(CSVException::ERROR_FAILED_TO_LOAD_DATA . $exception->getMessage());
        }
        fclose($file);

        if ($includeHeader) {
            $this->header = $data[0];
            unset($data[0]);
        }
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file location
     * @return \Jennifer\File\CSV
     */
    public function setFile(string $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data data
     * @return \Jennifer\File\CSV
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param array $header header
     * @return \Jennifer\File\CSV
     */
    public function setHeader(array $header)
    {
        $this->header = $header;

        return $this;
    }
}
