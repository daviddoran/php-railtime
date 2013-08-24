<?php

namespace Railtime;

const BaseURI = "http://api.irishrail.ie/realtime/realtime.asmx";

const DirectionNorthbound = 'N';
const DirectionSouthbound = 'S';
const DirectionToStation  = 'T';

const TrainTypeAll      = 'A';
const TrainTypeDart     = 'D';
const TrainTypeMainline = 'M';
const TrainTypeSuburban = 'S';

const StationTypeAll      = 'A';
const StationTypeDart     = 'D';
const StationTypeMainline = 'M';
const StationTypeSuburban = 'S';

const LocationTypeOrigin = 'O';
const LocationTypeDestination = 'D';
const LocationTypeStop = 'S';
const LocationTypeTimingPoint = 'T';

const StopTypeCurrent = 'C';
const StopTypeNext    = 'N';

/**
 * Railtime API Exception
 * @package Railtime
 */
class Exception extends \Exception {}

class API {
    /**
     * @var DownloaderInterface
     */
    protected $downloader;

    /**
     * @param DownloaderInterface $dl
     */
    public function __construct(DownloaderInterface $dl) {
        $this->downloader = $dl;
    }

    /**
     * Get a list of stations
     *
     * Optionally, stations may be filtered by type.
     *
     * @param string $type
     * @return Station[]
     */
    public function stations($type = StationTypeAll) {
        $callback = function (\SimpleXMLElement $station) {
            return Station::create(array(
                "id" => (int)$station->StationId,
                "code" => trim($station->StationCode),
                "description" => trim($station->StationDesc),
                "alias" => trim($station->StationAlias),
                "latitude" => (float)$station->StationLatitude,
                "longitude" => (float)$station->StationLongitude
            ));
        };
        $query = array(
            "StationType" => $type
        );
        return $this->_get_mapped("/getAllStationsXML_WithStationType", $query, $callback);
    }

    /**
     * Get the currently running trains (between origin and destination)
     *
     * Optionally, trains may be filtered by type.
     *
     * @param string $type
     * @return RunningTrain[]
     */
    public function current_trains($type = TrainTypeAll) {
        $callback = function (\SimpleXMLElement $train) {
            return RunningTrain::create(array(
                "code" => trim($train->TrainCode),
                "date" => trim($train->TrainDate),
                "status" => trim($train->TrainStatus),
                "latitude" => (float)$train->TrainLatitude,
                "longitude" => (float)$train->TrainLongitude,
                "message" => trim($train->PublicMessage),
                "direction" => trim($train->Direction)
            ));
        };
        $query = array(
            "TrainType" => $type
        );
        return $this->_get_mapped("/getCurrentTrainsXML_WithTrainType", $query, $callback);
    }

    /**
     * Get the trains passing a station
     *
     * Optionally, how many $minutes into the future to look.
     *
     * @param string $name_or_code
     * @param int $minutes
     * @return StationPassing[]
     */
    public function station_passings($name_or_code, $minutes = null) {
        $callback = function (\SimpleXMLElement $passing) {
            return StationPassing::create(array(
                "server_time" => trim($passing->Servertime),
                "train_code" => trim($passing->Traincode),
                "station_code" => trim($passing->Stationcode),
                "station_fullname" => trim($passing->Stationfullname),
                "query_time" => trim($passing->Querytime),
                "train_date" => trim($passing->Traindate),
                "origin" => trim($passing->Origin),
                "destination" => trim($passing->Destination),
                "origin_time" => trim($passing->Origintime),
                "destination_time" => trim($passing->Destinationtime),
                "status" => trim($passing->Status),
                "last_location" => trim($passing->Lastlocation),
                "due_minutes" => trim($passing->Duein),
                "late_minutes" => trim($passing->Late),
                "expected_arrival" => trim($passing->Exparrival),
                "expected_departure" => trim($passing->Expdepart),
                "scheduled_arrival" => trim($passing->Scharrival),
                "scheduled_departure" => trim($passing->Schdepart),
                "direction" => trim($passing->Direction),
                "train_type" => trim($passing->Traintype),
                "location_type" => trim($passing->Locationtype)
            ));
        };
        $query = array();
        if (strlen($name_or_code) > 5 or !(ctype_upper($name_or_code))) {
            $query["StationDesc"] = $name_or_code;
            $path = "/getStationDataByNameXML" . (!is_null($minutes) ? "_withNumMins" : "");
        } else {
            $query["StationCode"] = $name_or_code;
            $path = "/getStationDataByCodeXML" . (!is_null($minutes) ? "_WithNumMins" : "");
        }
        if (!is_null($minutes)) {
            $query["NumMins"] = $minutes;
        }
        return $this->_get_mapped($path, $query, $callback);
    }

