<?php

class ExtendedUserType
{
    public static $networks = [
        'instagram'  => 'Instagram',
        'youtube'    => 'YouTube',
        'facebook'   => 'Facebook',
        'twitter'    => 'Twitter',
        'linkedin'   => 'LinkedIn',
        'pinterest'  => 'Pinterest',
        'myspace'    => 'MySpace',
        'soundcloud' => 'SoundCloud',
        'tumblr'     => 'Tumblr',
        'wikipedia'  => 'Wikipedia',
    ];

    public static function config()
    {
        $fields = [];

        foreach(self::$networks as $network => $label) {
            $fields[$network] = [
                'type' => 'String',
                'metadata' => [
                    'label' => $label
                ],
                'extensions' => [
                    'call' => __CLASS__.'::data'
                ],
            ];
        }

        return ['fields' => $fields];
    }

    public static function data($obj, $args, $context, $info)
    {
        if (!array_key_exists($info->fieldName, self::$networks)) {
            return '';
        }

        return get_the_author_meta($info->fieldName, $obj->ID);
    }
}
