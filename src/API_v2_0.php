<?php

namespace MySportsFeeds;

class API_v2_0 extends API_v1_2 {

    # Constructor
    public function __construct($version, $verbose, $storeType = null, $storeLocation = null) {

		parent::__construct($version, $verbose, $storeType, $storeLocation);

        $this->validFeeds = [
            'seasonal_games' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'games',
            ],
            'daily_games' => [
                'season'   => true, 
                'pathparms'=> ['date/date'],
                'endpoint' => 'games',
            ],
            'weekly_games' => [
                'season'   => true, 
                'pathparms'=> ['week/week'],
                'endpoint' => 'games',
            ],
            'seasonal_dfs' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'dfs',
            ],
            'daily_dfs' => [
                'season'   => true, 
                'pathparms'=> ['date/date'], 
                'endpoint' => 'dfs',
            ],
            'weekly_dfs' => [
                'season'   => true, 
                'pathparms'=> ['week/week'], 
                'endpoint' => 'dfs',
            ],
            'seasonal_player_gamelogs' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'player_gamelogs',
            ],
            'daily_player_gamelogs' => [
                'season'   => true, 
                'pathparms'=> ['date/date'],
                'endpoint' => 'player_gamelogs',
            ],
            'weekly_player_gamelogs' => [
                'season'   => true, 
                'pathparms'=> ['week/week'],
                'endpoint' => 'player_gamelogs',
            ],
            'seasonal_team_gamelogs' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'team_gamelogs',
            ],
            'daily_team_gamelogs' => [
                'season'   => true, 
                'pathparms'=> ['date/date'],
                'endpoint' => 'team_gamelogs',
            ],
            'weekly_team_gamelogs' => [
                'season'   => true, 
                'pathparms'=> ['week/week'],
                'endpoint' => 'team_gamelogs',
            ],
            'game_boxscore' => [
                'season'   => true, 
                'pathparms'=> ['games/game'],
                'endpoint' => 'boxscore',
            ],
            'game_playbyplay' => [
                'season'   => true, 
                'pathparms'=> ['games/game'],
                'endpoint' => 'playbyplay',
            ],
            'game_lineup' => [
                'season'   => true, 
                'pathparms'=> ['games/game'],
                'endpoint' => 'lineup',
            ],
            'current_season' => [
                'season'   => false, 
                'pathparms'=> [], 
                'endpoint' => 'current_season',
            ],
            'player_injuries' => [
                'season'   => false,
                'pathparms'=> [],
                'endpoint' => 'injuries',
            ],
            'latest_updates' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'latest_updates',
            ],
            'seasonal_team_stats' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'team_stats_totals',
            ],
            'seasonal_player_stats' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'player_stats_totals',
            ],
            'seasonal_venues' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'venues',
            ],
            'players' => [
                'season'   => false,
                'pathparms'=> [],
                'endpoint' => 'players',
            ],
            'seasonal_standings' => [
                'season'   => true, 
                'pathparms'=> [], 
                'endpoint' => 'standings',
            ],
        ];
    }
}
