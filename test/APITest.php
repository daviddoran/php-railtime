<?php

class APITest extends \PHPUnit_Framework_TestCase {
    /**
     * @dataProvider testStationsProvider
     * @param string $station_type
     * @param string $mock_xml_file
     */
    public function testStations($station_type, $mock_xml_file) {
        $mock_downloader = $this->mock_downloader_interface();
        $mock_downloader->expects($this->once())->method('get')
            ->with(
                $this->equalTo('/getAllStationsXML_WithStationType'),
                $this->equalTo(array("StationType" => $station_type))
            )
            ->will($this->returnValue(file_get_contents($mock_xml_file)));

        $api = new \Railtime\API($mock_downloader);
        $stations = $api->stations($station_type);
        $this->assertNotEmpty($stations);
        $this->assertContainsOnlyInstancesOf('\Railtime\Station', $stations);
    }

    /**
     * @return array
     */
    public function testStationsProvider() {
        return array(
            array(\Railtime\StationTypeAll, self::mockfile("stations-" . \Railtime\StationTypeAll . ".xml")),
            array(\Railtime\StationTypeDart, self::mockfile("stations-" . \Railtime\StationTypeDart . ".xml")),
            array(\Railtime\StationTypeMainline, self::mockfile("stations-" . \Railtime\StationTypeMainline . ".xml")),
            array(\Railtime\StationTypeSuburban, self::mockfile("stations-" . \Railtime\StationTypeSuburban . ".xml"))
        );
    }

    /**
     * @dataProvider testCurrentTrainsProvider
     * @param string $train_type
     * @param string $mock_xml_file
     */
    public function testCurrentTrains($train_type, $mock_xml_file) {
        $mock_downloader = $this->mock_downloader_interface();
        $mock_downloader->expects($this->once())->method('get')
            ->with(
                $this->equalTo('/getCurrentTrainsXML_WithTrainType'),
                $this->equalTo(array("TrainType" => $train_type))
            )
            ->will($this->returnValue(file_get_contents($mock_xml_file)));

        $api = new \Railtime\API($mock_downloader);
        $trains = $api->current_trains($train_type);
        $this->assertNotEmpty($trains);
        $this->assertContainsOnlyInstancesOf('\Railtime\RunningTrain', $trains);
    }

    /**
     * @return array
     */
    public function testCurrentTrainsProvider() {
        return array(
            array(\Railtime\TrainTypeAll, self::mockfile("current-" . \Railtime\TrainTypeAll. ".xml")),
            array(\Railtime\TrainTypeDart, self::mockfile("current-" . \Railtime\TrainTypeDart. ".xml")),
            array(\Railtime\TrainTypeMainline, self::mockfile("current-" . \Railtime\TrainTypeMainline. ".xml")),
            array(\Railtime\TrainTypeSuburban, self::mockfile("current-" . \Railtime\TrainTypeSuburban. ".xml"))
        );
    }

    /**
     * @dataProvider testStationPassingsProvider
     * @param string $station
     * @param string $fullname
     * @param int|null $mins
     * @param string $path
     * @param array $query
     * @param string $mock_xml_file
     */
    public function testStationPassings($station, $fullname, $mins, $path, $query, $mock_xml_file) {
        $mock_downloader = $this->mock_downloader_interface();
        $mock_downloader
            ->expects($this->once())->method('get')
            ->with(
                $this->equalTo($path),
                $this->equalTo($query)
            )
            ->will($this->returnValue(file_get_contents($mock_xml_file)));

        $api = new \Railtime\API($mock_downloader);
        $passings = $api->station_passings($station, $mins);
        $this->assertNotEmpty($passings);
        $this->assertContainsOnlyInstancesOf('\Railtime\StationPassing', $passings);
        foreach ($passings as $passing) {
            $this->assertEquals($fullname, $passing->station_fullname);
        }
    }

    /**
     * @return array
     */
    public function testStationPassingsProvider() {
        return array(
            //short station name (default minutes) to check it doesn't interpret it as a code
            array("Athy", "Athy", null, "/getStationDataByNameXML", array("StationDesc" => "Athy"), self::mockfile("passings-Athy.xml")),
            //station name (default minutes)
            array("Howth Junction", "Howth Junction", null, "/getStationDataByNameXML", array("StationDesc" => "Howth Junction"), self::mockfile("passings-HowthJunction.xml")),
            //station name (30 minutes)
            array("Howth Junction", "Howth Junction", 30, "/getStationDataByNameXML_withNumMins", array("StationDesc" => "Howth Junction", "NumMins" => 30), self::mockfile("passings-HowthJunction-30m.xml")),
            //station code (default minutes)
            array("KKNNY", "Kilkenny", null, "/getStationDataByCodeXML", array("StationCode" => "KKNNY"), self::mockfile("passings-KKNNY.xml")),
            //station code (30 minutes)
            array("MHIDE", "Malahide", 30, "/getStationDataByCodeXML_WithNumMins", array("StationCode" => "MHIDE", "NumMins" => 30), self::mockfile("passings-MHIDE-30m.xml")),
        );
    }

    /**
     * @expectedException \Railtime\Exception
     * @dataProvider testStationPassingsValidateMinsProvider
     * @param int $mins
     */
    public function testStationPassingsValidateMins($mins) {
        $mock_downloader = $this->mock_downloader_interface();
        $api = new \Railtime\API($mock_downloader);
        $api->station_passings("MHIDE", $mins);
    }

    /**
     * @return array
     */
    public function testStationPassingsValidateMinsProvider() {
        return array(
            array(null),
            array(0),
            array(4),
            array(91),
        );
    }

    /**
     * @dataProvider testTrainMovementsProvider
     * @param string $train_id
     * @param string $train_date
     * @param string $mock_xml_file
     */
    public function testTrainMovements($train_id, $train_date, $mock_xml_file) {
        $mock_downloader = $this->mock_downloader_interface();
        $mock_downloader
            ->expects($this->once())->method('get')
            ->with(
                $this->equalTo("/getTrainMovementsXML"),
                $this->equalTo(array("TrainId" => $train_id, "TrainDate" => $train_date))
            )
            ->will($this->returnValue(file_get_contents($mock_xml_file)));

        $api = new \Railtime\API($mock_downloader);
        $movements = $api->train_movements($train_id, $train_date);
        $this->assertNotEmpty($movements);
        $this->assertContainsOnlyInstancesOf('\Railtime\TrainMovement', $movements);
    }

    /**
     * @return array
     */
    public function testTrainMovementsProvider() {
        return array(
            array("e109", "21 dec 2011", self::mockfile("movements-e109-21dec2011.xml")),
            array("E815", "25 Aug 2013", self::mockfile("movements-e815-25aug2013.xml")),
        );
    }

    /**
     * @expectedException \Railtime\Exception
     */
    public function testHandleInvalidXml() {
        $mock_downloader = $this->mock_downloader_interface();
        $mock_downloader
            ->expects($this->once())->method('get')
            ->will($this->returnValue("Not an XML string."));

        $api = new \Railtime\API($mock_downloader);
        $api->stations();
    }

    /**
     * @return \Railtime\DownloaderInterface
     */
    private function mock_downloader_interface() {
        return $this->getMock('\Railtime\DownloaderInterface');
    }

    /**
     * Get the full path to a mock (XML) file
     *
     * @param string $filename
     * @return string
     */
    public static function mockfile($filename) {
        return __DIR__ . "/mock/" . $filename;
    }
}
