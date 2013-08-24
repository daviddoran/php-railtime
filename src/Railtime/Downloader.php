<?php

namespace Railtime;

/**
 * Simple Downloader
 *
 * This simple downloader uses the "Requests for PHP"
 * library to GET a path with optional query params.
 *
 * For convenience, this class assumes a base URI.
 *
 * @package Railtime
 * @link https://github.com/rmccue/Requests
 */
class Downloader implements DownloaderInterface {
    /**
     * Use the package's base URI by default
     *
     * @var string
     */
    protected $base_uri = BaseURI;

    public function get($path, array $params = array()) {
        try {
            $response = \Requests::request(self::build_uri($this->base_uri, $path), array(), $params, \Requests::GET);
        } catch (\Requests_Exception $e) {
            throw new Exception("Downloading XML failed.", 0, $e);
        }
        return $response->body;
    }

    /**
     * @param string $base_uri
     */
    public function set_base_uri($base_uri) {
        $this->base_uri = $base_uri;
    }

    /**
     * @return string
     */
    public function get_base_uri() {
        return $this->base_uri;
    }

    /**
     * Build a URI from base, path and query params
     *
     * @param string $base
     * @param string $path
     * @return string
     */
    protected function build_uri($base, $path) {
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}
