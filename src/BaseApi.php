<?php

namespace MySportsFeeds;

class BaseApi
{
    protected $auth;

    protected $baseUrl;
    protected $verbose;
    protected $verboseEOL;   // EOL char(s) depending on running env: cli or webserver
    protected $storeType;
    protected $storeLocation;
    protected $storeOutput;
    protected $version;
    protected $httpCode;  // getData() sets this to HTTP status code from server
    protected $url;       // getData() sets this full URL used for the request
    
    /**
     * Valid feeds is an array of feed names and the options needed to create 
     * a portion of the "path" part of a complete URL for each feed. 
     * (https://developer.mozilla.org/en-US/docs/Learn/Common_questions/Web_mechanics/What_is_a_URL)
     * 
     * Each entry in the array has this format:
     *   'feed_name' => [
     *      'season'    => true/false,
     *      'pathparms' => an array of 0 or more parms that must appear in the path portion of the URL,
     *                     one pathparm could look like "games/game", where
     *                     'games' is the pathparm name as will appearn in the URL path, and
     *                     'game' is the name of the caller's parameter that contains this pathparm's value,
     *      'endpoint'  => the service endpoint for the feed
     *   ]
     * 
     * Example entry:
     *   'example_feed' => [
     *       'season'    => true,                             // is season value required?
     *       'pathparms' => ['exdate/date', 'exgames/game'],  // required path parms
     *       'endpoint'  => 'example_service'       
     *   ]
     * 
     * The example might result in this portion of the URL:
     *   2020-regular/exdate/20200410/exgames/20161221-BAL-DET/example_service
     * 
     * The complete URL for this example might look like:
     *    https://api.mysportsfeeds.com/v2.1/pull/mlb/2024-regular/exdate/20240410/exgames/20161221-BAL-DET/example_service.json?force=true
     */
    protected $validFeeds = [];

    # Constructor
    public function __construct($version, $verbose, $storeType = null, $storeLocation = null) {

        $this->auth = null;
        $this->verbose = $verbose;
        $this->verboseEOL = (php_sapi_name() == 'cli') ?  "\n" : "<br>";  // not perfect but helps
        $this->storeType = $storeType;
        $this->storeLocation = $storeLocation;
        $this->version = $version;
        $this->httpCode = 0;   // set by getData()
        $this->url      =  ''; // set by getData()
        $this->baseUrl = $this->getBaseUrlForVersion($version);

        $this->validFeeds = [];  // API subclasses define the feeds for each API version
    }

    protected function getBaseUrlForVersion($version)
    {
        return "https://api.mysportsfeeds.com/v{$version}/pull";
    }

    # Verify a feed name
    protected function __verifyFeedName($feed) {
        return (array_key_exists($feed, $this->validFeeds));
    }

    # Verify output format
    protected function __verifyFormat($format) {
        return ( $format == "json" ||  $format == "xml" || $format == "csv" );
    }

