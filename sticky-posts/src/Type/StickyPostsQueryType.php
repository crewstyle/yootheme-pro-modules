<?php

use YOOtheme\Builder\Wordpress\Source\Helper;
use YOOtheme\Builder\Wordpress\Source\Type\CustomPostQueryType;
use YOOtheme\Str;
use function YOOtheme\trans;

class StickyPostsQueryType
{
    /**
     * @param \WP_Post_Type $type
     *
     * @return array
     */
    public static function config(\WP_Post_Type $type)
    {
        $base = Str::camelCase(Helper::getBase($type), true);
        $field = Str::camelCase(['sticky', Helper::getBase($type)]);

        $config = CustomPostQueryType::config($type)['fields']["custom{$base}"];

        return [
            'fields' => [
                $field => array_replace_recursive($config, [
                    'metadata' => [
                        'label' => trans('Sticky %post_type%', ['%post_type%' => $type->label]),
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => ['post_type' => $type->name],
                        ],
                    ],
                ]),
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        $stickies = get_option('sticky_posts');

        if (empty($stickies)) {
            return;
        }

        $args += [
            'post__in' => $stickies,
        ];

        return CustomPostQueryType::resolvePosts($root, $args);
    }
}
