<?php

namespace Jennifer\Html;

use Jennifer\Html\JObject\ClockPicker;
use Jennifer\Html\JObject\ColorPicker;
use Jennifer\Html\JObject\DatePicker;
use Jennifer\Html\JObject\FileUploader;

/**
 * Class Jquery
 * @package Jennifer\Html
 */
class Jquery
{
    public static function colorPicker($attr, $data)
    {
        $colorPicker = new ColorPicker($attr, $data);

        return $colorPicker->render();
    }

    public static function datePicker($attr, $data)
    {
        $datePicker = new DatePicker($attr, $data);

        return $datePicker->render();
    }

    public static function clockPicker($attr, $data)
    {
        $clockPicker = new ClockPicker($attr, $data);

        return $clockPicker->render();
    }

    public function fileUploader($attr, $data)
    {
        $fileUploader = new FileUploader($attr, $data);

        return $fileUploader;
    }
}