    /**
     * Create the complete URL for this feed request.
     * 
     * @param string $league       'mlb' | 'nfl' | ...
     * @param string $season       season string like '2024-regular'
     * @param string $feed         feed name
     * @param string $outputFormat 'csv' | 'json' | 'xml' 
     * @param array $params        array of  parm name => parm value entries
     * @return string              the complete URL built from the calling parameters
     * @throws \ErrorException
     */
    protected function __determineUrl($league, $season, $feed, $outputFormat, $params) {
        if (! $this->__verifyFeedName($feed)) {
            throw new \ErrorException("Unrecognized feed name '{$feed}' for API version {$this->version}");
        }

        # The parameters array contains all parameters (key/value pairs) that are used
        # to create the complete URL. Some of those parameters may go into the
        # "path" portion of the URL and the others may go in the "arguments" portion
        # of the URL:
        #    api.mysportsfeeds.com/path?arguments

        # Get this feed's settings
        $feed_settings = $this->validFeeds[$feed];

        # SEASONSTRING 
        # if season required, make the season string
        #   like ""  or  "2020/"  or "2024-regular/"
        $seasonstring = '';
        if ($feed_settings['season']) {
            if ($season == '') {
                throw new \ErrorException("You must specify a season for this request.");
            }
            $seasonstring = "{$season}/";
        }
        
        # PATHPARMS
        # Translate required pathparms to URL path snippets, e.g.,
        #   pathparm "games/game"  =>  "games/20240410/"
        # Remove each pathparm from PARAMS array as you go.
        # Concatenate all the pieces to create one string for the URL
        # Examples:  "" or "date/20200410/" or "date/20200410/team/NYY/"
        $pieces = array();
        foreach ($feed_settings['pathparms'] as $pathparm) {
            $rc = preg_match('/([a-z_]+)\/([a-z_]+)$/', $pathparm, $matches);
            if ($rc === false || $rc == 0) {
                throw new \ErrorException("Path parm '$pathparm' is not of form 'name/parm'.");
            }
            $name = $matches[1];
            $parm = $matches[2];
            if (! array_key_exists($parm, $params)  ||  ($params[$parm] == '')) {
                throw new \ErrorException("The required '{$parm}' parameter is either missing or has an empty value.");
            }
            $piece = "{$name}/{$params[$parm]}/";
            if ($this->verbose) {
                print("Required pathparm '$pathparm', path piece '$piece'{$this->verboseEOL}");
            }
            $pieces[] = $piece;

            # Remove each required pathparm from PARAMS array, will leave only optional parms in PARAMS array
            unset($params[$parm]);
        }
        $pathparms = (count($pieces) > 0) ? implode('', $pieces) : '';
            
        # ENDPOINT 
        #    like "daily_games"
        $endpoint = $feed_settings['endpoint'];

        # OPTIONS
        #   like ""  or "?team=MIN"  or "?team=BAL,NYY&force=true"
        # What's left in PARAMS array now are optional parameters
        $opts = array();
        foreach ($params as $parm => $value) {
            $opt = "{$parm}={$value}";
            $opts[] = $opt;
            if ($this->verbose) {
                print("Using optional parameter '$opt'{$this->verboseEOL}");
            }
        }
        $options = (count($opts) > 0) ?  '?' . implode('&', $opts)  :  '';

        # Put it all together
        return "{$this->baseUrl}/{$league}/{$seasonstring}{$pathparms}{$endpoint}.{$outputFormat}{$options}";
    }

    
    # Generate the appropriate filename for a feed request
    protected function __makeOutputFilename($league, $season, $feed, $outputFormat, $params) {

        $filename = $feed . "-" . $league . "-" . $season;

        if ( array_key_exists("gameid", $params) ) {
            $filename .= "-" . $params["gameid"];
        }

        if ( array_key_exists("fordate", $params) ) {
            $filename .= "-" . $params["fordate"];
        }

        if (array_key_exists("game", $params)) {
            $filename .= "-" . $params["game"];
        }

        if (array_key_exists("date", $params)) {
            $filename .= "-" . $params["date"];
        }

        if (array_key_exists("week", $params)) {
            $filename .= "-" . $params["week"];
        }

        $filename .= "." . $outputFormat;

        if ($this->verbose) {
            print("Filename will be '$filename'{$this->verboseEOL}");
        }

        return $filename;
    }

    # Save a feed response based on the store_type
    protected function __saveFeed($response, $league, $season, $feed, $outputFormat, $params) {

        # Save to memory regardless of selected method
        if ( $outputFormat == "json" ) {
            $this->storeOutput = (array) json_decode($response);
        } elseif ( $outputFormat == "xml" ) {
            $this->storeOutput = simplexml_load_string($response);
        } elseif ( $outputFormat == "csv" ) {
            $this->storeOutput = $response;
        }

        if ( $this->storeType == "file" ) {
            if ( ! is_dir($this->storeLocation) ) {
                mkdir($this->storeLocation, 0, true);
            }

            $filename = $this->__makeOutputFilename($league, $season, $feed, $outputFormat, $params);
            $nbytes = file_put_contents($this->storeLocation . $filename, $response);

            if ($this->verbose) {
                if ($nbytes === false) {
                    print("Failed to write output file {$filename}{$this->verboseEOL}");
                }
                else {
                    print("Output file written: $filename ($nbytes bytes){$this->verboseEOL}");
                }
            }
        }
    }

