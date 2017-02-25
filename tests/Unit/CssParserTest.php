<?php namespace Tests\Unit;

use CssPalette\Palette\CssParser;
use Tests\TestCase;
use Votemike\Color\Color;

class CssPaletteTest extends TestCase
{
    public function testGetNothingFromCss()
    {
        $css = 'body {border-radius:5px;}';
        $this->assertEmpty(CssParser::extractColours($css));
    }

    public function testLongHexRegex()
    {
        $this->assertNotRegExp('/' . CssParser::LONG_HEX_REGEX . '/', 'body {background-color:#0F0F0G;}');
        $this->assertNotRegExp('/' . CssParser::LONG_HEX_REGEX . '/', 'body {background-color:#-00F0F;}');
        $this->assertNotRegExp('/' . CssParser::LONG_HEX_REGEX . '/', 'body {background-color:#0FAA;}');
        $this->assertNotRegExp('/' . CssParser::LONG_HEX_REGEX . '/', 'body {background-color:#0FAAAAA;}');
        $this->assertNotRegExp('/' . CssParser::LONG_HEX_REGEX . '/', 'body {background-color:#0F;}');
        $this->assertRegExp('/' . CssParser::LONG_HEX_REGEX . '/', 'body {background-color:#0F0F0F;}');
    }

