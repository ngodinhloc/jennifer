<?php

namespace Jennifer\View;

use Jennifer\View\Exception\ViewException;

/**
 * Class ViewFactory: create view
 * @package Jennifer\View
 */
class ViewFactory
{
    /**
     * Create view from class name
     * @param $viewClass
     * @return \Jennifer\View\ViewInterface
     * @throws \Jennifer\View\Exception\ViewException
     */
    public function createView($viewClass)
    {
        $view = new $viewClass();
        if ($view) {
            return $view;
        }
        throw new ViewException(ViewException::ERROR_MSG_INVALID_VIEW);
    }
}