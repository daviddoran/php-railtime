<?php

/**
 * Test the Downloader
 *
 * TODO: Test the Downloader::get method (Requests uses static methods)
 */
class DownloaderTest extends \PHPUnit_Framework_TestCase {
    public function testBaseURI() {
        $expected = "http://google.com";
        $downloader = new \Railtime\Downloader;
        $downloader->set_base_uri($expected);
        $this->assertEquals($expected, $downloader->get_base_uri());
    }
}
