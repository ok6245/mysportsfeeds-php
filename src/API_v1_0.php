<?php

namespace MySportsFeeds;

class API_v1_0 extends BaseApi {
    protected function getBaseUrlForVersion($version)
    {
        return "https://api.mysportsfeeds.com/v1.0/pull";
    }
}
