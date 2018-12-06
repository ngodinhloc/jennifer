<?php

namespace jennifer\view;

use jennifer\exception\RequestException;

/**
 * Class ViewFactory: create view
 * @package jennifer\view
 */
class ViewFactory {
    /**
     * Create view from class name
     * @param $viewClass
     * @return \jennifer\view\ViewInterface
     * @throws RequestException
     */
    public function createView($viewClass) {
        $view = new $viewClass();
        if ($view) {
            return $view;
        }
        throw new RequestException(RequestException::ERROR_MSG_INVALID_VIEW, RequestException::ERROR_CODE_INVALID_VIEW);
    }
}