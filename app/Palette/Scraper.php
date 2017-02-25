<?php namespace CssPalette\Palette;

use DOMDocument;

class Scraper
{
    /**
     * @param string $html
     * @param string $baseUrl
     * @return array
     */
    public static function extractCssFiles($html, $baseUrl)
    {
        libxml_use_internal_errors(true);
        $files = [];
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $domcss = $doc->getElementsByTagName('link');
        foreach ($domcss as $links) {
            if (strtolower($links->getAttribute('rel')) == 'stylesheet') {
                $files[] = self::parseStylesheetUrl($baseUrl, $links->getAttribute('href'));
            }
        }

        return array_unique($files);
    }

    /**
     * @param string $baseUrl
     * @param string $href
     * @return string
     */
    private static function parseStylesheetUrl($baseUrl, $href)
    {
        $parts = parse_url($href);
        if (isset($parts['host'])) {
            return $href;
        }

        return $baseUrl . '/' . ltrim($href, '/');
    }
}
