<?php

namespace MySportsFeeds;

class API_v1_0 extends BaseApi {

    # Constructor
    public function __construct($version, $verbose, $storeType = null, $storeLocation = null) {

        parent::__construct($version, $verbose, $storeType, $storeLocation);

        $this->validFeeds = [
            'cumulative_player_stats'  => ['season' => true,  'pathparms' => [], 'endpoint' => 'cumulative_player_stats'],
            'full_game_schedule'       => ['season' => true,  'pathparms' => [], 'endpoint' => 'full_game_schedule'],
            'daily_game_schedule'      => ['season' => true,  'pathparms' => [], 'endpoint' => 'daily_game_schedule'],
            'daily_player_stats'       => ['season' => true,  'pathparms' => [], 'endpoint' => 'daily_player_stats'],
            'game_boxscore'            => ['season' => true,  'pathparms' => [], 'endpoint' => 'game_boxscore'],
            'game_playbyplay'          => ['season' => true,  'pathparms' => [], 'endpoint' => 'game_playbyplay'],
            'scoreboard'               => ['season' => true,  'pathparms' => [], 'endpoint' => 'scoreboard'],
            'game_startinglineup'      => ['season' => true,  'pathparms' => [], 'endpoint' => 'game_startinglineup'],
            'player_gamelogs'          => ['season' => true,  'pathparms' => [], 'endpoint' => 'player_gamelogs'],
            'team_gamelogs'            => ['season' => true,  'pathparms' => [], 'endpoint' => 'team_gamelogs'],
            'active_players'           => ['season' => true,  'pathparms' => [], 'endpoint' => 'active_players'],
            'overall_team_standings'   => ['season' => true,  'pathparms' => [], 'endpoint' => 'overall_team_standings'],
            'conference_team_standings'=> ['season' => true,  'pathparms' => [], 'endpoint' => 'conference_team_standings'],
            'division_team_standings'  => ['season' => true,  'pathparms' => [], 'endpoint' => 'division_team_standings'],
            'playoff_team_standings'   => ['season' => true,  'pathparms' => [], 'endpoint' => 'playoff_team_standings'],
            'player_injuries'          => ['season' => true,  'pathparms' => [], 'endpoint' => 'player_injuries'],
            'latest_updates'           => ['season' => true,  'pathparms' => [], 'endpoint' => 'latest_updates'],
            'daily_dfs'                => ['season' => true,  'pathparms' => [], 'endpoint' => 'daily_dfs'],
            'current_season'           => ['season' => false, 'pathparms' => [], 'endpoint' => 'current_season'],
        ];
    }
}
