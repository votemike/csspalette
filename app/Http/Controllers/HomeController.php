<?php namespace CssPalette\Http\Controllers;

use CssPalette\Palette\CssParser;
use CssPalette\Palette\Scraper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package Palette\Http\Controllers
 */
class HomeController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function file(Request $request)
    {
        $file = $request->file('file');
        $css = file_get_contents($file->getRealPath()); //@TODO find a Laravel way to do this

        return view('colours')->with('colours', CssParser::extractColours($css));
    }

    /**
     * @return \Illuminate\View\View | \Illuminate\Http\RedirectResponse
     */
    public function site(Request $request)
    {
        $url = $request->input('site');

        if ($this->hasNoHost($url)) {
            return $this->buildNewUrlRedirect($request, 'http://' . $url);
        }
        if ($redirect = $this->getRedirect($url)) {
            return $this->buildNewUrlRedirect($request, $redirect);
        }
        $html = $this->getUrlContents($url);

        if ($html === false) {
            return $this->buildErrorRedirect($url);
        }

        $parts = parse_url($url);
        $baseUrl = $parts['scheme'] . '://' . $parts['host'];
        $files = Scraper::extractCssFiles($html, $baseUrl);
        $css = '';
        foreach ($files as $file) {
            $contents = $this->getUrlContents($file);
            if ($contents == false) {
                return $this->buildErrorRedirect($file);
            }
            $css .= $contents;
        }

        return view('colours')->with('colours', CssParser::extractColours($css));
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View | \Illuminate\Http\RedirectResponse
     */
    public function sitefile(Request $request)
    {
        $cssFile = $request->input('sitefile');

        $css = $this->getUrlContents($cssFile);
        if ($css == false) {
            return $this->buildErrorRedirect($cssFile);
        }
        return view('colours')->with('colours', CssParser::extractColours($css));
    }

    /**
     * @param $url
     * @return \Illuminate\Http\RedirectResponse
     */
    private function buildErrorRedirect($url)
    {
        return redirect('/')
            ->withErrors([$url . ' could not be opened.'])
            ->withInput();
    }

    /**
     * @param Request $request
     * @param $newUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    private function buildNewUrlRedirect(Request $request, $newUrl)
    {
        return redirect()
            ->route($request->route()->getPath(), ['site' => $newUrl]);
    }

    /**
     * @param $url
     * @return bool|string
     */
    private function getRedirect($url)
    {
        $client = new Client();
        try {
            $response = $client->get($url, ['allow_redirects' => false]);
        } catch (ConnectException $e) {
            return false;
        }

        if ($response->getStatusCode() == 303) {
            $redirects = $response->getHeader('Location');
            return array_pop($redirects);
        }

        return false;
    }

    /**
     * @param $url
     * @return bool|string
     */
    private function getUrlContents($url)
    {
        $client = new Client();
        try {
            $response = $client->get($url);
        } catch (ConnectException $e) {
            return false;
        }

        if ($response->getStatusCode() == 200) {
            return (string)$response->getBody();
        }

        return false;
    }

    /**
     * @param $url
     * @return bool
     */
    private function hasNoHost($url)
    {
        return empty(parse_url($url, PHP_URL_HOST));
    }
}
