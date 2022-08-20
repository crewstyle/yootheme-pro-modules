<?php

use function YOOtheme\trans;

class MenuItemsQueryType
{
    /**
     * @return array
     */
    public static function config()
    {
        $menus = get_terms([
            'taxonomy'   => 'nav_menu',
            'hide_empty' => false,
            'orderby'    => 'name',
        ]);

        $select = [];

        foreach($menus as $menu) {
            $select[$menu->name] = $menu->term_id;
        }

        return [
            'fields' => [
                'customMenuItems' => [
                    'type' => [
                        'listOf' => 'MenuItems',
                    ],

                    'args' => [
                        'id' => [
                            'type' => 'Int',
                        ],
                        'parent' => [
                            'type' => 'String',
                        ],
                        'ids' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                    ],

                    'metadata' => [
                        'label'  => trans('Custom Menu Items'),
                        'group'  => 'Custom',
                        'fields' => [
                            'id' => [
                                'label'        => trans('Select Menu'),
                                'type'         => 'select',
                                'defaultIndex' => 0,
                                'options'      => $select,
                            ],
                        ],
                    ],

                    'extensions' => [
                        'call' => __CLASS__.'::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        return wp_get_nav_menu_items($args['id']);
    }
}
