<?php

use YOOtheme\Builder\Source;

class ExtendedPostListener
{
    /**
     * @param Source $source
     */
    public static function initSource($source)
    {
        $source->objectType('Post', ExtendedPostType::config());
    }
}
