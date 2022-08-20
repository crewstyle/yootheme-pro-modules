<?php

use YOOtheme\Builder\Wordpress\Source\Helper as SourceHelper;
use YOOtheme\Str;

class StickyPostsListener
{
    public static function initSource($source)
    {
        foreach (SourceHelper::getPostTypes() as $key => $type) {
            if ('post' !== $key) {
                continue;
            }

            $query = Str::camelCase([SourceHelper::getBase($type), 'Query'], true);
            $source->objectType($query, StickyPostsQueryType::config($type));
        }
    }
}
