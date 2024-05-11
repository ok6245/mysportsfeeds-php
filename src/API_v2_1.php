<?php

namespace MySportsFeeds;

class API_v2_1 extends API_v2_0 {

    # Constructor
    public function __construct($version, $verbose, $storeType = null, $storeLocation = null) {

        parent::__construct($version, $verbose, $storeType, $storeLocation);

        /**
         * See BaseApi for syntax
         */
        $this->validFeeds = [
            /* CORE */
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
            'current_season' => [
                'season'   => false,
                'pathparms'=> [],
                'endpoint' => 'current_season',
            ],
            'latest_updates' => [
                'season'   => true,
                'pathparms'=> [],
                'endpoint' => 'latest_updates',
            ],
            'seasonal_venues' => [
                'season'   => true,
                'pathparms'=> [],
                'endpoint' => 'venues',
            ],
            /* STATS */
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
            'seasonal_player_gamelogs' => [
                'season'   => true,
                'pathparms'=> [],
                'endpoint' => 'player_gamelogs',
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
            'seasonal_team_gamelogs' => [
                'season'   => true,
                'pathparms'=> [],
                'endpoint' => 'team_gamelogs',
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
            'seasonal_standings' => [
                'season'   => true,
                'pathparms'=> [],
                'endpoint' => 'standings',
            ],
            /* DETAILED */
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
            'player_injuries' => [
                'season'   => false,
                'pathparms'=> [],
                'endpoint' => 'injuries',
            ],
            'players' => [
                'season'   => false,
                'pathparms'=> [],
                'endpoint' => 'players',
            ],
            'injury_history' => [
                'season'   => false,
                'pathparms'=> [],
                'endpoint' => 'injury_history',
            ],
            /* ODDS */
            'daily_odds_gamelines' => [
                'season'   => true,
                'pathparms'=> ['date/date'],
                'endpoint' => 'odds_gamelines',
            ],
            'weekly_odds_gamelines' => [
                'season'   => true,
                'pathparms'=> ['week/week'],
                'endpoint' => 'odds_gamelines',
            ],
            'seasonal_odds_gamelines' => [
                'season'   => true,
                'pathparms'=> [],
                'endpoint' => 'odds_gamelines',
            ],
            'daily_odds_futures' => [
                'season'   => true,
                'pathparms'=> ['date/date'],
                'endpoint' => 'odds_futures',
            ],
            /* PROJECTIONS */
            'daily_player_gamelogs_projections' => [
                'season'   => true,
                'pathparms'=> ['date/date'],
                'endpoint' => 'player_gamelogs_projections',
            ],
            'weekly_player_gamelogs_projections' => [
                'season'   => true,
                'pathparms'=> ['week/week'],
                'endpoint' => 'player_gamelogs_projections',
            ],
            'daily_dfs_projections' => [
                'season'   => true,
                'pathparms'=> ['date/date'],
                'endpoint' => 'dfs_projections',
            ],
            'weekly_dfs_projections' => [
                'season'   => true,
                'pathparms'=> ['week/week'],
                'endpoint' => 'dfs_projections',
            ],
            'seasonal_player_stats_projections' => [
                'season'   => true,
                'pathparms'=> ['week/week'],
                'endpoint' => 'player_stats_totals_projections',
            ],
            /* DFS */
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
            'seasonal_dfs' => [
                'season'   => true,
                'pathparms'=> [],
                'endpoint' => 'dfs',
            ],
        ];
    }
}
