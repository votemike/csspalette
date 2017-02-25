<?php namespace CssPalette\Palette;

use Votemike\Color\Color;

/**
 * Class CssParser
 * @package Palette\Palette
 */
class CssParser
{

    const SHORT_HEX_REGEX = '#([a-fA-F\d]{3})\b';
    const LONG_HEX_REGEX = '#([a-fA-F\d]{6})\b';
    const RGB_REGEX = 'rgb\(((?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5]),\s*){2}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5]))\)';
    const RGBA_REGEX = 'rgba\(((?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5]),\s*){3}(?:[01]|1.0*|0?.\d))\)';
    const HSL_REGEX = 'hsl\(((?:(?:[012]?\d{1,2}|3[0-5]\d|360))(?:,\s*(?:100|\d{1,2})%){2})\)';
    const HSLA_REGEX = 'hsla\(((?:(?:[012]?\d{1,2}|3[0-5]\d|360))(?:,\s*(?:100|\d{1,2})%){2}(?:,\s*(?:[01]|1.0*|0?.\d)))\)';

    /**
     * function extractColours
     * @todo implement x11 colours
     * @todo refactor
     * @param string css
     * @return array
     */
    public static function extractColours($css)
    {
        $shortHexs = self::extract($css, self::SHORT_HEX_REGEX, 'fromShortHex');
        $longHexs = self::extract($css, self::LONG_HEX_REGEX, 'fromHex');
        $rgbs = self::extract($css, self::RGB_REGEX, 'fromRgb');
        $rgbas = self::extract($css, self::RGBA_REGEX, 'fromRgba');
        $hsls = self::extract($css, self::HSL_REGEX, 'fromHsl');
        $hslas = self::extract($css, self::HSLA_REGEX, 'fromHsla');
        $colours = array_merge($shortHexs, $longHexs, $rgbs, $rgbas, $hsls, $hslas);

        $uniqueColours = array_unique($colours, SORT_REGULAR);

        return self::sortColours($uniqueColours);
    }

    /**
     * @param string $css
     * @param string $regex
     * @param string $function
     * @return array
     */
    private static function extract($css, $regex, $function)
    {
        preg_match_all('/' . $regex . '/', $css, $matches);
        $colours = [];
        foreach ($matches[1] as $match) {
            $colours[] = Color::$function($match);
        }

        return $colours;
    }

    private static function sortColours($colours)
    {
        $sum = [];
        $r = [];
        $g = [];
        $b = [];
        $a = [];
        foreach ($colours as $key => $colour) {
            $sum[$key] = $colour->getRed() + $colour->getGreen() + $colour->getBlue();
            $r[$key] = $colour->getRed();
            $g[$key] = $colour->getGreen();
            $b[$key] = $colour->getBlue();
            $a[$key] = $colour->getAlpha();
        }

        array_multisort($sum, SORT_ASC, $r, SORT_ASC, $g, SORT_ASC, $b, SORT_ASC, $a, SORT_ASC, $colours);

        return $colours;
    }
}