    /**
     * Get the movements of a train on a particular day
     * 
     * @param string $train_id
     * @param string $train_date
     * @return TrainMovement[]
     */
    public function train_movements($train_id, $train_date) {
        $callback = function (\SimpleXMLElement $movement) {
            return TrainMovement::create(array(
                "train_code" => trim($movement->TrainCode),
                "train_date" => trim($movement->TrainDate),

                "location_code" => trim($movement->LocationCode),
                "location_fullname" => trim($movement->LocationFullName),
                "location_order" => trim($movement->LocationOrder),
                "location_type" => trim($movement->LocationType),

                "train_origin" => trim($movement->TrainOrigin),
                "train_destination" => trim($movement->TrainDestination),

                "expected_arrival" => trim($movement->ExpectedArrival),
                "expected_departure" => trim($movement->ExpectedDeparture),

                "scheduled_arrival" => trim($movement->ScheduledArrival),
                "scheduled_departure" => trim($movement->ScheduledDeparture),

                "actual_arrival" => trim($movement->Arrival),
                "actual_departure" => trim($movement->Departure),

                "auto_arrival" => ("1" == trim($movement->AutoArrival)),
                "auto_departure" => ("1" == trim($movement->AutoDepart)),

                "stop_type" => trim($movement->StopType)
            ));
        };
        $query = array(
            "TrainId" => $train_id,
            "TrainDate" => $train_date
        );
        return $this->_get_mapped("/getTrainMovementsXML", $query, $callback);
    }

    /**
     * Download XML and map over the results
     *
     * This is a utility function that:
     *   - Makes a GET request to the API
     *   - Downloads and parses the result XML
     *   - Maps $function over the result elements
     *
     * Since the XML API is (thankfully) very consistent we
     * can convert each of the XML response bodies to lists
     * of the appropriate object, e.g. Train or StationPassing.
     *
     * @param string $path
     * @param array $params
     * @param callable $function
     * @return RailtimeObject
     * @throws Exception
     */
    protected function _get_mapped($path, array $params, /*callable*/ $function) {
        //Download the XML. If this fails then an exception is thrown.
        $xml = $this->downloader->get($path, $params);
        //Suppress PHP warnings/errors/etc and use exceptions
        libxml_use_internal_errors(true);
        try {
            //Parse XML (disable network and don't expand entities)
            $sxml = new \SimpleXMLElement($xml, LIBXML_NONET | LIBXML_NOENT);
            $results = array();
            foreach ($sxml->children() as $node) {
                $results []= call_user_func($function, $node);
            }
            return $results;
        } catch (\Exception $e) {
            //print_r(libxml_get_errors());
            throw new Exception("XML parsing failed.", 0, $e);
        }
    }
}

function jsonify($api, $filename, $function, $ttl = 3600) {
    if (file_exists($filename)) {
        if (filemtime($filename) > (time() - $ttl)) {
            return;
        }
    }
    file_put_contents("json/" . $filename, json_encode(
        $function($api),
        JSON_PRETTY_PRINT
    ));
}