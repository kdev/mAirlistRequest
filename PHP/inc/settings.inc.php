<?php

//Only needed when using the mAirlist RESTapi

$useMairlistRest = false; //not implemented yet
$mairlistIP = '192.168.1.79';
$mairlistPort = '9300';
$mairlistUser = 'test';
$mairlistPassword = '2XGDoAD6XbJ5wMK5';

//Always needed

$limitApiAccess = true; //Protect acccess to the getrequest api calls
$allowIpApi = '::1'; //IP used by mAirlist PC to query rest api

$uploadCsvSecret = 'changeme'; //secret needed to upload CSV

//Max number of requests active for one ip address
$numberofActiveRequests = 3;

?>