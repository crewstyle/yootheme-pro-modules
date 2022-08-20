<?php

use WordPressPopularPosts\Helper;
use YOOtheme\Arr;
use YOOtheme\Builder\Wordpress\Source\Helper as SourceHelper;
use YOOtheme\Builder\Wordpress\Source\Type\CustomPostQueryType;
use YOOtheme\Str;
use function YOOtheme\trans;

class PopularPostsQueryType
{
    /**
     * @param \WP_Post_Type $type
     *
     * @return array
     */
    public static function config(\WP_Post_Type $type)
    {
        $base = Str::camelCase(SourceHelper::getBase($type), true);
        $field = Str::camelCase(['popular', SourceHelper::getBase($type)]);

        $config = CustomPostQueryType::config($type)['fields']["custom{$base}"];

        Arr::set($config, 'metadata.fields._order.fields', [
            'order' => [
                'label' => trans('Order'),
                'type' => 'select',
                'default' => 'views',
                'options' => [
                    trans('Comments') => 'comments',
                    trans('Total Views') => 'views',
                    trans('Average Daily Views') => 'avg',
                ],
            ],
            'order_direction' => [
                'label' => trans('Time Range'),
                'type' => 'select',
                'default' => 'monthly',
                'options' => [
                    trans('Last 24 hours') => 'daily',
                    trans('Last 7 days') => 'weekly',
                    trans('Last 30 days') => 'monthly',
                    trans('All-time') => 'all',
                ],
            ],
        ]);

        return [
            'fields' => [
                $field => array_replace_recursive($config, [
                    'metadata' => [
                        'label' => trans('Popular %post_type%', ['%post_type%' => $type->label]),
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
        $args += [
            'order' => 'views',
            'order_direction' => 'monthly',
        ];

        $now = new \DateTime(Helper::now(), wp_timezone());
        $range = $args['order_direction'];
        $order = $args['order'];

        SourceHelper::filterOnce('posts_where', static::postsWhereFn($range, $order));
        SourceHelper::filterOnce('posts_fields', static::postsFieldsFn($range, $order, $now));
        SourceHelper::filterOnce('posts_orderby', static::postsOrderbyFn($range, $order));
        SourceHelper::filterOnce('posts_groupby', static::postsGroupbyFn($range, $order));
        SourceHelper::filterOnce('posts_join', static::postsJoinFn($range, $order, $now));

        return CustomPostQueryType::resolvePosts($root, $args);
    }

    protected static function postsWhereFn($range, $order)
    {
        return function ($where) use ($range, $order) {
            if ($range === 'all' && $order === 'comments') {
                return $where . 'AND comment_count > 0';
            }

            return $where;
        };
    }

    protected static function postsFieldsFn($range, $order, $now)
    {
        return function ($fields) use ($range, $order, $now) {
            if ($range === 'all') {
                if ($order === 'avg') {
                    return "{$fields}, (v.pageviews/(IF (DATEDIFF('{$now->format(
                        'Y-m-d'
                    )}', MIN(v.day)) > 0, DATEDIFF('{$now->format(
                        'Y-m-d'
                    )}', MIN(v.day)), 1))) AS avg_views";
                }

                return $fields;
            }

            if ($order === 'views') {
                return "{$fields}, pageviews";
            }
            if ($order === 'avg') {
                return "{$fields}, avg_views";
            }
            return "{$fields}, c.comment_count";
        };
    }

    protected static function postsOrderbyFn($range, $order)
    {
        return function () use ($range, $order) {
            if ($order === 'views') {
                return 'pageviews DESC';
            }
            if ($order === 'avg') {
                return 'avg_views DESC';
            }

            return ($range === 'all' ? '' : 'c.') . 'comment_count DESC';
        };
    }

    protected static function postsGroupbyFn($range, $order)
    {
        return function ($groupby) use ($range, $order) {
            if ($range === 'all' && $order === 'avg') {
                return 'v.postid';
            }

            return $groupby;
        };
    }

    protected static function postsJoinFn($range, $order, $now)
    {
        return function ($join) use ($range, $order, $now) {
            global $wpdb;

            if ($range === 'all') {
                if ($order !== 'comments') {
                    return "{$join} INNER JOIN `{$wpdb->prefix}popularpostsdata` v ON {$wpdb->posts}.ID = v.postid";
                }

                return $join;
            }

            $startDate = clone $now;

            switch ($range) {
                case 'daily':
                    $startDate = $startDate->sub(new \DateInterval('P1D'));
                    $startDatetime = $startDate->format('Y-m-d H:i:s');
                    $views_time_range = "view_datetime >= '{$startDatetime}'";
                    break;
                case 'weekly':
                    $startDate = $startDate->sub(new \DateInterval('P6D'));
                    $startDatetime = $startDate->format('Y-m-d');
                    $views_time_range = "view_date >= '{$startDatetime}'";
                    break;
                case 'monthly':
                default:
                    $startDate = $startDate->sub(new \DateInterval('P29D'));
                    $startDatetime = $startDate->format('Y-m-d');
                    $views_time_range = "view_date >= '{$startDatetime}'";
                    break;
            }

            if ($order === 'views') {
                return "{$join} INNER JOIN (SELECT SUM(pageviews) AS pageviews, postid FROM `{$wpdb->prefix}popularpostssummary` WHERE {$views_time_range} GROUP BY postid) v ON {$wpdb->posts}.ID = v.postid";
            }

            if ($order === 'avg') {
                return "{$join} INNER JOIN (SELECT SUM(pageviews)/(IF (DATEDIFF('{$now->format(
                    'Y-m-d H:i:s'
                )}', '{$startDatetime}') > 0, DATEDIFF('{$now->format(
                    'Y-m-d H:i:s'
                )}', '{$startDatetime}'), 1)) AS avg_views, postid FROM `{$wpdb->prefix}popularpostssummary` WHERE {$views_time_range} GROUP BY postid) v ON {$wpdb->posts}.ID = v.postid";
            }

            if ($order === 'comments') {
                return "{$join} INNER JOIN (SELECT COUNT(comment_post_ID) AS comment_count, comment_post_ID FROM `{$wpdb->comments}` WHERE comment_date_gmt >= '{$startDatetime}' AND comment_approved = '1' GROUP BY comment_post_ID) c ON {$wpdb->posts}.ID = c.comment_post_ID";
            }
        };
    }
}
