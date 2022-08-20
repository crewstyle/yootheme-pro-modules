<?php

use YOOtheme\Config;
use function YOOtheme\app;
use function YOOtheme\trans;

class MenuItemsType
{
    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Title'),
                        'filters' => ['limit'],
                    ],
                ],
                'image' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Image'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::data',
                    ],
                ],
                'subtitle' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Subtitle'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::data',
                    ],
                ],
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Link'),
                    ],
                ],
                'active' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => trans('Active'),
                    ],
                ],
            ],
            'metadata' => [
                'type' => true,
                'label' => trans('Menu Item'),
            ],
        ];
    }

    public static function data($item, $args, $context, $info)
    {
        // Get menus
        $menu_cache = (array) get_terms([
            'object_ids' => $item->ID,
            'taxonomy'   => 'nav_menu',
            'hide_empty' => false,
            'orderby'    => 'name',
        ]);

        return isset($menu_cache[$info->fieldName]) ? $menu_cache[$info->fieldName] : '';
    }
}