    public function testGetLongHexFromCss()
    {
        $css = 'body {background-color:#0F0F0F;}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(1, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }


    public function testShortHexRegex()
    {
        $this->assertNotRegExp('/' . CssParser::SHORT_HEX_REGEX . '/', 'body {background-color:#0FG;}');
        $this->assertNotRegExp('/' . CssParser::SHORT_HEX_REGEX . '/', 'body {background-color:#-0A;}');
        $this->assertNotRegExp('/' . CssParser::SHORT_HEX_REGEX . '/', 'body {background-color:#0FAA;}');
        $this->assertNotRegExp('/' . CssParser::SHORT_HEX_REGEX . '/', 'body {background-color:#0FAAAAA;}');
        $this->assertNotRegExp('/' . CssParser::SHORT_HEX_REGEX . '/', 'body {background-color:#0F;}');
        $this->assertRegExp('/' . CssParser::SHORT_HEX_REGEX . '/', 'body {background-color:#0FA;}');
    }

    public function testGetShortHexFromCss()
    {
        $css = 'body {background-color:#0FA;}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(1, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testGetMultipleHexFromCss()
    {
        $css = 'body {background-color:#0F0F0F;} div {background-color:#1AF;}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(2, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testGetDuplicateHexFromCss()
    {
        $css = 'body {background-color:#11AAFF;} div {background-color:#1AF;}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(1, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testRgbRegex()
    {
        $this->assertRegExp('/' . CssParser::RGB_REGEX . '/', 'body {background-color:rgb(0,199,255);}');
        $this->assertRegExp('/' . CssParser::RGB_REGEX . '/', 'body {background-color:rgb(0, 199, 255);}');
        $this->assertNotRegExp('/' . CssParser::RGB_REGEX . '/', 'body {background-color:rgb(0,199,257);}');
        $this->assertNotRegExp('/' . CssParser::RGB_REGEX . '/', 'body {background-color:rgb(0,399,255);}');
        $this->assertNotRegExp('/' . CssParser::RGB_REGEX . '/', 'body {background-color:rgb(0,-199,255);}');
        $this->assertNotRegExp('/' . CssParser::RGB_REGEX . '/', 'body {background-color:rgb(0,-199,255,1);}');
    }

    public function testGetRgbFromCss()
    {
        $css = 'body {background-color: rgb(0, 199, 255);}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(1, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testRgbaRegex()
    {
        $this->assertRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgba(0,199,255,0);}');
        $this->assertRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgba(0,199,255,1.0);}');
        $this->assertRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgba(0, 199, 255, 1);}');
        $this->assertRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgba(0, 199, 255, 0.5);}');
        $this->assertNotRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgb(0,199,255,1);}');
        $this->assertNotRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgba(0,199,255,2);}');
        $this->assertNotRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgba(0,399,255,1);}');
        $this->assertNotRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgba(0,-199,255, 1);}');
        $this->assertNotRegExp('/' . CssParser::RGBA_REGEX . '/', 'body {background-color:rgba(0,199,255);}');
    }

    public function testGetRgbaFromCss()
    {
        $css = 'body {background-color: rgba(0, 199, 255, 0.9);}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(1, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testGetDuplicateRgbRgbaFromCss()
    {
        $css = 'body {background-color: rgb(0, 199, 255);} div {background-color: rgba(0, 199, 255, 1); span {background-color: rgba(0,199,255,0.9)}}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(2, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testHslRegex()
    {
        $this->assertRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(0,0%,0%);}');
        $this->assertRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(180,50%,0%);}');
        $this->assertRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(360,0%,50%);}');
        $this->assertRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(360,100%,100%);}');
        $this->assertRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(360, 100%, 100%);}');
        $this->assertNotRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(0,0%,0%,0);}');
        $this->assertNotRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(-0,0%,0%);}');
        $this->assertNotRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(0,-0%,0%);}');
        $this->assertNotRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(0,101%,0%);}');
        $this->assertNotRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(0,0%,101%);}');
        $this->assertNotRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(380,0%,0%);}');
        $this->assertNotRegExp('/' . CssParser::HSL_REGEX . '/', 'body {background-color:hsl(380,0%,0%,1);}');
    }

    public function testGetHslFromCss()
    {
        $css = 'body {background-color: hsl(180,50%,0%);}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(1, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testHslaRegex()
    {
        $this->assertRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(0,0%,0%,0);}');
        $this->assertRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(180,50%,0%,1);}');
        $this->assertRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(180, 50%, 0%, 1);}');
        $this->assertRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(360,0%,50%, 0.5);}');
        $this->assertNotRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(0,0%,0%);}');
        $this->assertNotRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(-0,0%,0%,1);}');
        $this->assertNotRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(0,-0%,0%,1);}');
        $this->assertNotRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(0,101%,0%,1);}');
        $this->assertNotRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(0,0%,101%,1);}');
        $this->assertNotRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(380,0%,0%,1);}');
        $this->assertNotRegExp('/' . CssParser::HSLA_REGEX . '/', 'body {background-color:hsla(360,0%,0%,2);}');
    }

    public function testGetHslaFromCss()
    {
        $css = 'body {background-color: hsla(180,50%,0%,1);}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(1, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testGetDuplicateHslHslaFromCss()
    {
        $css = 'body {background-color: hsl(180, 50%, 50%);} div {background-color: hsla(180, 50%, 50%, 1); span {background-color: hsla(240,100%,50%,0.5)}}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(2, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testGetDuplicatesFromCss()
    {
        $css = 'body {background-color: hsl(0, 0%, 80%);} div {background-color: rgba(204, 204, 204, 1); span {background-color: #CCCCCC}}';
        $colours = CssParser::extractColours($css);
        $this->assertCount(1, $colours);
        $this->assertContainsOnlyInstancesOf(Color::class, $colours);
    }

    public function testColourSorting()
    {
        $css = 'body {background-color: rgb(0,0,0); background-color: rgb(0,2,0); background-color: rgb(0,0,1); background-color: rgb(1,0,0);}';
        $colours = CssParser::extractColours($css);
        $this->assertAttributeEquals(0, 'red', $colours[0]);
        $this->assertAttributeEquals(0, 'green', $colours[0]);
        $this->assertAttributeEquals(0, 'blue', $colours[0]);

        $this->assertAttributeEquals(0, 'red', $colours[1]);
        $this->assertAttributeEquals(0, 'green', $colours[1]);
        $this->assertAttributeEquals(1, 'blue', $colours[1]);

        $this->assertAttributeEquals(1, 'red', $colours[2]);
        $this->assertAttributeEquals(0, 'green', $colours[2]);
        $this->assertAttributeEquals(0, 'blue', $colours[2]);

        $this->assertAttributeEquals(0, 'red', $colours[3]);
        $this->assertAttributeEquals(2, 'green', $colours[3]);
        $this->assertAttributeEquals(0, 'blue', $colours[3]);
    }
}
