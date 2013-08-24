<?php

namespace Railtime;

interface DownloaderInterface {
    /**
     * GET a path (with optional parameters)
     *
     * Returns the string response body.
     *
     * @param string $path
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function get($path, array $params = array());
}
