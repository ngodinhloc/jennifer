<?php

namespace Jennifer\View;

interface ViewInterface
{
    /**
     * Prepare view data before calling render()
     * All view class must implement this method to prepare data and meta
     * @return \Jennifer\View\ViewInterface
     */
    public function prepare();

    /**
     * Render view to html
     * This function is already implemented in \view\Base so no need to implement in view classes
     */
    public function render();
}