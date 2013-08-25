<?php

namespace Railtime;

const BaseURI = "http://api.irishrail.ie/realtime/realtime.asmx";

//The timezone of the dates and times returned by the API
const Timezone = "Europe/Dublin";

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
