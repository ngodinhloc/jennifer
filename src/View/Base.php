<?php

namespace Jennifer\View;

use Jennifer\Auth\Authentication;
use Jennifer\Cache\CacheEngineFactory;
use Jennifer\Cache\CacheEngineInterface;
use Jennifer\Com\Common;
use Jennifer\Http\Request;
use Jennifer\IO\Output;
use Jennifer\Sys\Config;
use Jennifer\Template\Template;

/**
 * Class Base: Base view class: all view classes will extend this base class
 * @package Jennifer\View
 */
class Base
{
    /** @var Authentication */
    protected $authentication;
    /** @var  Template */
    protected $tpl;
    /** @var Output */
    protected $output;
    /** @var CacheEngineInterface */
    protected $cacheEngine;
    /** @var  Request */
    protected $request;
    /** @var  bool cache this view or not */
    protected $cache;
    /** @var array list of templates used in the view */
    protected $templates = [];
    /** @var bool|array required permission of the view */
    protected $requiredPermission = false;
    /** @var array store data that will be accessible from template */
    protected $data = [];
    /** @var array store meta data : userData , route, url, title, description, keyword, metaTags, metaFiles */
    protected $meta = [];
    /** @var array store para from uri */
    protected $para = [];
    /** @var array|bool user data */
    protected $userData = false;
    /** @var string route of the view */
    protected $route;
    /** @var  string url of the view */
    protected $url;
    /** @var  string title of the view */
    protected $title;
    /** @var  string description of the view */
    protected $description;
    /** @var  string keywords of the view */
    protected $keyword;
    /** @var array css, js */
    protected $metaFiles = ["header" => [], "footer" => []];
    /** @var array tags */
    protected $metaTags = ["header" => "", "footer" => ""];

    /**
     * Base constructor.
     * @param \Jennifer\Http\Request|null $request
     * @param \Jennifer\IO\Output|null $output
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    public function __construct(Request $request = null, Output $output = null)
    {
        $this->request = $request ?: new Request();
        $this->output = $output ?: new Output();
        $this->url = $this->request->uri;
        $this->authentication = new Authentication();
        $this->authentication->checkUserPermission($this->requiredPermission, "view");
        $this->userData = $this->authentication->getUserData();
        if (Config::getConfig("CACHE_ENGINE")) {
            $this->cacheEngine = CacheEngineFactory::createCacheEngine(Config::getConfig("CACHE_ENGINE"), Config::getConfig("CACHE_TIME"), Config::getConfig("CACHE_TIME"));
        }
        if ($this->cacheEngine && $this->cache) {
            $this->retrieveCache();
        }
    }

    /**
     * Check if there is valid cache
     * If there is cache than output the cache and exit
     * else : process to prepare data and render
     */
    protected function retrieveCache()
    {
        $cache = $this->cacheEngine->getCache($this->url);
        if ($cache) {
            $this->output->html($cache);
        }
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param array $templates
     * @return Base
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return Base
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get view data
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set a value of data
     * @param array name => var
     */
    public function setData($array = [])
    {
        foreach ($array as $name => $var) {
            $this->data["$name"] = $var;
        }
    }

    /**
     * Get user data
     * @return bool
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * Get the required permissions for this view
     */
    public function getRequiredPermission()
    {
        return $this->requiredPermission;
    }

    /**
     * Process URI para
     * @return $this;
     */
    public function processPara()
    {
        $paras = explode("/", str_replace($this->route, "", $this->request->uri));
        $this->para["id"] = $paras[0];

        return $this;
    }

    /**
     * Check if uri para exists then return value, else return false
     * @param $name
     * @return bool|mixed
     */
    public function hasPara($name)
    {
        return isset($this->para[$name]) ? $this->para[$name] : false;
    }

    /**
     * Add html code to header
     * @param array $tags
     */
    public function addMetaTags($tags)
    {
        foreach ($tags as $tag) {
            $this->metaTags["header"] .= $tag;
        }
    }

    /**
     * Register object meta files
     * @param \Jennifer\Html\JObject $object
     */
    public function registerMetaFiles($object)
    {
        $metaFiles = $object->getMetaFiles();
        $this->addMetaFiles($metaFiles);
    }

    /**
     * Add meta file
     * @param array $files
     */
    public function addMetaFiles($files)
    {
        foreach ($files as $file) {
            $ext = Common::getFileExtension($file);
            switch ($ext) {
                case "css":
                    array_push($this->metaFiles["header"], ["type" => $ext, "src" => $file]);
                    break;
                case "js":
                    array_push($this->metaFiles["footer"], ["type" => $ext, "src" => $file]);
                    break;
            }
        }
    }

    /**
     * Render this view
     * @param bool $compress
     * @throws \Jennifer\Cache\Exception\FileCacheException
     */
    public function render($compress = true)
    {
        $this->initMeta();
        $this->tpl = new Template($this->templates, $this->data, $this->meta);
        $html = $this->tpl->render($compress);
        // cache the whole view html
        if ($this->cache) {
            $this->cacheEngine->writeCache($this->url, $html);
        }

        $this->output->html($html);
    }

    /**
     * Initialise view meta data
     */
    protected function initMeta()
    {
        $this->initMetaTags();
        $this->meta = [
            "route" => $this->route,
            "title" => $this->title,
            "description" => $this->description,
            "keyword" => $this->keyword,
            "metaTags" => $this->metaTags,
            "userData" => (array)$this->userData,
        ];
    }

    /**
     * Initialise meta tags to html
     */
    protected function initMetaTags()
    {
        foreach ($this->metaFiles as $pos => $files) {
            if (!empty($files)) {
                array_unique($files);
                $tags = "";
                foreach ($files as $file) {
                    $tag = "";
                    switch ($file["type"]) {
                        case "css":
                            $tag = "<link rel='stylesheet' href='{$file["src"]}' type='text/css'/>";
                            break;
                        case "js":
                            $tag = "<script type='text/javascript' src='{$file["src"]}' ></script>";
                            break;
                    }
                    $tags .= $tag;
                }
                $this->metaTags[$pos] = $tags . $this->metaTags[$pos];
            }
        }
    }

    /**
     * Load required permission from database or set required permission on each view
     */
    protected function loadRequiredPermission()
    {

    }
}