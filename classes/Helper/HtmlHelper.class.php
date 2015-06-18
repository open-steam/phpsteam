<?php

namespace OpenSteam\Helper;

use HTMLPurifier_Config;
use HTMLPurifier;

class HtmlHelper
{

	 public static function purify($string) {
        //require_once './libraries/php/htmlpurifier-4.3.0/library/HTMLPurifier.auto.php';
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        //$config->set('Core.CollectErrors', true);
        $config->set('CSS.AllowTricky', true);
        $config->set('CSS.AllowedProperties', array("color", "text-align", "float", "margin-left", "display", "margin-right", "list-style-type", "padding-left"));
        $config->set('HTML.AllowedAttributes', '*.style,*.id,*.title,*.class,a.href,a.target,img.src,img.alt,*.name,ol.start');
        $config->set('Attr.EnableID', true);
        //$def = $config->getHTMLDefinition(true);
        //$def->addAttribute('a', 'id', 'ID');

        $purifier = new HTMLPurifier($config);
        return $purifier->purify($string);
    }

    public static function encode($encode, $data) {
        if ($encode === 'html') {
            $data = self::purify($data);
            $data = 'data:text/html;charset=utf8;base64,' . base64_encode($data);
        }

        return $data;
    }

    public static function decode($decode, $data) {
        if ($decode === 'html' && is_string($data)) {
            $pattern = 'data:text/html;charset=utf8;base64,';
            if (substr($data, 0, strlen($pattern)) === $pattern) {
                $data = str_replace($pattern, '', $data);
                $data = base64_decode($data);
                $data = self::purify($data);
            }
        }
        return $data;
    }

    public static function strip_tags_array($array)
    {
        if (!is_array($array)) {
            return $array;
        }
        $keys = array_keys($array);
        foreach ($keys as $key) {
            $value = $array[$key];
            if (is_string($value)) {
                $array[$key] = strip_tags($value);
            }
        }

        return $array;
    }

    public static function decode_array($decode, $array)
    {
        if (!is_array($decode) || !is_array($array)) {
            return $array;
        }
        $keys = array_keys($array);
        foreach ($keys as $key) {
            $value = $array[$key];
            if (is_string($value)) {
                if (isset($decode[$key])) {
                    $array[$key] = self::decode($decode[$key], $value);
                }
            }
        }

        return $array;
    }

}