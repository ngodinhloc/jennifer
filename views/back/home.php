<?php

namespace back;

use jennifer\html\jobject\ClockPicker;
use jennifer\html\jobject\ColorPicker;
use jennifer\html\jobject\DatePicker;
use jennifer\html\jobject\FileUploader;
use jennifer\html\jobject\QRCode;
use jennifer\html\jobject\Signature;
use jennifer\view\ViewInterface;
use thedaysoflife\model\Admin;
use thedaysoflife\view\ViewBack;

class home extends ViewBack implements ViewInterface
{
    protected $title = "Dashboard";
    protected $contentTemplate = "home";

    public function __construct(Admin $admin = null)
    {
        parent::__construct();
        $this->admin = $admin ? $admin : new Admin();
    }

    public function prepare()
    {
        $this->admin->testJoin();
        $this->admin->testTable();

        $datePicker = new DatePicker(["id" => "date-picker"],
            ["value" => "22/04/2017",
                "startDate" => "",
                "endDate" => "",
                "autoClose" => true]);
        $clockPicker = new ClockPicker(["id" => "clock-picker"], ["value" => "10:00", "autoClose" => true]);
        $colorPicker = new ColorPicker(["id" => "color-picker"], ["value" => "#ffffff"]);
        $fileUploader = new FileUploader(["id" => "file-uploader"], ["dragText" => "Drag & Drop or",
            "buttonText" => "Browse Files",
            "limit" => 5,
            "maxSize" => 5,
            "fileMaxSize" => 1,
            "fileExtensions" => "'jpeg', 'jpg', 'png', 'gif'",
            "controller" => "ControllerFileUploader",
            "uploadAction" => "ajaxUpload",
            "removeAction" => "ajaxRemove"]);
        $signature = new Signature(["id" => "signature"], ["height" => 150,
            "jsonValue" => '{"lines":[[[164,43.73],[164,44.73],[163,45.73],[163,46.73],[162,51.73],[157,55.73],[154,57.73],[140,68.73],[129,72.73],[119,81.73],[104,85.73],[90,87.73],[77,89.73],[62,91.73],[54,92.73],[51,92.73],[46,92.73],[45,91.73],[45,90.73],[45,89.73],[44,85.73],[44,81.73],[44,78.73],[44,75.73],[49,71.73],[51,64.73],[53,62.73],[61,57.73],[64,56.73],[75,49.73],[85,44.73],[99,43.73],[107,40.73],[116,40.73],[117,40.73],[118,40.73],[119,42.73],[119,44.73],[120,46.73],[120,50.73],[120,52.73],[120,55.73],[120,62.73],[118,68.73],[112,79.73],[108,88.73],[98,98.73],[93,105.73],[82,117.73],[76,123.73],[71,127.73],[68,129.73],[66,129.73],[65,130.73],[65,129.73],[66,125.73],[67,116.73],[71,109.73],[77,103.73],[87,91.73],[91,88.73],[99,81.73],[106,76.73],[109,75.73],[110,75.73],[111,75.73],[111,77.73],[111,86.73],[111,96.73],[110,103.73],[109,111.73],[106,117.73],[106,123.73],[105,128.73],[105,130.73],[106,130.73],[108,130.73],[111,127.73],[120,117.73],[127,105.73],[135,95.73],[144,82.73],[157,65.73],[161,58.73],[163,48.73],[164,47.73],[164,48.73],[164,50.73],[163,58.73],[162,65.73],[161,73.73],[161,78.73],[161,81.73],[161,86.73],[161,87.73],[162,87.73],[168,87.73],[177,85.73],[193,78.73],[204,71.73],[213,60.73],[231,44.73],[234,40.73],[244,27.73],[247,22.73],[247,21.73],[246,25.73],[242,27.73],[233,42.73],[226,52.73],[216,67.73],[212,76.73],[205,88.73],[204,95.73],[204,96.73],[204,99.73],[207,99.73],[210,99.73],[217,99.73],[226,99.73],[233,95.73],[238,92.73],[244,90.73],[245,90.73],[247,89.73],[247,88.73],[247,89.73],[247,90.73],[247,92.73]]]}']);
        $qrcode = new QRCode(["id" => "qrcode"], ["text" => "www.thedaysoflife.com"]);
        $qrcode1 = new QRCode(["id" => "qrcode1"], ["text" => "www.thedaysoflife.com"]);
        $this->data = ["datePicker" => $datePicker->render(),
            "clockPicker" => $clockPicker->render(),
            "colorPicker" => $colorPicker->render(),
            "fileUploader" => $fileUploader->render(),
            "signature" => $signature->render(),
            "qrcode" => $qrcode->render(),
            "qrcode1" => $qrcode1->render(),
        ];
        $this->registerMetaFiles($datePicker);
        $this->registerMetaFiles($clockPicker);
        $this->registerMetaFiles($colorPicker);
        $this->registerMetaFiles($fileUploader);
        $this->registerMetaFiles($signature);
        $this->registerMetaFiles($qrcode);
    }
}