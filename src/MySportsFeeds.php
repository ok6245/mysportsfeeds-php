<?php

namespace MySportsFeeds;

class MySportsFeeds {

    public $buildVersion = "2.1.1"; // PHP Wrapper version
    
    private $api_version;
    private $verbose;
    private $storeType;
    private $storeLocation;
    private $apiInstance;

    public function __construct($api_version = "1.2", $verbose = false, $storeType = "file",
                              $storeLocation = "results/") {

        $this->__verifyStore($storeType, $storeLocation);

        $this->api_version = $api_version;
        $this->verbose = $verbose;
        $this->storeType = $storeType;
        $this->storeLocation = $storeLocation;
        $this->apiInstance = ApiFactory::create($this->api_version, $this->verbose, $this->storeType, $this->storeLocation);
    }

    # Verify the type and location of the stored data
    private function __verifyStore($storeType, $storeLocation) {
        if ($storeType !== null && $storeType != "file") {
            throw new \ErrorException("Unrecognized storage type specified.  Supported values are: {null,'file'}");
        }

        if ($storeType == "file") {
            if ($storeLocation === null) {
                throw new \ErrorException("Must specify a location for stored data.");
            }
        }
    }

    # Return an array of feed settings in this API version, indexed by feed name.
    public function getFeedsList() {
        return $this->apiInstance->getFeedsList();
    }

    # Get the HTTP response code from the most recent call to getData()
    public function getHTTPCode() {
        return $this->apiInstance->getHTTPCode();
    }

    # Get the URL used for the most recent call to getData()
    public function getURL() {
        return $this->apiInstance->getURL();
    }

    # Authenticate against the API (for v1.x, v2.x)
    public function authenticate($apikey, $password) {
        if (!$this->apiInstance->supportsBasicAuth()) {
            throw new \ErrorException("BASIC authentication not supported for API version " + $this->api_version);
        }

        $this->apiInstance->setAuthCredentials($apikey, $password);
    }

    # Request data (and store it if applicable)
    public function getData($league, $season, $feed, $format, ...$kvParams) {

        return $this->apiInstance->getData($league, $season, $feed, $format, ...$kvParams);
    }
}
