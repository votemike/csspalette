<?php

use CssPalette\Palette\Scraper;
use Tests\TestCase;

class ScraperTest extends TestCase
{
    public function testExtractingCssFileNames()
    {
        $html = '<head><link rel="stylesheet" type="text/css" href="theme.css"></head>';
        $urls = Scraper::extractCssFiles($html, 'http://localhost');
        $this->assertCount(1, $urls);
        $this->assertSame('http://localhost/theme.css', $urls[0]);

        $html = '<head><link rel="stylesheet" type="text/css" href="directory/theme.css"></head>';
        $urls = Scraper::extractCssFiles($html, 'http://localhost');
        $this->assertCount(1, $urls);
        $this->assertSame('http://localhost/directory/theme.css', $urls[0]);

        $html = '<head><link rel="stylesheet" type="text/css" href="directory/theme.css"><link rel="stylesheet" type="text/css" href="theme.css"></head>';
        $urls = Scraper::extractCssFiles($html, 'http://localhost');
        $this->assertCount(2, $urls);

        $html = '<head><link rel="stylesheet" type="text/css" href="theme.css"><link rel="stylesheet" type="text/css" href="theme.css"></head>';
        $urls = Scraper::extractCssFiles($html, 'http://localhost');
        $this->assertCount(1, $urls);

        $html = '<head><link rel="stylesheet" type="text/css" href="' . url('theme.css') . '"></head>';
        $urls = Scraper::extractCssFiles($html, 'http://localhost');
        $this->assertCount(1, $urls);
        $this->assertSame('http://localhost/theme.css', $urls[0]);
    }
}
