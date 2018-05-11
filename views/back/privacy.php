<?php

namespace back;

use jennifer\view\ViewInterface;
use thedaysoflife\model\Admin;
use thedaysoflife\sys\Configs;
use thedaysoflife\view\ViewBack;

class privacy extends ViewBack implements ViewInterface
{
    protected $title = "Dashboard :: Privacy";
    protected $contentTemplate = "privacy";

    public function __construct(Admin $admin = null)
    {
        parent::__construct();
        $this->admin = $admin ? $admin : new Admin();
    }

    public function prepare()
    {
        $tag = "privacy";
        $info = $this->admin->getInfoByTag($tag);
        $this->data = ["tag" => $tag, "info" => $info];
        $this->addMetaFile(Configs::SITE_URL . "/plugins/ckeditor/ckeditor.js");
    }
}