    # Indicate this version does support BASIC auth
    public function supportsBasicAuth() {
        return true;
    }

    # Establish BASIC auth credentials
    public function setAuthCredentials($apikey, $password) {
        $this->auth = ['username' => $apikey, 'password' => $password];
    }

    # Get the feeds for this API version
    public function getFeedsList() {
        return $this->validFeeds;
    }

    # Get the HTTP status code from the most recent call to getData()
    public function getHTTPCode() {
        return $this->httpCode;
    }

    # Get the full URL used by the most recent call to getData()
    public function getURL() {
        return $this->url;
    }

    # Request data (and store it if applicable)
    public function getData($league, $season, $feed, $format, ...$kvParams) {

        if (! $this->auth) {
            throw new \ErrorException("You must authenticate() before making requests.");
        }

        if (! $this->__verifyFeedName($feed)) {
            throw new \ErrorException("Unknown version {$this->version} feed '" . $feed . "'.  Supported values are: [" . print_r(array_keys($this->validFeeds), true) . "]");
        }

        if (! $this->__verifyFormat($format)) {
            throw new \ErrorException("Unsupported format '" . $format . "'.");
        }

        # make more convenient assoc array [key => value, ...] from caller array of strings ["key=value", ...]
        $params = [];
        foreach ($kvParams as $kvPair) {
            $pieces = explode("=", $kvPair);
            if (count($pieces) <> 2) {
              throw new \ErrorException("Optional parameter '{$kvPair}' is invalid, must be of form 'xxxx=yyyyyyy'");
            }
            $key = trim($pieces[0]);
            $value = trim($pieces[1]);
            $params[$key] = $value;
        }

        # add force=false parameter (helps prevent unnecessary bandwidth use)
	    # Only adds if storeType == file, else you won't have any data to retrieve.
        if ( ! array_key_exists("force", $params) ) {
	        if ( $this->storeType == "file" ) {
		        $params['force'] = 'false';
	        } else {
		        $params['force'] = 'true';
	        }
        }

        # Make the full URL for this request
        $this->url = $this->__determineUrl($league, $season, $feed, $format, $params);

        if ($this->verbose) {
            print("Making API request to '{$this->url}'{$this->verboseEOL}");
        }

        // Establish a curl handle for the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); // Enable compression
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // If you have issues with SSL verification
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . base64_encode($this->auth['username'] . ":" . $this->auth['password'])
        ]); // Authenticate using HTTP Basic with account credentials

        // Send the request & retrieve response
        $resp = curl_exec($ch);

        // Uncomment the following if you're having trouble:
        // print(curl_error($ch));

        // Get the HTTP response status code then close the curl handle
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = "";

        if ($this->httpCode == 200) {
            // Fixes MySportsFeeds/mysportsfeeds-php#1
            // Remove if storeType == null so data gets stored in memory regardless.
            $this->__saveFeed($resp, $league, $season, $feed, $format, $params);

            $data = $this->storeOutput;
        } elseif ($this->httpCode == 304) {
            if ($this->verbose) {
                print("Data hasn't changed since last call.{$this->verboseEOL}");
            }

            $filename = $this->__makeOutputFilename($league, $season, $feed, $format, $params);

            $data = file_get_contents($this->storeLocation . $filename);

            if ($format == "json") {
                $this->storeOutput = (array) json_decode($data);
            } elseif ($format == "xml") {
                $this->storeOutput = simplexml_load_string($data);
            } elseif ($format == "csv") {
                $this->storeOutput = $data;
            }

            $data = $this->storeOutput;
        } else {
            throw new \ErrorException("API call failed with HTTP status code: {$this->httpCode}");
        }

        return $data;
    }

}
