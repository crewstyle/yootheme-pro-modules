<?php

use YOOtheme\Builder\Source;

class ExtendedUserListener
{
    /**
     * @param Source $source
     */
    public static function initSource($source)
    {
        if (!class_exists('WPSEO_Options')) {
            return;
        }

        $source->objectType('User', ExtendedUserType::config());
    }
}
