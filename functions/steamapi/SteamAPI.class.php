<?php

class SteamAPI
{

	const version = '1.0';
	private $ids = '';
	private $api_key;
	private $pre_url = 'https://api.steampowered.com/';


	public function __construct($api_key)
	{
		if (!empty($api_key)) {
			$this->api_key = $api_key;
		}

		echo "<script>
				console.info('SteamAPI v." . self::version . " successfully loaded!');
			</script>";
	}

	###################
	# GENERAL METHODS #
	###################

	// original had error $this expected as array but was string. from line 7.
	private function add_steam_id($steamid)
	{
		if (strlen($this->ids) == 0) {
			$this->ids .= $steamid;
		} else {
			$this->ids .= ',' . $steamid;
		}
	}

	public function set_api_key($api_key)
	{
		$this->api_key = $api_key;
	}

	private function get_content($uri)
	{
		try {
			$content = file_get_contents($uri);
			$content = json_decode($content, true);
			return $content;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	private function array_steamids($steamids)
	{
		$required = ',';
		if (str_contains($steamids, $required)) {
			return explode(',', $steamids);
		} else {
			return array($steamids); // if there is no comma, there is only one value. We can return the string as an array
		}
	}


	###############
	# API METHODS #
	###############

	public function GetPlayerInfo($steamids)
	{
		$url = $this->pre_url . 'ISteamUser/GetPlayerSummaries/v0002/?key=' . $this->api_key . '&steamids=' . $steamids;
		$contents = $this->get_content($url);
		$contents = $contents['response']['players'];

		$visibility = array(
			"1" => "Private",
			"2" => "FriendsOnly",
			"3" => "FriendsOfFriends",
			"4" => "UsersOnly",
			"5" => "Public"
		);

		$status = array(
			"0" => "Offline",
			"1" => "Online",
			"2" => "Busy",
			"3" => "Away",
			"4" => "Snooze",
			"5" => "LookingToTrade",
			"6" => "LookingToPlay"
		);

		$countries = $this->get_content('steam_countries.json');

		$players = array();

		foreach ($contents as $it => $p) {
			$player = [];
			$player['steamid'] = $p['steamid'];
			$player['personaname'] = utf8_decode($p['personaname']);
			$player['profileurl'] = $p['profileurl'];
			$player['avatar'] = $p['avatar'];
			$player['avatarmedium'] = $p['avatarmedium'];
			$player['avatarfull'] = $p['avatarfull'];
			$player['personastate'] = $status[$p['personastate']];
			$player['communityvisibilitystate'] = $visibility[$p['communityvisibilitystate']];
			$player['profilestate'] = $p['profilestate'];
			$player['lastlogoff'] = gmdate("m-d-Y H:i:s", $p['lastlogoff']);
			if (!empty($p['realname'])) $player['realname'] = utf8_decode($p['realname']);
			if (!empty($p['commentpermission'])) $player['commentpermission'] = $p['commentpermission'];
			if (!empty($p['primaryclanid'])) $player['primaryclanid'] = $p['primaryclanid'];
			if (!empty($p['timecreated'])) $player['timecreated'] = gmdate("m-d-Y H:i:s", $p['timecreated']);
			if (!empty($p['gameid'])) $player['gameid'] = $p['gameid'];
			if (!empty($p['gameserverip'])) $player['gameserverip'] = $p['gameserverip'];
			if (!empty($p['gameextrainfo'])) $player['gameextrainfo'] = $p['gameextrainfo'];
			if (!empty($p['cityid'])) $player['cityid'] = $p['cityid'];
			if (!empty($p['loccountrycode'])) $player['loccountrycode'] = $p['loccountrycode'];
			if (!empty($p['locstatecode'])) $player['locstatecode'] = $countries[$p['loccountrycode']]['states'][$p['locstatecode']]['name'];
			if (!empty($p['loccityid'])) $player['loccityid'] = $countries[$p['loccountrycode']]['states'][$p['locstatecode']]['cities'][$p['loccityid']]['name'];
			array_push($players, $player);
		}

		return $players;
	}


	public function GetPlayerLevel($steamids)
	{
		$steamids = $this->array_steamids($steamids);

		$players = [];

		foreach ($steamids as $it => $id) {
			$player = [];
			$url = $this->pre_url . 'IPlayerService/GetSteamLevel/v1/?key=' . $this->api_key . '&steamid=' . $id;
			$handler = $this->get_content($url);

			$player['steamid'] = $id;
			$player['level'] = $handler['response']['player_level'];
			array_push($players, $player);
		}

		return $players;
	}


	public function GetPlayerGames($steamids)
	{
		$steamids = $this->array_steamids($steamids);

		$return = [];

		foreach ($steamids as $id) {
			$player = [];
			$url = $this->pre_url . 'IPlayerService/GetOwnedGames/v0001/?key=' . $this->api_key . '&steamid=' . $id . '&format=json&include_appinfo=1';
			$handler = $this->get_content($url);

			foreach ($handler as $h) {
				$player['steamid'] = $id;
				$player['game_count'] = $h['game_count'];
				$player['games'] = [];
				foreach ($h['games'] as $g) {
					$game = [];
					$game['appid'] = $g['appid'];
					$game['name'] = $g['name'];
					$game['playtime_minutes'] = $g['playtime_forever'];
					$game['playtime_hours'] = number_format($g['playtime_forever'] / 60, 2);
					if (!empty($g['playtime_2weeks'])) $game['playtime_2weeks_minutes'] = $g['playtime_2weeks'];
					if (!empty($g['playtime_2weeks'])) $game['playtime_2weeks_hours'] = number_format($g['playtime_2weeks'] / 60, 2);
					if (!empty($g['has_community_visible_stats'])) {
						$game['has_community_visible_stats'] = $g['has_community_visible_stats'];
						$game['community_stats_url'] = 'http://steamcommunity.com/profiles/' . $id . '/stats/' . $g['appid'];
					}
					if (!empty($g['img_icon_url'])) $game['app_icon_url'] = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/' . $g['appid'] . '/' . $g['img_icon_url'] . '.jpg';
					if (!empty($g['img_logo_url'])) $game['app_logo_url'] = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/' . $g['appid'] . '/' . $g['img_logo_url'] . '.jpg';
					array_push($player['games'], $game);
				}
			}

			array_push($return, $player);
		}

		return $return;
	}


	public function GetRecentPlayedGames($steamids, $limit = 3)
	{
		$steamids = $this->array_steamids($steamids);

		$return = [];

		foreach ($steamids as $id) {
			$player = [];
			$url = $this->pre_url . 'IPlayerService/GetRecentlyPlayedGames/v0001/?key=' . $this->api_key . '&steamid=' . $id . '&format=json&count=' . $limit;
			$handler = $this->get_content($url);

			foreach ($handler as $h) {
				$player['steamid'] = $id;
				$player['total_count'] = $h['total_count'];
				$player['games'] = [];
				foreach ($h['games'] as $g) {
					$game = [];
					$game['appid'] = $g['appid'];
					$game['name'] = $g['name'];
					$game['playtime_minutes'] = $g['playtime_forever'];
					$game['playtime_hours'] = number_format($g['playtime_forever'] / 60, 2);
					if (!empty($g['playtime_2weeks'])) $game['playtime_2weeks_minutes'] = $g['playtime_2weeks'];
					if (!empty($g['playtime_2weeks'])) $game['playtime_2weeks_hours'] = number_format($g['playtime_2weeks'] / 60, 2);
					if (!empty($g['has_community_visible_stats'])) {
						$game['has_community_visible_stats'] = $g['has_community_visible_stats'];
						$game['community_stats_url'] = 'http://steamcommunity.com/profiles/' . $id . '/stats/' . $g['appid'];
					}
					if (!empty($g['img_icon_url'])) $game['app_icon_url'] = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/' . $g['appid'] . '/' . $g['img_icon_url'] . '.jpg';
					if (!empty($g['img_logo_url'])) $game['app_logo_url'] = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/' . $g['appid'] . '/' . $g['img_logo_url'] . '.jpg';
					array_push($player['games'], $game);
				}
			}

			array_push($return, $player);
		}

		return $return;
	}


	public function GetFriendsList($steamids)
	{
		$steamids = $this->array_steamids($steamids);

		$return = [];

		foreach ($steamids as $id) {
			$player = [];
			$url = $this->pre_url . 'ISteamUser/GetFriendList/v0001/?key=' . $this->api_key . '&steamid=' . $id . '&format=json&relationship=friend';
			$handler = $this->get_content($url);

			foreach ($handler as $h) {
				$player = [];
				$player['my_steamid'] = $id;
				$player['friends'] = [];
				foreach ($h['friends'] as $f) {
					$friend = [];
					$friend['steamid'] = $f['steamid'];
					$friend['relationship'] = $f['relationship'];
					$friend['friend_since'] = gmdate("Y-m-d H:i:s", $f['friend_since']);
					array_push($player['friends'], $friend);
				}
			}

			array_push($return, $player);
		}

		return $return;
	}


	public function GetGamePrice($appid, $currency = [])
	{
		$country_codes = array(
			'ae', 'au', 'br', 'cn', 'dk', 'es', 'gb', 'hr', 'ie', 'ir', 'jp', 'lt', 'ly', 'mx', 'no', 'ph', 'pt', 'rs', 'se', 'sk', 'tw', 'ar',
			'be', 'ca', 'cz', 'dz', 'fi', 'gr', 'hu', 'il', 'is', 'kr', 'lu', 'mk', 'my', 'nz', 'pk', 're', 'ru', 'sg', 'th', 'ua', 'za', 'at',
			'bg', 'cl', 'de', 'ee', 'fr', 'hk', 'id', 'in', 'it', 'kz', 'lv', 'mo', 'nl', 'pe', 'pl', 'ro', 'sa', 'si', 'tr', 'us', 'by'
		);

		$array_countries = [];


		if (!empty($currency) && is_array($currency)) {
			$array_countries = $currency;
		} else {
			$array_countries = $country_codes;
		}

		$prices = [];
		foreach ($array_countries as $code) {
			$url = 'http://store.steampowered.com/api/appdetails?appids=' . $appid . '&filters=price_overview&cc=' . $code;
			$handler = $this->get_content($url);

			if ($handler[$appid]['success']) {
				foreach ($handler[$appid]['data'] as $h) {
					$price = [];
					$price['currency'] = $h['currency'];
					$price['initial'] = $h['initial'];
					$price['final'] = $h['final'];
					$price['discount_percent'] = $h['discount_percent'];
					array_push($prices, $price);
				}
			}
		}

		return $prices;
	}


	public function GetPlayerBans($steamids)
	{
		$steamids = $this->array_steamids($steamids);

		$bans = [];

		foreach ($steamids as $id) {
			$url = $this->pre_url . 'ISteamUser/GetPlayerBans/v1?key=' . $this->api_key . '&steamids=' . $id;
			$bans[] = $this->get_content($url)['players'][0];
		}

		return $bans;
	}


	public function GetGameNumberOfPlayers($appid)
	{
		$url = $this->pre_url . 'ISteamUserStats/GetNumberOfCurrentPlayers/v1?appid=' . $appid;
		$count = $this->get_content($url);

		if ($count) {
			return $count['response']['result'] ? $count['response']['player_count'] : false;
		}
		return false;
	}


	public function GetPlayerBadges($steamids)
	{
		$steamids = $this->array_steamids($steamids);

		$players = [];

		foreach ($steamids as $id) {
			$url = $this->pre_url . 'IPlayerService/GetBadges/v1?key=' . $this->api_key . '&steamid=' . $id;
			$handler = $this->get_content($url);

			$player = [];

			foreach ($handler as $h) {
				$player['steamid'] = $id;
				$player['player_xp'] = $h['player_xp'];
				$player['player_level'] = $h['player_level'];
				$player['player_xp_needed_to_level_up'] = $h['player_xp_needed_to_level_up'];
				$player['player_xp_needed_current_level'] = $h['player_xp_needed_current_level'];
				$player['badges'] = [];

				foreach ($h['badges'] as $key => $badge) {
					foreach ($badge as $k => $b) {
						if ($k && $b) {
							if ($k == 'completion_time') {
								$player['badges'][$key][$k] = gmdate("m-d-Y H:i:s", $b);
							} else {
								$player['badges'][$key][$k] = $b;
							}
						}
					}
				}

				array_push($players, $player);
			}
		}

		return $players;
	}


	public function GetPlayersFriendship($id1, $id2)
	{
		$friendsPlayer1 = $this->GetFriendsList($id1);
		$players = $this->GetPlayerInfo($id1 . ',' . $id2);
		$flag = false;

		foreach ($friendsPlayer1[0]['friends'] as $k => $v) {
			if ($v['steamid'] == $id2) {
				$flag = true;
				$friend_since = $v['friend_since'];
			}
		}

		$return = new stdClass();

		if ($flag) {
			$return->msg = 'success';
			$return->player_1 = $players[0]['personaname'];
			$return->player_2 = $players[1]['personaname'];
			$return->friends_since = $friend_since;

			date_default_timezone_set('America/Los_Angeles');
			$_since = new DateTime($friend_since);
			$_now = new DateTime('now');
			$diff = $_since->diff($_now);

			$return->friendship_time = $diff->format('%Y years, %m months and %d days');
			$return->friendship_days = $diff->days;
		} else {
			$return->msg = 'error';
		}

		return $return;
	}


	public function news($app_id, $count = 3, $max_length = 1000)
	{
		$url = $this->pre_url . "ISteamNews/GetNewsForApp/v0002/?appid={$app_id}&count={$count}&maxlength={$max_length}&format=json";

		$handler = $this->get_content($url);

		return $handler;
	}


	public function playesStatsForGame($steamid, $appid)
	{

		$url = $this->pre_url . "ISteamUserStats/GetUserStatsForGame/v0002/?appid={$appid}&key={$this->api_key}&steamid={$steamid}";

		$handler = $this->get_content($url);

		return $handler;
	}


	public function getSchemaForGame($appid)
	{

		$url = $this->pre_url . "ISteamUserStats/GetSchemaForGame/v2/?key={$this->api_key}&appid={$appid}";

		$handler = $this->get_content($url);

		return $handler;
	}
}
