<?php

class MenuItemsListener
{
    public static function initSource($source)
    {
        $source->objectType('MenuItems', MenuItemsType::config());
        $source->queryType(MenuItemsQueryType::config());
    }
}
