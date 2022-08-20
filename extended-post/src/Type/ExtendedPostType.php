<?php

use function YOOtheme\trans;

class ExtendedPostType
{
    public static function config()
    {
        return [
            'fields' => [
                'orientation' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Featured Image Orientation'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__.'::data',
                    ],
                ],
            ]
        ];
    }

    public static function data($obj, $args, $context, $info)
    {
        // Get thumb details
        $thumb_id = get_post_thumbnail_id($obj->ID);
        $thumb    = wp_get_attachment_image_src($thumb_id, 'full', false);

        // Get width and height
        $width  = $thumb[1];
        $height = $thumb[2];

        if ($width < $height) {
            return 'portrait';
        } else if ($width === $height) {
            return 'square';
        }

        return 'landscape';
    }
}
