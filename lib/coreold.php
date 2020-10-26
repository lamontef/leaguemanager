<?php
/**
 * Core class for the WordPress plugin LeagueManager
 * 
 * @author 	Kolja Schleich
 * @package	LeagueManager
 * @copyright Copyright 2008
*/
class LeagueManager
{
	/**
	 * array of leagues
	 *
	 * @var array
	 */
	var $leagues = array();
	

	/**
	 * data of certain league
	 *
	 * @var array
	 */
	var $league = array();


	/**
	 * ID of current league
	 *
	 * @var int
	 */
	var $league_id = null;

	
	/**
	 * current season
	 *
	 * @var mixed
	 */
	var $season;


	/**
	 * error handling
	 *
	 * @var boolean
	 */
	var $error = false;
	
	
	/**
	 * message
	 *
	 * @var string
	 */
	var $message;
	
	
	/**
	 * control variable if bridge is active
	 *
	 * @var boolean
	 */
	var $bridge = false;

	
	/**
	 * number of matches
	 *
	 * @var int
	 */
	var $num_matches = null;
	
	
	/**
	 * number of matches per page
	 *
	 * @var int
	 */
	var $num_matches_per_page = 0;
	
	
	/**
	 * number of pages for matches
	 *
	 * @var int
	 */
	var $num_max_pages = 0;
	

	/**
	 * match day
	 *
	 * @var int
	 */
	var $match_day = null;
	
	
	/**
	 * Initializes plugin
	 *
	 * @param boolean $bridge
	 * @return void
	 */
	function __construct( $bridge = false )
	{
		$this->bridge = $bridge;
		if (isset($_GET['league_id'])) {
			$this->league = $this->getLeague($_GET['league_id']);
			$this->setLeagueID( intval($this->league->id) );
			$this->competition = $this->getCompetition(intval($this->league->competition_id));
		}
		if (isset($_GET['competition_id'])) {
			$this->setCompetitionID( intval($_GET['competition_id']) );
			$this->competition = $this->getCompetition(intval($this->getCompetitionID()));
		}

		$this->loadOptions();
	}
	function LeagueManager( $bridge = false )
	{
		$this->__construct( $bridge );
	}
	
	
	/**
	 * recursively remove directory
	 *
	 * @param string $dir
	 *
	 */
	function removeDir($dir)
	{
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files AS $file) {
			if (is_dir("$dir/$file"))
				$this->removeDir("$dir/$file");
			else
				@unlink("$dir/$file");
		}
		@rmdir($dir);
	}
	
	
	/**
	 * load options
	 *
	 * @param none
	 * @return void
	 */
	function loadOptions()
	{
		$this->options = get_option('leaguemanager');
	}
	
	
	/**
	 * get options
	 *
	 * @param none
	 * @return void
	 */
	function getOptions()
	{
		return $this->options;
	}
	
	
	/**
	 * check if bridge is active
	 *
	 * @param none
	 * @return boolean
	 */
	function hasBridge()
	{
		return $this->bridge;
	}
	
	/*
	 * set competition object
	 *
	 * @param object $competition
	 */
	function setCompetition( $competition )
	{
		$this->competition = $competition;
		$this->setCompetitionID( $competition->id );
	}
	
	
	/**
	 * set competition id
	 *
	 * @param int $competition_id
	 * @return void
	 */
	function setCompetitionID( $competition_id )
	{
		$this->competition_id = intval($competition_id);
	}
	
	
	/**
	 * retrieve competition ID
	 *
	 * @param none
	 * @return int ID of current competition
	 */
	function getCompetitionID()
	{
		return intval($this->competition_id);
	}
	
	
	/**
	 * get current competition object
	 *
	 * @param none
	 * @return object
	 */
	function getCurrentCompetition()
	{
		return $this->competition;
	}
	
	/**
	 * get current competition ID
	 *
	 * @param none
	 * @return int
	 */
	function getCurrentCompetitionID()
	{
		return intval($this->competition->id);
	}
	
	/*
	 * set league object
	 *
	 * @param object $league
	 */
	function setLeague( $league )
	{
		$this->league = $league;
		$this->setLeagueID( $league->id );
	}
	
	
	/**
	 * set league id
	 *
	 * @param int $league_id
	 * @return void
	 */
	function setLeagueID( $league_id )
	{
		$this->league_id = intval($league_id);
	}
	
	
	/**
	 * retrieve league ID
	 *
	 * @param none
	 * @return int ID of current league
	 */
	function getLeagueID()
	{
		return intval($this->league_id);
	}
	

	/**
	 * get current league object
	 *
	 * @param none
	 * @return object
	 */
	function getCurrentLeague()
	{
		return $this->league;
	}
	
	/**
	 * get current league ID
	 *
	 * @param none
	 * @return int
	 */
	function getCurrentLeagueID()
	{
		return intval($this->league->id);
	}
	
	/**
	 * set season
	 *
	 * @param mixed $season
	 * @return void
	 */
	function setSeason( $season )
	{
		$this->season = $season;
	}
	
	
	/**
	 * get current season
	 *
	 * @param mixed $index
	 * @return array
	 */
	function getCurrentSeason( $index = false )
	{
		if ( $index )
			return $this->season[$index];

		return $this->season;
	}


	/**
	 * get league types
	 *
	 * @param none
	 * @return array
	 */
	function getLeagueTypes()
	{
		$types = array( 'other' => __('Other', 'leaguemanager') );
		$types = apply_filters('leaguemanager_sports', $types);
		asort($types);

		return $types;
	}
	
	
	/**
	 * get supported image types from Image class
	 *
	 * @param none
	 * @return array
	 */
	function getSupportedImageTypes()
	{
		return LeagueManagerImage::getSupportedImageTypes();
	}
	
	
	/**
	 * build home only query
	 *
	 * @param int $league_id
	 * @return string MySQL search query
	 */
	function buildHomeOnlyQuery($league_id)
	{
		global $wpdb;
		
		if ( isset($this->home_only_query[$league_id]) )
			return $this->home_only_query[$league_id];
		
		$queries = array();
		$teams = $wpdb->get_results($wpdb->prepare("SELECT `id` FROM {$wpdb->leaguemanager_teams} WHERE `league_id` = '%d' AND `home` = 1", intval($league_id)) );
		if ( $teams ) {
			foreach ( $teams AS $team )
				$queries[] = $wpdb->prepare("`home_team` = '%d' OR `away_team` = '%d'", $team->id, $team->id);
		
			$query = " AND (".implode(" OR ", $queries).")";
			
			$this->home_only_query[$league_id] = $query;
			return $this->home_only_query[$league_id];
		}
		
		return false;
	}
	
	
	/**
	 * get months
	 *
	 * @param none
	 * @return void
	 */
	function getMonths()
	{
		$locale = get_locale();
		setlocale(LC_ALL, $locale);
		for ( $month = 1; $month <= 12; $month++ ) 
			$months[$month] = htmlentities( strftime( "%B", mktime( 0,0,0, $month, date("m"), date("Y") ) ) );
			
		return $months;
	}
	
	
	/**
	 * returns image directory
	 *
	 * @param string|false $file
	 * @param boolean $root
	 * @return string
	 */
	function getImagePath( $file = false, $root = false, $size = 'full' )
	{
		if ($root || $this->getLeagueID() == 0)
			$base = WP_CONTENT_DIR.'/uploads/leagues';
		else
			$base = WP_CONTENT_DIR.'/uploads/leagues/League-'.$this->getLeagueID();
			
		if ( $file ) {
			if ( $size == 'full' || $size == '' )
				$file = basename($file);
			else
				$file = $size . "_" . basename($file);
			
			return $base .'/'. $file;
		} else {
			return $base;
		}
		/*
		$league = $this->getCurrentLeague();
		if ( $file )
			return trailingslashit($_SERVER['DOCUMENT_ROOT']) . substr($file,strlen($_SERVER['HTTP_HOST'])+8, strlen($file));
		 else 
			return ABSPATH . $league->upload_dir;
		*/
	}
	
	
	/**
	 * returns url of image directory
	 *
	 * @param string|false $file image file
	 * @param boolean $root
	 * @return string
	 */
	function getImageUrl( $file = false, $root = false, $size = 'full' )
	{
		if ($root || $this->getLeagueID() == 0)
			$base = WP_CONTENT_URL.'/uploads/leagues';
		else
			$base = WP_CONTENT_URL.'/uploads/leagues/League-'.$this->getLeagueID();
			
		if ( $file ) {
			if ( $size == 'full' || $size == '' )
				$file = basename($file);
			else
				$file = $size . "_" . basename($file);
			
			if (file_exists($this->getImagePath($file, $root, '')))
				return esc_url($base .'/'. $file);
			else
				return false;
		} else {
			return esc_url($base);
		}
		/*
		$league = $this->getCurrentLeague();
		if ( $file )
			return trailingslashit(get_option('siteurl')) . trailingslashit($league->upload_dir) . $file;
		else
			return trailingslashit(get_option('siteurl')) . $league->upload_dir;
		*/
	}

	
	/**
	 * get Thumbnail image
	 *
	 * @param string $file
	 * @return string
	 */
	function getThumbnailUrl( $file )
	{
		if ( file_exists($this->getThumbnailPath($file)) )
			return $this->getImageUrl('thumb_'.basename($file));//trailingslashit(dirname($file)) . 'thumb_' . basename($file);
		else
			return $this->getImageUrl('thumb.'.basename($file));//return trailingslashit(dirname($file)) . 'thumb.' . basename($file);
	}

	
	/**
	 * get Thumbnail path
	 *
	 * @param string $file
	 * @return string
	 */
	function getThumbnailPath( $file )
	{
		return $this->getImagePath("thumb_" . basename($file));
		//return trailingslashit($_SERVER['DOCUMENT_ROOT']) . dirname(substr($file,strlen($_SERVER['HTTP_HOST'])+8, strlen($file))) . '/thumb_' . basename($file);
	}
	
	
	/**
	 * set message
	 *
	 * @param string $message
	 * @param boolean $error triggers error message if true
	 * @return none
	 */
	function setMessage( $message, $error = false )
	{
		$type = 'success';
		if ( $error ) {
			$this->error = true;
			$type = 'error';
		}
		$this->message[$type] = $message;
	}
	
	
	/**
	 * return message
	 *
	 * @param none
	 * @return string
	 */
	function getMessage()
	{
		if ( $this->error )
			return $this->message['error'];
		else
			return $this->message['success'];
	}
	
	
	/**
	 * print formatted message
	 *
	 * @param none
	 * @return string
	 */
	function printMessage()
	{
		if ( $this->error )
			echo "<div class='error'><p>".$this->getMessage()."</p></div>";
		else
			echo "<div id='message' class='updated fade'><p><strong>".$this->getMessage()."</strong></p></div>";
	}

	
	/**
	 * Set match day
	 *
	 * @param int 
	 * @return void
	 */
	function setMatchDay( $match_day )
	{
		$this->match_day = intval($match_day);
	}
	
	
	/**
	* retrieve match day
	 *
	 * @param none
	 * @return int
	 */
	function getMatchDay( $select = '' )
	{
		global $wpdb;
		
		$sql = "";
		
		$league_id = $this->getCurrentLeagueID();
		$season = isset($this->season['name']) ? $this->season['name'] : '';
		if ( isset($_GET['match_day']) ) {
			$match_day = intval($_GET['match_day']);
		} elseif ( isset($_GET['match_day_'.$league_id])) {
			$match_day = intval($_GET['match_day_'.$league_id]);
		} elseif (isset($_POST['match_day'])) {
			$match_day = intval($_POST['match_day']);
		} elseif ( $select == "last" ) {
			if ( isset($this->last_match_day[$league_id]) ) {
				$match_day = $this->last_match_day[$league_id];
			} else {
				$sql = "SELECT `match_day`, DATEDIFF(NOW(), `date`) AS datediff FROM {$wpdb->leaguemanager_matches} WHERE `league_id` = '%d' AND `season` = '%s' AND DATEDIFF(NOW(), `date`) > 0 ORDER BY datediff ASC";
				$matches = $wpdb->get_results( $wpdb->prepare($sql, $this->getLeagueID(), $season) );
				if ($matches) $match_day = $matches[0]->match_day;
				else $match_day = -1;
				
				$this->last_match_day[$league_id] = $match_day;
			}
		} elseif ( $select == "next" ) {
			if ( isset($this->next_match_day[$league_id]) ) {
				$match_day = $this->next_match_day[$league_id];
			} else {
				$sql = "SELECT `match_day`, DATEDIFF(NOW(), `date`) AS datediff FROM {$wpdb->leaguemanager_matches} WHERE `league_id` = '%d' AND `season` = '%s' AND DATEDIFF(NOW(), `date`) < 0 ORDER BY datediff DESC";
				$matches = $wpdb->get_results( $wpdb->prepare($sql, $this->getLeagueID(), $season) );
				if ($matches) $match_day = $matches[0]->match_day;
				else $match_day = -1;
				
				$this->next_match_day[$league_id] = $match_day;
			}
		} elseif ( $select == "current" || $select == "latest") {
			if ( isset($this->current_match_day[$league_id]) ) {
				$match_day = $this->current_match_day[$league_id];
			} else {
				$sql = "SELECT `id`, `match_day`, DATEDIFF(NOW(), `date`) AS datediff FROM {$wpdb->leaguemanager_matches} WHERE `league_id` = '%d' AND `season` = '%s' ORDER BY datediff ASC";
				$matches = $wpdb->get_results( $wpdb->prepare($sql, $this->getLeagueID(), $season) );
				if ($matches) {
					$datediff = array();
					foreach ($matches AS $key => $match) {
						$datediff[$key] = abs($match->datediff);
					}
					asort($datediff);
					$keys = array_keys($datediff);
					$match_day = $matches[$keys[0]]->match_day;
				} else {
					$match_day = -1;
				}
				
				$this->current_match_day[$league_id] = $match_day;
			}
		} elseif ( intval($this->match_day) > 0 ) {
			$match_day = $this->match_day;
		} else {
			$match_day = -1;
		}
		
		$this->setMatchDay($match_day);
		
		return $match_day;
	}
	
	
	/**
	 * get current season
	 *
	 * @param object $league
	 * @param mixed $season
	 * @return array
	 */
	function getSeason( $league, $season = false, $index = false )
	{
		
		if ( isset($_GET['season']) && !empty($_GET['season']) ) {
			$key = htmlspecialchars(strip_tags($_GET['season']));
			if (!isset($league->seasons[$key]))
				return false;
			
			$data = $league->seasons[$key];
		} elseif ( isset($_GET['season_'.$league->id]) ) {
			$key = htmlspecialchars(strip_tags($_GET['season_'.$league->id]));
			if (!isset($league->seasons[$key]))
				return false;
			
			$data = $league->seasons[$key];
		} elseif ( $season ) {
			$data = $league->seasons[$season];
		} elseif ( !empty($league->seasons) ) {
			$data = end($league->seasons);
		} else {
			return false;
		}
		
		if ( $index )
			return $data[$index];
		else
			return $data;
	}


	/**
	 * get competitions from database
	 *
	 * @param int $competition_id (default: false)
	 * @param string $search
	 * @return array
	 */
	function getCompetitions( $offset=0, $limit=99999999 )
	{
		global $wpdb;
		$competitions = $wpdb->get_results($wpdb->prepare( "SELECT `name`, `id`, `num_sets`, `num_rubbers`, `type`, `settings`, `seasons` FROM {$wpdb->leaguemanager_competitions} ORDER BY name ASC LIMIT %d, %d", intval($offset), intval($limit) ));
		$i = 0;
		foreach ( $competitions AS $competition ) {
			$competitions[$i]->name = stripslashes($competition->name);
			$competitions[$i]->num_rubbers = $competition->num_rubbers;
			$competitions[$i]->num_sets = $competition->num_sets;
			$competitions[$i]->type = $competition->type;
			$competitions[$i]->seasons = $competition->seasons = maybe_unserialize($competition->seasons);
			$competition->settings = maybe_unserialize($competition->settings);
			if ( !is_array($competition->settings) ) {
				$competition->settings = array();
			}
			$settings = $this->getDefaultCompetitionSettings( $competition->settings );
			$competitions[$i] = (object)array_merge((array)$competition, $settings);
			$this->competitions[$competition->id] = $competitions[$i];
			$i++;
		}
		return $competitions;
	}

	/**
	 * get competition
	 *
	 * @param mixed $competition_id either ID of competition or title
	 * @return competition object
	 */
	function getCompetition( $competition_id )
	{
		global $wpdb;
		
			// use cached object
		if ( isset($this->competitions[$competition_id]) ) {
			$this->competition = $this->competitions[$competition_id];
			if (isset($this->competition->num_matches_per_page) && $this->competition->num_matches_per_page > 0)
				$this->setNumMatchesPerPage($this->competition->num_matches_per_page);
			
			return $this->competitions[$competition_id];
		}
		
		$competition = $wpdb->get_results($wpdb->prepare("SELECT `name`, `id`, `num_sets`, `num_rubbers`, `type`, `settings`, `seasons` FROM {$wpdb->leaguemanager_competitions} WHERE `id` = '%d' OR `name` = '%s'", intval($competition_id), $competition_id) );
		$competition[] = new stdClass();
		$competition = $competition[0];
		$competition->name = stripslashes($competition->name);
		$competition->num_rubbers = stripslashes($competition->num_rubbers);
		$competition->num_sets = stripslashes($competition->num_sets);
		$competition->type = stripslashes($competition->type);
		$competition->seasons = maybe_unserialize($competition->seasons);
		$competition->settings = (array)maybe_unserialize($competition->settings);
		$competition = (object)array_merge((array)$competition, $competition->settings);
		$this->competition_id = $competition->id;
				
		$this->competitions[$competition_id] = $competition;
		$this->competition = $this->competitions[$competition_id];
		return $competition;
	}
	
	/**
	 * get default competition settings
	 *
	 * @param object $competition
	 * @return object
	 */
	function getDefaultCompetitionSettings( $settings )
	{
		$default = array(
						 "sport" => "tennis",
						 "point_rule" => "tennis",
						 "point_format" => "%d-%d",
						 "point_format2" => "%d-%d",
						 "team_ranking" => "auto",
						 "mode" => "default",
						 "default_match_start_time" => array("hour" => 19, "minutes" => 30),
						 "standings" => array( 'pld' => 1, 'won' => 1, 'tie' => 1, 'lost' => 1 ),
						 "num_ascend" => "",
						 "num_descend" => "",
						 "num_relegation" => "",
						 "num_matches_per_page" => 10,
						 "use_stats" => 1,
						 );
		
		$settings = array_merge($default, $settings);
		
		return $settings;
	}
	
	
	/**
	 * get leagues from database
	 *
	 * @param int $league_id (default: false)
	 * @param string $search
	 * @return array
	 */
	function getLeagues( $args = array() )
	{
		global $wpdb;
		
		$defaults = array( 'offset' => 0, 'limit' => 99999999, 'competition' => false, 'orderby' => array("rank" => "ASC", "id" => "ASC") );
		$args = array_merge($defaults, $args);
		extract($args, EXTR_SKIP);

		$search_terms = array();
		if ( $competition ) {
			$search_terms[] = $wpdb->prepare("`competition_id` = '%d'", intval($competition));
		}
		
		$search = "";
		if (count($search_terms) > 0) {
			$search = " WHERE ";
			$search .= implode(" AND ", $search_terms);
		}
		
		$orderby_string = ""; $i = 0;
		foreach ($orderby AS $order => $direction) {
			if (!in_array($direction, array("DESC", "ASC", "desc", "asc"))) $direction = "ASC";
			if ($this->databaseColumnExists("leagues", $order)) {
				$orderby_string .= "`".$order."` ".$direction;
				if ($i < (count($orderby)-1)) $orderby_string .= ",";
			}
			$i++;
		}
		$orderby = $orderby_string;
		
		$leagues = $wpdb->get_results($wpdb->prepare( "SELECT `title`, `id`, `settings`, `competition_id` FROM {$wpdb->leaguemanager} $search ORDER BY $orderby LIMIT %d, %d", intval($offset), intval($limit) ));
		$i = 0;
		foreach ( $leagues AS $league ) {
			$leagues[$i]->title = stripslashes($league->title);
			$leagues[$i]->competition_id = $league->competition_id;
			$competition = $this->getCompetition($league->competition_id);
			$league->seasons = $competition->seasons;
			$league->num_sets = $competition->num_sets;
			$league->num_rubbers = $competition->num_rubbers;
			$league->type = $competition->type;
			$leagues[$i]->num_sets = $league->num_sets;
			$leagues[$i]->num_rubbers = $league->num_rubbers;
			$leagues[$i]->type = $league->type;
			$leagues[$i]->seasons = $league->seasons ;
			$settings = $competition->settings;
			$leagues[$i] = (object)array_merge((array)$league, $settings);
			unset($leagues[$i]->settings, $league->settings);

			$this->leagues[$league->id] = $leagues[$i];
			$i++;
		}
		return $leagues;
	}
	
	/**
	 * get league
	 *
	 * @param mixed $league_id either ID of League or title
	 * @return league object
	 */
	function getLeague( $league_id )
	{
		global $wpdb;
		// use cached object
		if ( isset($this->leagues[$league_id]) ) {
			$this->league = $this->leagues[$league_id];
			if (isset($this->league->num_matches_per_page) && $this->league->num_matches_per_page > 0)
				$this->setNumMatchesPerPage($this->league->num_matches_per_page);
			return $this->leagues[$league_id];
		}
		$league = $wpdb->get_results($wpdb->prepare("SELECT `title`, `id`, `competition_id` FROM {$wpdb->leaguemanager} WHERE `id` = '%d' OR `title` = '%s'", intval($league_id), $league_id) );
		$league[] = new stdClass();
		$league = $league[0];
		$league->title = stripslashes($league->title);
		$competition = $this->getCompetition($league->competition_id);
		$league->seasons = $competition->seasons;
		$league->num_sets = $competition->num_sets;
		$league->num_rubbers = $competition->num_rubbers;
		$league->type = $competition->type;

		$this->league_id = $league->id;
		$league->hasBridge = $this->hasBridge();
		
		$settings = $competition->settings;
		$league = (object)array_merge((array)$league, $settings);

		if (isset($league->num_matches_per_page) && $league->num_matches_per_page > 0)
			$this->setNumMatchesPerPage($league->num_matches_per_page);
		
        $this->leagues[$league_id] = $league;
		$this->league = $this->leagues[$league_id];
		return $league;
	}
	
	
	/**
	 * get default league settings
	 *
	 * @param object $league
	 * @return object
	 */
	function getDefaultLeagueSettings( $settings )
	{
		$default = array(
			"sport" => "soccer",
			"point_rule" => "three",
			"point_format" => "%d:%d",
			"point_format2" => "%d:%d",
			"team_ranking" => "auto",
			"mode" => "default",
			"default_match_start_time" => array("hour" => 0, "minutes" => 0),
			"standings" => array( 'pld' => 1, 'won' => 1, 'tie' => 1, 'lost' => 1 ),
			"num_ascend" => "",
			"num_descend" => "",
			"num_relegation" => "",
			"num_matches_per_page" => 10,
			"teamprofiles" => array('project_id' => 0, 'cat_id' => 0),
			"use_stats" => 0,
			"slideshow" => array( 'season' => 'latest', 'num_matches' => 0, 'show_logos' => 1 ),
		);
		
		$settings = array_merge($default, $settings);
		
		return $settings;
	}
	
	/**
	 * get teams from database
	 *
	 * @param array $args
	 * @param string $output OBJECT | ARRAY
	 * @return array database results
	 */
	function getTeams( $args = array(), $output = 'OBJECT' )
	{
		global $wpdb;
		$defaults = array( 'competition_id' => false, 'league_id' => false, 'season' => false, 'group' => false, 'rank' => false, 'orderby' => array("rank" => "ASC", "title" => "ASC"), "home" => false, "cache" => true, 'affiliatedclub' => false );
		$args = array_merge($defaults, $args);
		extract($args, EXTR_SKIP);
		
		//$cachekey = md5(implode(array_map(function($entry) { if(is_array($entry)) { return implode($entry); } else { return $entry; } }, $args)) . $output);
				
		$search_terms = array();
		if ( $competition_id ) {
			$search_terms[] = $wpdb->prepare("A.`league_id` IN (SELECT `id` FROM {$wpdb->leaguemanager} WHERE `competition_id` = '%d')", intval($competition_id));
		}
		if ( $league_id ) {
			if ($league_id == "any")
				$search_terms[] = "A.`league_id` != ''";
			else
				$search_terms[] = $wpdb->prepare("A.`league_id` = '%d'", intval($league_id));
		}
		if ( $affiliatedclub ) {
			$search_terms[] = $wpdb->prepare("`affiliatedclub` = '%d'", intval($affiliatedclub));
		}
		if ( $season ) {
			$search_terms[] = $wpdb->prepare("A.`season` = '%s'", htmlspecialchars($season));
		}
		if ($group && $this->groupExists($league_id, htmlspecialchars($group)))
			$search_terms[] = $wpdb->prepare("A.`group` = '%s'", htmlspecialchars($group));
		if ( $rank )
			$search_terms[] = $wpdb->prepare("A.`rank` = '%s'", $rank);
		
		if ( $home )
			$search_terms[] = "B.`home` = 1";
		
		$search = "";
		if (count($search_terms) > 0) {
			$search = " WHERE ";
			$search .= implode(" AND ", $search_terms);
		}
		
		$orderby_string = ""; $i = 0;
		foreach ($orderby AS $order => $direction) {
			if (!in_array($direction, array("DESC", "ASC", "desc", "asc"))) $direction = "ASC";
			if ( ($this->databaseColumnExists("teams", $order)) || ($this->databaseColumnExists("table", $order)) ) {
				$orderby_string .= "`".$order."` ".$direction;
				if ($i < (count($orderby)-1)) $orderby_string .= ",";
			}
			$i++;
		}
		$orderby = $orderby_string;
		
		$sql = "SELECT B.`id` AS `id`, B.`title`, B.`captain`, B.`contactno`, B.`contactemail`, B.`affiliatedclub`, B.`stadium`, B.`logo`, B.`home`, A.`group`, B.`roster`, B.`profile`, A.`points_plus`, A.`points_minus`, A.`points2_plus`, A.`points2_minus`, A.`add_points`, A.`done_matches`, A.`won_matches`, A.`draw_matches`, A.`lost_matches`, A.`diff`, A.`league_id`, A.`id` AS `table_id`, A.`season`, A.`rank`, A.`status`, A.`custom` FROM {$wpdb->leaguemanager_teams} B INNER JOIN {$wpdb->leaguemanager_table} A ON B.id = A.team_id $search ORDER BY $orderby";
		
		$cachekey = md5($sql.$output);
		// use cached object
		if ( isset($this->teams[$cachekey]) && $cache ) {
			return $this->teams[$cachekey];
		}
		$teamlist = $wpdb->get_results( $sql );
		$teams = array(); $i = 0; $class = '';
		$logo_sizes = array( 'tiny', 'thumb', 'large' );
		foreach ( $teamlist AS $team ) {
			$class = ( 'alternate' == $class ) ? '' : 'alternate'; 
			$team->custom = stripslashes_deep(maybe_unserialize($team->custom));
			if ( 'ARRAY' == $output ) {
				$teams[$team->id]['title'] = htmlspecialchars(stripslashes($team->title), ENT_QUOTES);
				$teams[$team->id]['rank'] = $team->rank;
				$teams[$team->id]['status'] = $team->status;
				$teams[$team->id]['season'] = $team->season;
				$teams[$team->id]['captain'] = stripslashes($team->captain);
				$teams[$team->id]['contactno'] = $team->contactno;
                $teams[$team->id]['contactemail'] = $team->contactemail;
                $teams[$team->id]['affiliatedclub'] = $team->affiliatedclub;
                if ( is_plugin_active('wp-clubs/wp-clubs.php') ) {
                    $teams[$team->id]['affiliatedclubname'] = getClubName($team->affiliatedclub);
                } else {
                    $teams[$team->id]['affiliatedclubname'] = '';
                }
				$teams[$team->id]['stadium'] = stripslashes($team->stadium);
				$teams[$team->id]['logo'] = ( !empty($team->logo) ) ? $this->getImageUrl(basename($team->logo)) : false;
				if ( $team->logo ) {
					foreach ( $logo_sizes AS $logo_size ) {
						$teams[$team->id]['logos'][$logo_size] = $this->getImageUrl( basename($team->logo), false, $logo_size );
					}
				}
				$teams[$team->id]['home'] = $team->home;
				$teams[$team->id]['group'] = $team->group;
				$teams[$team->id]['class'] = $class;
				$teams[$team->id]['roster'] = maybe_unserialize($team->roster);
				if ( $this->hasBridge() ) {
					global $lmBridge;
					$teams[$team->id]['teamRoster'] = $lmBridge->getTeamRoster(maybe_unserialize($team->roster));
					$teams[$team->id]['profileData'] = $lmBridge->getTeamProfile($team->profile);
				}
				$teams[$team->id]['points'] = array( 'plus' => $team->points_plus, 'minus' => $team->points_minus );
				$teams[$team->id]['points2'] = array( 'plus' => $team->points2_plus, 'minus' => $team->points2_minus );
				$teams[$team->id]['add_points'] = $team->add_points;
				$teams[$team->id]['diff'] = ( $team->diff > 0 ) ? '+'.intval($team->diff) : $team->diff;
				foreach ( (array)$team->custom AS $key => $value )
					$teams[$team->id][$key] = stripslashes_deep($value);
			} else {
				$teamlist[$i]->logo = ( !empty($team->logo) ) ? $this->getImageUrl(basename($team->logo)) : false;
				if ( $team->logo ) {
					foreach ( $logo_sizes AS $logo_size ) {
						$teamlist[$i]->logos[$logo_size] = $this->getImageUrl( basename($team->logo), false, $logo_size );
					}
				}
				$teamlist[$i]->roster = maybe_unserialize($team->roster);
				$teamlist[$i]->title = htmlspecialchars(stripslashes($team->title), ENT_QUOTES);
				$teamlist[$i]->captain = stripslashes($team->captain);
				$teamlist[$i]->contactno = stripslashes($team->contactno);
                $teamlist[$i]->contactemail = stripslashes($team->contactemail);
                $teamlist[$i]->affiliatedclub = stripslashes($team->affiliatedclub);
                if ( is_plugin_active('wp-clubs/wp-clubs.php') ) {
                    $teamlist[$i]->affiliatedclubname = getClubName($team->affiliatedclub);
                } else {
                    $teamlist[$i]->affiliatedclubname = '';
                }

				$teamlist[$i]->stadium = stripslashes($team->stadium);
				$teamlist[$i]->class = $class;
				$teamlist[$i]->diff = ( $team->diff > 0 ) ? '+'.intval($team->diff) : $team->diff;
				$teamlist[$i] = (object)array_merge((array)$team, (array)$team->custom);
			}

			//unset($teamlist[$i]->custom, $team->custom);
			$i++;
		}

		if ( 'ARRAY' == $output ) {
			$this->teams[$cachekey] = $teams;
		} else {
			$this->teams[$cachekey] = $teamlist;
		}
		
		return $this->teams[$cachekey];
	}
	
	/**
	 * get teams from database
	 *
	 * @param array $args
	 * @param string $output OBJECT | ARRAY
	 * @return array database results
	 */
	function getTeamsInfo( $args = array(), $output = 'OBJECT' )
	{
		global $wpdb;
		$defaults = array( 'competition_id' => false, 'league_id' => false, 'season' => false, 'group' => false, 'rank' => false, 'orderby' => array("rank" => "ASC", "title" => "ASC"), "home" => false, "cache" => true, 'affiliatedclub' => false );
		$args = array_merge($defaults, $args);
		extract($args, EXTR_SKIP);
		
			//$cachekey = md5(implode(array_map(function($entry) { if(is_array($entry)) { return implode($entry); } else { return $entry; } }, $args)) . $output);
		
		$search_terms = array();
		if ( $competition_id ) {
			$search_terms[] = $wpdb->prepare("A.`league_id` IN (SELECT `id` FROM {$wpdb->leaguemanager} WHERE `competition_id` = '%d')", intval($competition_id));
		}
		if ( $league_id ) {
			if ($league_id == "any")
				$search_terms[] = "A.`league_id` != ''";
			else
				$search_terms[] = $wpdb->prepare("A.`league_id` = '%d'", intval($league_id));
		}
		if ( $affiliatedclub ) {
			$search_terms[] = $wpdb->prepare("`affiliatedclub` = '%d'", intval($affiliatedclub));
		}
		if ($group && $this->groupExists($league_id, htmlspecialchars($group)))
			$search_terms[] = $wpdb->prepare("A.`group` = '%s'", htmlspecialchars($group));
		if ( $rank )
			$search_terms[] = $wpdb->prepare("A.`rank` = '%s'", $rank);
		
		if ( $home )
			$search_terms[] = "B.`home` = 1";
		
		$search = "";
		if (count($search_terms) > 0) {
			$search = " WHERE ";
			$search .= implode(" AND ", $search_terms);
		}
		
		$orderby_string = ""; $i = 0;
		foreach ($orderby AS $order => $direction) {
			if (!in_array($direction, array("DESC", "ASC", "desc", "asc"))) $direction = "ASC";
			if ($this->databaseColumnExists("teams", $order)) {
				$orderby_string .= "`".$order."` ".$direction;
				if ($i < (count($orderby)-1)) $orderby_string .= ",";
			}
			$i++;
		}
		$orderby = $orderby_string;
		
		$sql = "SELECT B.`title`, B.`captain`, B.`contactno`, B.`contactemail`, B.`affiliatedclub`, B.`stadium`, B.`logo`, B.`home`, B.`roster`, B.`profile` FROM {$wpdb->leaguemanager_teams} B  $search ORDER BY $orderby";
		
		$cachekey = md5($sql.$output);
			// use cached object
		if ( isset($this->teams[$cachekey]) && $cache ) {
			return $this->teams[$cachekey];
		}
		$teamlist = $wpdb->get_results( $sql );
		$teams = array(); $i = 0; $class = '';
		$logo_sizes = array( 'tiny', 'thumb', 'large' );
		foreach ( $teamlist AS $team ) {
			$class = ( 'alternate' == $class ) ? '' : 'alternate';
			if ( 'ARRAY' == $output ) {
				$teams[$team->id]['title'] = htmlspecialchars(stripslashes($team->title), ENT_QUOTES);
				$teams[$team->id]['season'] = $team->season;
				$teams[$team->id]['captain'] = stripslashes($team->captain);
				$teams[$team->id]['contactno'] = $team->contactno;
				$teams[$team->id]['contactemail'] = $team->contactemail;
				$teams[$team->id]['affiliatedclub'] = $team->affiliatedclub;
				if ( is_plugin_active('wp-clubs/wp-clubs.php') ) {
					$teams[$team->id]['affiliatedclubname'] = getClubName($team->affiliatedclub);
				} else {
					$teams[$team->id]['affiliatedclubname'] = '';
				}
				$teams[$team->id]['stadium'] = stripslashes($team->stadium);
				$teams[$team->id]['logo'] = ( !empty($team->logo) ) ? $this->getImageUrl(basename($team->logo)) : false;
				if ( $team->logo ) {
					foreach ( $logo_sizes AS $logo_size ) {
						$teams[$team->id]['logos'][$logo_size] = $this->getImageUrl( basename($team->logo), false, $logo_size );
					}
				}
				$teams[$team->id]['home'] = $team->home;
				$teams[$team->id]['class'] = $class;
				$teams[$team->id]['roster'] = maybe_unserialize($team->roster);
				foreach ( (array)$team->custom AS $key => $value )
				$teams[$team->id][$key] = stripslashes_deep($value);
			} else {
				$teamlist[$i]->logo = ( !empty($team->logo) ) ? $this->getImageUrl(basename($team->logo)) : false;
				if ( $team->logo ) {
					foreach ( $logo_sizes AS $logo_size ) {
						$teamlist[$i]->logos[$logo_size] = $this->getImageUrl( basename($team->logo), false, $logo_size );
					}
				}
				$teamlist[$i]->roster = maybe_unserialize($team->roster);
				$teamlist[$i]->title = htmlspecialchars(stripslashes($team->title), ENT_QUOTES);
				$teamlist[$i]->captain = stripslashes($team->captain);
				$teamlist[$i]->contactno = stripslashes($team->contactno);
				$teamlist[$i]->contactemail = stripslashes($team->contactemail);
				$teamlist[$i]->affiliatedclub = stripslashes($team->affiliatedclub);
				if ( is_plugin_active('wp-clubs/wp-clubs.php') ) {
					$teamlist[$i]->affiliatedclubname = getClubName($team->affiliatedclub);
				} else {
					$teamlist[$i]->affiliatedclubname = '';
				}
				
				$teamlist[$i]->stadium = stripslashes($team->stadium);
				$teamlist[$i]->class = $class;
			}
			
				//unset($teamlist[$i]->custom, $team->custom);
			$i++;
		}
		
		if ( 'ARRAY' == $output ) {
			$this->teams[$cachekey] = $teams;
		} else {
			$this->teams[$cachekey] = $teamlist;
		}
		
		return $this->teams[$cachekey];
	}
	
	
	/**
	 * get single team
	 *
	 * @param int $team_id
	 * @return object
	 */
	function getTeam( $team_id )
	{
		global $wpdb;

		// use cached object
		if ( isset($this->team[$team_id]) )
			return $this->team[$team_id];
		
		$team = $wpdb->get_results( $wpdb->prepare("SELECT A.`title`, A.`captain`, A.`contactno`, A.`contactemail`, A.`affiliatedclub`, A.`match_day`, A.`match_time`, A.`stadium`, A.`logo`, A.`home`, B.`group`, A.`roster`, A.`profile`, B.`points_plus`, B.`points_minus`, B.`points2_plus`, B.`points2_minus`, B.`add_points`, B.`done_matches`, B.`won_matches`, B.`draw_matches`, B.`lost_matches`, B.`diff`, B.`league_id`, B.`id`, B.`season`, B.`rank`, B.`status`, B.`custom` FROM {$wpdb->leaguemanager_teams} A, {$wpdb->leaguemanager_table} B WHERE A.`id` = '%d' ORDER BY B.`rank` ASC, A.`id` ASC", intval($team_id)) );
		
		if (!isset($team[0])) return false;
		
		$team = $team[0];

		$team->title = htmlspecialchars(stripslashes($team->title), ENT_QUOTES);
		$team->captain = stripslashes($team->captain);
        $team->contactno = stripslashes($team->contactno);
        $team->contactemail = stripslashes($team->contactemail);
        $team->affiliatedclub = stripslashes($team->affiliatedclub);
        if ( is_plugin_active('wp-clubs/wp-clubs.php') ) {
            $team->affiliatedclubname = getClubName($team->affiliatedclub);
        } else {
            $team->affiliatedclubname = '';
        }

		$team->stadium = stripslashes($team->stadium);
		$team->custom = stripslashes_deep(maybe_unserialize($team->custom));
		$team->roster = maybe_unserialize($team->roster);
		$team->logo = ( !empty($team->logo) ) ? $this->getImageUrl(basename($team->logo)) : false;
		if ( $team->logo ) {
			$logo_sizes = array( 'tiny', 'thumb', 'large' );
			foreach ( $logo_sizes AS $logo_size ) {
				$team->logos[$logo_size] = $this->getImageUrl( basename($team->logo), false, $logo_size );
			}
		}
		$team->diff = ( $team->diff > 0 ) ? '+'.$team->diff : $team->diff;
		
		$team = (object)array_merge((array)$team,(array)$team->custom);
		//unset($team->custom);
		
		$this->team[$team_id] = $team;
		return $this->team[$team_id];
	}
	
	/**
	 * get single team
	 *
	 * @param int $team_id
	 * @return object
	 */
	function getTeamDtls( $team_id )
	{
		global $wpdb;
		
			// use cached object
		if ( isset($this->teamDtls[$team_id]) )
			return $this->teamDtls[$team_id];
		
		$team = $wpdb->get_results( $wpdb->prepare("SELECT A.`title`, A.`captain`, A.`contactno`, A.`contactemail`, A.`affiliatedclub`, A.`match_day`, A.`match_time`, A.`stadium`, A.`logo`, A.`home`, A.`roster`, A.`profile`, A.`id` FROM {$wpdb->leaguemanager_teams} A WHERE A.`id` = '%d'", intval($team_id)) );
		
		if (!isset($team[0])) return false;
		
		$team = $team[0];
		
		$team->title = htmlspecialchars(stripslashes($team->title), ENT_QUOTES);
		$team->captain = stripslashes($team->captain);
		$team->contactno = stripslashes($team->contactno);
		$team->contactemail = stripslashes($team->contactemail);
		$team->affiliatedclub = stripslashes($team->affiliatedclub);
		if ( is_plugin_active('wp-clubs/wp-clubs.php') ) {
			$team->affiliatedclubname = getClubName($team->affiliatedclub);
		} else {
			$team->affiliatedclubname = '';
		}
		
		$team->stadium = stripslashes($team->stadium);
		$team->roster = maybe_unserialize($team->roster);
		$team->logo = ( !empty($team->logo) ) ? $this->getImageUrl(basename($team->logo)) : false;
		if ( $team->logo ) {
			$logo_sizes = array( 'tiny', 'thumb', 'large' );
			foreach ( $logo_sizes AS $logo_size ) {
				$team->logos[$logo_size] = $this->getImageUrl( basename($team->logo), false, $logo_size );
			}
		}
		
		$this->teamDtls[$team_id] = $team;
		return $this->teamDtls[$team_id];
	}
	
	/**
	 * get Team ID for given string
	 *
	 * @param string $title
	 * @return int
	 */
	function getTeamID( $title, $league_id = false, $season = false )
	{
		global $wpdb;
		
		$team = $wpdb->get_results( $wpdb->prepare("SELECT `id` FROM {$wpdb->leaguemanager_teams} WHERE `title` = '%s'", $title) );
		return $team[0]->id;
	}
	
	
	/**
	 * get number of seasons
	 *
	 * @param array $seasons
	 * @return int
	 */
	function getNumSeasons( $seasons )
	{
		if (empty($seasons))
			return 0;
		else
			return count($seasons);
	}


	/**
	 * gets number of teams for specific league
	 *
	 * @param int $league_id
	 * @param string $group
	 * @return int
	 */
	function getNumTeams( $league_id, $group = '' )
	{
		global $wpdb;
		
		$key = md5($league_id . $group);
		
		// use cached number
		if ( isset($this->num_teams[$key]) )
			return $this->num_teams[$key];
		
		$league_id = intval($league_id);
		if ($group == ''){
			$this->num_teams[$key] = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(ID) FROM {$wpdb->leaguemanager_table} WHERE `league_id` = '%d'", $league_id) );
		} else {
			$this->num_teams[$key] = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(ID) FROM {$wpdb->leaguemanager_table} WHERE `league_id` = '%d' AND `group` = '%s'", $league_id, $group) );
		}
		return $this->num_teams[$key];
	}
	
	/**
	 * gets number of leagues for specific competition
	 *
	 * @param int $competition_id
	 * @param string $group
	 * @return int
	 */
	function getNumLeagues( $competition_id, $group = '' )
	{
		global $wpdb;
		
		$key = md5($competition_id . $group);
		
			// use cached number
		if ( isset($this->num_leagues[$key]) )
			return $this->num_leagues[$key];
		
		$competition_id = intval($competition_id);
		if ($group == ''){
			$this->num_leagues[$key] = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(ID) FROM {$wpdb->leaguemanager} WHERE `competition_id` = '%d'", $competition_id) );
		} else {
			$this->num_leagues[$key] = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(ID) FROM {$wpdb->leaguemanager} WHERE `competition_id` = '%d' AND `group` = '%s'", $competition_id, $group) );
		}
		return $this->num_leagues[$key];
	}
	
	
	/**
	 * check if any team has a team roster
	 *
	 * @param array $teams
	 * @return boolean
	 */
	function hasTeamRoster($teams)
	{
		foreach ($teams AS $team) {
			if (!empty($team->teamRoster)) return true;
		}
		return false;
	}
	
	
	/**
	 * gets number of matches
	 *
	 * @param string $search
	 * @return int
	 */
	function getNumMatches( $league_id )
	{
		global $wpdb;
	
		$this->num_matches = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(ID) FROM {$wpdb->leaguemanager_matches} WHERE `league_id` = '%d'", $league_id) );
		return $this->num_matches;
	}
	
	
	/**
	 * get number of matches saved in class
	 *
	 * @param none
	 * @return int
	 */
	function getNumMatchesQuery()
	{
		return intval($this->num_matches);
	}
	
	
	/**
	 * set number of matches
	 *
	 * @param int $num_matches
	 */
	function setNumMatches( $num_matches )
	{
		$this->num_matches = intval($num_matches);
	}
	
	
	/**
	 * rank teams
	 *
	 * The Team Ranking can be altered by sport specific rules via the hook <em>rank_teams_`sport_type`</em>
	 * `sport_type` needs to be the key of current sport type. Below is an example how it could be used
	 *
	 * add_filter('rank_teams_soccer', 'soccer_ranking');
	 *
	 * function soccer_ranking( $teams ) {
	 *	// do some stuff
	 *	return $teams
	 * }
	 *
	 *
	 * @param int $league_id
	 * @param mixed $season
	 * @param boolean $update
	 * @return array $teams ordered
	 */
	function rankTeams( $league_id )
	{
		global $wpdb;
		
		if ( isset($league_id) && !$league_id == 0 ) {
			$league = $this->getLeague( $league_id );
			
			if ( !isset($season) )
				$season = $this->getSeason($league);
			
			$season = is_array($season) ? $season['name'] : $season;
			
				// rank Teams in groups
			$groups = !empty($league->groups) ? explode(";", $league->groups) : array( '0' );
			
			foreach ( $groups AS $group ) {
				$team_args = array("league_id" => $league_id, "season" => $season);
				if ( !empty($group) ) $team_args["group"] = $group;
				
				$teams = $teamsTmp = array();
				foreach ( $this->getTeams( $team_args ) AS $team ) {
						//$team->diff = ( $team->diff > 0 ) ? '+'.$team->diff : $team->diff;
					$team->points = array( 'plus' => $team->points_plus, 'minus' => $team->points_minus );
					$team->points2 = array( 'plus' => $team->points2_plus, 'minus' => $team->points2_minus );
					$team->winPercent = ($team->done_matches > 0) ? ($team->won_matches/$team->done_matches) * 100 : 0;
					
					$teams[] = $team;
					$teamsTmp[] = $team;
				}
				
				if ( !empty($teams) && $league->team_ranking == 'auto' ) {
					if ( has_filter( 'rank_teams_'.$league->sport ) ) {
						$teams = apply_filters( 'rank_teams_'.$league->point_rule, $teams );
					} else {
						foreach ( $teams AS $key => $row ) {
							$points[$key] = $row->points['plus'] + $row->add_points;
							$done[$key] = $row->done_matches;
						}
						
						array_multisort($points, SORT_DESC, $done, SORT_ASC, $teams);
					}
				}
				updateRanking( $league_id, $season, $group, $teams, $teamsTmp );
			}
			
			return true;
			
		} else {
			return false;
		}
	}
	

	/**
	 * get standings table
	 *
	 * @param array $teams
	 * @param int $match_day
	 * @param string $mode
	 * @param string $group
	 */
	function getStandings( $teams = false, $match_day = false, $mode = 'all', $group = false )
	{
		global $wpdb;
		
		$league = $this->getCurrentLeague( );
		$season = $this->getSeason($league);
		if ( !$teams ) $teams = $this->getTeams( array("league_id" => $league->id, "season" => $season['name']), 'OBJECT' );
		
		$rule = $this->getPointRule( $league->point_rule );
		extract( (array)$rule );
		
		foreach ( $teams AS $i => $team ) {
			$match_args = array( "league_id" => $league->id, "season" => $season['name'], "final" => "", "standingstable" => true, 'limit' => false, "home_points" => 'not null', "away points" => 'not null' );
			// get matches of specific group
			if ( $group )
				$match_args["group"] = $group;
		
			// get only home matches
			if ( $mode == "home" )
				$match_args['home_team'] = $team->id;
			// get only away matches
			if ( $mode == "away" )
				$match_args['away_team'] = $team->id;
			// get all matches for given team
			if ( $mode == "all" )
				$match_args['team_id'] = $team->id;
			
			// get matches up to given match day
			if ( $match_day )
				$match_args['match_day'] = $match_day;
			
			// initialize team standings data
			$team->done_matches = 0;
			$team->won_matches = 0;
			$team->draw_matches = 0;
			$team->lost_matches = 0;
			$team->points_plus = 0;
			$team->points_minus = 0;
			$team->points2_plus = 0;
			$team->points2_minus = 0;
			
			$points = array( 'plus' => 0, 'minus' => 0 );
			$points2 = array( 'plus' => 0, 'minus' => 0 );
			$team_points = 0;
			
			// get matches
			$matches = $this->getMatches( $match_args );
			foreach ( $matches AS $match ) {
				if ( $match->home_points != "" && $match->away_points != "" )
					$team->done_matches += 1;
				
				if ( $match->winner_id == $team->id )
					$team->won_matches += 1;
				
				if ( $match->loser_id == $team->id )
					$team->lost_matches += 1;
				
				if ( $match->winner_id == -1 && $match->loser_id == -1 )
					$team->draw_matches += 1;
				
				// Home Match
				if ( $match->home_team == $team->id ) {
					if ( 'score' == $rule ) {
						$points['plus'] += $match->home_points;
						$points['minus'] += $match->away_points;
					} else {
						$team_points += $match->home_points;
					}
				}
				
				// Away Match
				if ( $match->away_team == $team->id ) {
					if ( 'score' == $rule ) {
						$points['plus'] += $match->away_points;
						$points['minus'] += $match->home_points;
					} else {
						$team_points += $match->away_points;
					}
				}
			}
			
			if ( $rule != "score" ) {
				$points['plus'] = $team->won_matches * $forwin + $team->draw_matches * $fordraw + $team->lost_matches * $forloss + ($team_points * (isset($forscoring) ? $forscoring : 0));
				$points['minus'] = $team->draw_matches * $fordraw + $team->lost_matches * $forwin + $team->won_matches * $forloss;
			}
			//$points = apply_filters( 'team_points_'.$league->sport, $points, $team->id, $rule );
			$team->points_plus = $points['plus'];
			$team->points_minus = $points['minus'];
			
			//$points2 = apply_filters( 'team_points2_'.$league->sport, $team->id );
			$team->points2_plus = $points2['plus'];
			$team->points2_minus = $points2['minus'];
			
			$team = apply_filters( "leaguemanager_get_standings_".$league->sport, $team, $league->id, $matches, $rule );
			$team->diff = $team->points2_plus - $team->points2_minus;
			$team->points = array( 'plus' => $team->points_plus, 'minus' => $team->points_minus );
			$team->points2 = array( 'plus' => $team->points2_plus, 'minus' => $team->points2_minus );
			
			$teams[$i] = $team;
		}
		
		/*
		 * rank teams
		 */
		if ( has_filter( 'rank_teams_'.$league->sport ) ) {
			$teams = apply_filters( 'rank_teams_'.$league->sport, $teams );
		} else {
			foreach ( $teams AS $key => $row ) {
				$points[$key] = $row->points['plus'] + $row->add_points;
				$done[$key] = $row->done_matches;
			}
			
			array_multisort($points, SORT_DESC, $done, SORT_ASC, $teams);
		}
				
		return $teams;
	}
	
	
	/**
	 * get standings selection
	 *
	 * @param objet $league
	 */
	function getStandingsSelection( $league )
	{
		$selected = isset($_GET['standingstable']) ? htmlspecialchars($_GET['standingstable']) : '';
		
		$season = $this->getSeason( $league );
		$options = array( 'all' => __( 'Current Table', 'leaguemanager' ), 'home' => __( 'Hometable', 'leaguemanager' ), 'away' => __( 'Awaytable', 'leaguemanager' ) );
		$action = is_admin() ? menu_page_url('leaguemanager', 0)."&amp;subpage=show-league&amp;league_id=".$league->id : get_permalink();
		$out = "<select size='1' name='standingstable'>";
		foreach ( $options AS $value => $label ) {
			$out .= "<option value='".$value."'".selected($value, $selected, false).">".$label."</option>";
		}
		for ( $day = 1; $day <= $season['num_match_days']; $day++ ) {
			$out .= "<option value='match_day-".$day."'".selected("match_day-".$day, $selected, false).">".sprintf(__("%d. Match Day", 'leaguemanager'), $day)."</option>";
		}
		$out .= "</select>";
		
		return $out;
	}
	
	
	/**
	 * get point rule depending on selection.
	 * For details on point rules see http://de.wikipedia.org/wiki/Drei-Punkte-Regel (German)
	 *
	 * @param int $rule
	 * @return array of points
	 */
	function getPointRule( $rule )
	{
		$rule = maybe_unserialize($rule);

		// Manual point rule
		if ( is_array($rule) ) {
			return $rule;
		} else {
			$point_rules = array();
			// One point rule
			$point_rules['one'] = array( 'forwin' => 1, 'fordraw' => 0, 'forloss' => 0 );
			// Two point rule
			$point_rules['two'] = array( 'forwin' => 2, 'fordraw' => 1, 'forloss' => 0 );
			// Three-point rule
			$point_rules['three'] = array( 'forwin' => 3, 'fordraw' => 1, 'forloss' => 0 );
			// Score. One point for each scored goal
			$point_rules['score'] = 'score';

			$point_rules = apply_filters( 'leaguemanager_point_rules', $point_rules );

			return $point_rules[$rule];
		}
	}
	
	
	/**
	 * determine if two teams are tied
	 *
	 * @param object $team
	 * @param object $team2
	 * @return boolean
	 */
	function isTie( $team, $team2 )
	{
//    echo "Check for tie here1 <br>";
		if ( $team->points['plus'] == $team2->points['plus'] && $team->diff == $team2->diff && $team->points2['plus'] == $team2->points2['plus'] )
			return true;

		return false;
	}

	
	/**
	 * gets matches from database
	 * 
	 * @param array $args
	 * @param string $output (optional)
	 * @return array
	 */
	function getMatches( $args, $output = 'OBJECT' )
	{
	 	global $wpdb;
		$defaults = array( 'league_id' => false, 'count' => false, 'season' => false, 'group' => false, 'final' => false, 'match_day' => false, 'time' => false, 'home_only' => false, 'winner_id' => false, 'loser_id' => false, 'team_id' => false, 'home_team' => false, 'away_team' => false, 'home_points' => false, 'away_points' => false, 'limit' => true, 'orderby' => array("date" => "ASC", "id" => "ASC"), 'standingstable' => false, 'cache' => true, 'logos' => true, 'competition_id' => false);
		$args = array_merge($defaults, (array)$args);
		extract($args, EXTR_SKIP);
		
		//$cachekey = md5(implode(array_map(function($entry) { if(is_array($entry)) { return implode($entry); } else { return $entry; } }, $args)) . $output);
		
		$league = $this->getCurrentLeague();
		// disable limit for championship mode
		if ( isset($league->mode) && $league->mode == "championship" ) $limit = false;
		
		$search_terms = array();
		if ($league_id) {
			if ($league_id == "any")
				$search_terms[] = "`league_id` != ''";
			else
				$search_terms[] = $wpdb->prepare("`league_id` = '%d'", intval($league_id));
		}
		if ( $competition_id ) {
			$search_terms[] = $wpdb->prepare("`league_id` IN (SELECT `id` FROM {$wpdb->leaguemanager} WHERE `competition_id` = '%d')", intval($competition_id));
		}
		if ($season) {
			if ($season == "any") 
				$search_terms[] = "`season` != ''";
			else
				$search_terms[] = $wpdb->prepare("`season` = '%s'", htmlspecialchars(strip_tags($season)));
		}
		
		if ($final != false && $this->finalExists(htmlspecialchars(strip_tags($final))))
			$search_terms[] = $wpdb->prepare("`final` = '%s'", htmlspecialchars(strip_tags($final)));
		else
			$search_terms[] = "`final` = ''";
		
		if ($group != false && $this->groupExists($league_id, htmlspecialchars(strip_tags($group))))
			$search_terms[] = $wpdb->prepare("`group` = '%s'", htmlspecialchars(strip_tags($group)));
		elseif ( $group === "" )
			$search_terms[] = "`group` = ''";
		
		if ($team_id) {
			$search_terms[] = $wpdb->prepare("(`home_team` = '%d' OR `away_team` = '%d')", $team_id, $team_id);
		} else {
			if ($home_team) $search_terms[] = $wpdb->prepare("`home_team` = '%s'", $home_team);
			if ($away_team) $search_terms[] = $wpdb->prepare("`away_team` = '%s'", $away_team);
		}
		
		if ( $match_day && intval($match_day) > 0 ) {
			if ( $standingstable )
				$search_terms[] = $wpdb->prepare("`match_day` <='%d'", $match_day);
			else
				$search_terms[] = $wpdb->prepare("`match_day` = '%d'", $match_day);
		}
		
		// get only finished matches with score for time 'latest'
		if ( $time == 'latest' ) {
			$home_points = $away_points = false;
			$search_terms[] = "(`home_points` != '' OR `away_points` != '')";
		}
		
		if ($home_points) {
			if ($home_points == "null")
				$search_terms[] = "`home_points` IS NULL";
			elseif ($home_points == "not_null")
				$search_terms[] = "`home_points` IS NOT NULL";
		}
		if ($away_points) {
			if ($away_points == "null")
				$search_terms[] = "`away_points` IS NULL";
			elseif ($away_points == "not_null")
				$search_terms[] = "`away_points` IS NOT NULL";
		}
		if ($winner_id)
			$search_terms[] = $wpdb->prepare("`winner_id` = '%d'", $winner_id);
		if ($loser_id)
			$search_terms[] = $wpdb->prepare("`loser_id` = '%d'", $loser_id);
		
		if ( $time == 'next' )
			$search_terms[] = "TIMESTAMPDIFF(MINUTE, NOW(), `date`) >= 0";
		elseif ( $time == 'prev' || $time == 'latest' )
			$search_terms[] = "TIMESTAMPDIFF(MINUTE, NOW(), `date`) < 0";
		elseif ( $time == 'prev1' )
			$search_terms[] = "TIMESTAMPDIFF(MINUTE, NOW(), `date`) < 0) AND (`winner_id` != 0) ";
		elseif ( $time == 'today' )
			$search_terms[] = "DATEDIFF(NOW(), `date`) = 0";
		elseif ( $time == 'day' )
			$search_terms[] = "DATEDIFF('". htmlspecialchars(strip_tags($match_date))."', `date`) = 0";
	
		$search = "";
		if (count($search_terms) > 0) {
			$search = implode(" AND ", $search_terms);
		}
		
		if ($home_only) {
			$search .= $this->buildHomeOnlyQuery($league_id);
		}
			
		// Force ordering by date ascending if next matches are queried
		if ( $time == 'next' ) {
			$orderby['date'] = 'ASC';
		}
		// Force ordering by date descending if previous/latest matches are queried
		if ( $time == 'prev' || $time == 'latest' ) {
			$orderby['date'] = 'DESC';
		}
		
		$orderby_string = ""; $i = 0;
		foreach ($orderby AS $order => $direction) {
			if (!in_array($direction, array("DESC", "ASC", "desc", "asc"))) $direction = "ASC";
			if ($this->databaseColumnExists("matches", $order)) {
				$orderby_string .= "`".$order."` ".$direction;
				if ($i < (count($orderby)-1)) $orderby_string .= ",";
			}
			$i++;
		}
		$order = $orderby_string;
		
		$num_matches_per_page = ( $limit && is_numeric($limit) ) ? intval($limit) : $this->getNumMatchesPerPage();
		if ( $limit === true ) {
			$offset = ( $this->getCurrentPage() - 1 ) * $this->getNumMatchesPerPage();
		} else {
			$offset = 0;
		}
		if ( $count ) {
			$sql = "SELECT COUNT(ID) FROM {$wpdb->leaguemanager_matches}";
			if ( $search != "") $sql .= " WHERE $search";
			$sql .= " ORDER BY $order";
			//if ( $limit ) $sql .= " LIMIT ".intval($offset).",".intval($limit)."";
			
			$cachekey = md5($sql);
			if ( isset($this->num_matches2[$cachekey]) && $cache && $count )
				return intval($this->num_matches2[$cachekey]);
		
			$this->num_matches2[$cachekey] = $wpdb->get_var($sql);
			return $this->num_matches2[$cachekey];
		}
		$sql = "SELECT `group`, `home_team`, `away_team`, DATE_FORMAT(`date`, '%Y-%m-%d %H:%i') AS date, DATE_FORMAT(`date`, '%e') AS day, DATE_FORMAT(`date`, '%c') AS month, DATE_FORMAT(`date`, '%Y') AS year, DATE_FORMAT(`date`, '%H') AS `hour`, DATE_FORMAT(`date`, '%i') AS `minutes`, `match_day`, `location`, `league_id`, `home_points`, `away_points`, `winner_id`, `loser_id`, `post_id`, `season`, `id`, `custom` FROM {$wpdb->leaguemanager_matches}";
		if ( $search != "") $sql .= " WHERE $search";
		$sql .= " ORDER BY $order";
		if ( $limit && intval($num_matches_per_page) > 0 ) $sql .= " LIMIT ".$offset.",".intval($num_matches_per_page)."";
		$cachekey = md5($sql.$output);
		// use cached objects
		if ( isset($this->matches[$cachekey]) && $cache && !$count ) {
			return $this->matches[$cachekey];
		}

		$matches = $wpdb->get_results( $sql, $output );

		$i = 0;
		$class = '';
		foreach ( $matches AS $match ) {
			$class = ( 'alternate' == $class ) ? '' : 'alternate';
			$matches[$i]->class = $class;
			
			$matches[$i]->match_title = $this->getMatchTitle($match->id, $logos, $match);
			
			$matches[$i]->location = stripslashes($match->location);
			$matches[$i]->custom = $match->custom = stripslashes_deep(maybe_unserialize($match->custom));
			$matches[$i] = (object)array_merge((array)$match, (array)$match->custom);
		//	unset($matches[$i]->custom);
		
			$matches[$i]->hadOvertime = ( isset($match->overtime) && $match->overtime['home'] != '' && $match->overtime['away'] != '' ) ? true : false;
			$matches[$i]->hadPenalty = ( isset($match->penalty) && $match->penalty['home'] != '' && $match->penalty['away'] != '' ) ? true : false;

			$matches[$i]->start_time = ( '00:00' == $match->hour.":".$match->minutes ) ? '' : mysql2date(get_option('time_format'), $match->date);
			$matches[$i]->match_date = ( substr($match->date, 0, 10) == '0000-00-00' ) ? 'N/A' : mysql2date(get_option('date_format'), $match->date);
			if ( $matches[$i]->hadPenalty ) {
				$matches[$i]->homeScore = $match->penalty['home']+$match->overtime['home'];
				$matches[$i]->awayScore = $match->penalty['away']+$match->overtime['away'];
				$matches[$i]->score = sprintf("%s - %s", $matches[$i]->homeScore, $matches[$i]->awayScore)." ".__( '(o.P.)', 'leaguemanager' );
			} elseif ( $matches[$i]->hadOvertime ) {
				$matches[$i]->homeScore = $match->overtime['home'];
				$matches[$i]->awayScore = $match->overtime['away'];
				$matches[$i]->score = sprintf("%s - %s", $matches[$i]->homeScore, $matches[$i]->awayScore)." ".__( '(AET)', 'leaguemanager' );
				//$matches[$i]->score = sprintf("%s - %s", $matches[$i]->home_points, $matches[$i]->away_points);
			} elseif ( $matches[$i]->home_points != NULL && $match->away_points != NULL ) {
				$matches[$i]->homeScore = $match->home_points;
				$matches[$i]->awayScore = $match->away_points;
				$matches[$i]->score = sprintf("%s - %s", $matches[$i]->homeScore, $matches[$i]->awayScore);
			} else {
				$matches[$i]->homeScore = "-";
				$matches[$i]->awayScore = "-";
				$matches[$i]->score = sprintf("%s:%s", $matches[$i]->homeScore, $matches[$i]->awayScore);
			}

			$matches[$i]->homeTeam = $this->getTeam($match->home_team);
			$matches[$i]->awayTeam = $this->getTeam($match->away_team);
			
			$matches[$i]->report = ( $match->post_id != 0 ) ? '(<a href="'.get_permalink($match->post_id).'">'.__('Report', 'leaguemanager').'</a>)' : '';
			
			$i++;
		}
	
		$this->matches[$cachekey] = $matches;
		return $this->matches[$cachekey];
	}
	
	
	/**
	 * get single match
	 *
	 * @param int $match_id
	 * @param boolean $cache
	 * @param boolean $logos
	 * @return object
	 */
	function getMatch( $match_id, $cache = true, $logos = true )
	{
		global $wpdb;

		// use cached object
		if ( isset($this->match[$match_id]) && $cache )
			return $this->match[$match_id];
		
		$match = $wpdb->get_results("SELECT `group`, `home_team`, `away_team`, DATE_FORMAT(`date`, '%Y-%m-%d %H:%i') AS date, DATE_FORMAT(`date`, '%e') AS day, DATE_FORMAT(`date`, '%c') AS month, DATE_FORMAT(`date`, '%Y') AS year, DATE_FORMAT(`date`, '%H') AS `hour`, DATE_FORMAT(`date`, '%i') AS `minutes`, `match_day`, `location`, `league_id`, `home_points`, `away_points`, `winner_id`, `loser_id`, `post_id`, `season`, `id`, `custom` FROM {$wpdb->leaguemanager_matches} WHERE `id` = '".intval($match_id)."'");
		
		if ( !$match ) return false;
		
		$match = $match[0];

		$match->match_title = $this->getMatchTitle($match->id, $logos, $match);
		$match->location = stripslashes($match->location);
		$match->custom = stripslashes_deep(maybe_unserialize($match->custom));
		$match = (object)array_merge((array)$match, (array)$match->custom);
		$match->time = ( '00:00' == $match->hour.":".$match->minutes ) ? '' : mysql2date(get_option('time_format'), $match->date);
		//unset($match->custom);

		$this->match[0] = $match;
		return $this->match[0];
	}
	
	
	/**
	 * get match title
	 *
	 * @param int $match_id
	 * @param boolean show_logo
	 *
	 */
	function getMatchTitle( $match_id, $show_logo = true, $match = false)
	{
		if ( !$match ) $match = $this->getMatch($match_id);
		$league = $this->getLeague($match->league_id);
		$teams = $this->getTeams( array("league_id" => $match->league_id, "season" => $match->season), 'ARRAY');

		if (!isset($teams[$match->home_team]) || !isset($teams[$match->away_team]) || $match->home_team == $match->away_team) {
			if (isset($match->title))
				$title = stripslashes($match->title);
			else
				$title = "";
		} else {
			$home_logo_img = ($teams[$match->home_team]['logo'] != "" && $show_logo) ? "<img class='match-title home-logo logo' src='".$this->getThumbnailUrl($teams[$match->home_team]['logo'])."' alt='' />" : "";
			$away_logo_img = ($teams[$match->away_team]['logo'] != "" && $show_logo) ? "<img class='match-title away-logo logo' src='".$this->getThumbnailUrl($teams[$match->away_team]['logo'])."' alt='' />" : "";
			$home_team_name = ($this->isHomeTeamMatch($match->home_team, $match->away_team, $teams)) ? "<strong>".$teams[$match->home_team]['title']."</strong>" : $teams[$match->home_team]['title']; 
			$away_team_name = ($this->isHomeTeamMatch($match->home_team, $match->away_team, $teams)) ? "<strong>".$teams[$match->away_team]['title']."</strong>" : $teams[$match->away_team]['title']; 
		
			$title = sprintf("%s %s &#8211; %s %s", $home_team_name, $home_logo_img, $away_logo_img, $away_team_name);
			$title = apply_filters( 'leaguemanager_matchtitle_'.$league->sport, $title, $match, $teams );
		}
		
		return $title;
	}
	
	
	/**
	 * test if it's a match of home team
	 *
	 * @param int $home_team
	 * @param int $away_team
	 * @param array $teams
	 * @return boolean
	 */
	function isHomeTeamMatch( $home_team, $away_team, $teams )
	{
		if ( isset($teams[$home_team]) && 1 == $teams[$home_team]['home'] )
			return true;
		elseif ( isset($teams[$away_team]) && 1 == $teams[$away_team]['home'] )
			return true;
		else
			return false;
	}

	/**
	 * gets rubbers from database
	 * 
	 * @param array $args
	 * @param string $output (optional)
	 * @return array
	 */
	function getRubbers( $args, $output = 'OBJECT' )
	{
	 	global $wpdb;
	
		$defaults = array( 'league_id' => false, 'count' => false, 'season' => false, 'group' => false, 'final' => false, 'match_day' => false, 'time' => false, 'home_only' => false, 'winner_id' => false, 'loser_id' => false, 'team_id' => false, 'home_player_1' => false, 'home_player_2' => false, 'away_player_1' => false, 'away_player_2' => false, 'home_points' => false, 'away_points' => false, 'limit' => true, 'orderby' => array("date" => "ASC", "id" => "ASC"), 'standingstable' => false, 'cache' => true, 'logos' => true);
		$args = array_merge($defaults, (array)$args);
		extract($args, EXTR_SKIP);
		
		//$cachekey = md5(implode(array_map(function($entry) { if(is_array($entry)) { return implode($entry); } else { return $entry; } }, $args)) . $output);
		
		$search_terms = array();
		if ($match_id) {
			if ($match_id == "any")
				$search_terms[] = "`match_id` != ''";
			else
				$search_terms[] = $wpdb->prepare("`match_id` = '%d'", intval($match_id));
		}

        if ($league_id) {
            $search_terms[] = $wpdb->prepare("`match_id' in (select `id` FROM {$wpdb->leaguemanager_matches} where `league_id` = '%d')", intval($match_id));
        }
        
        if ($final != false && $this->finalExists(htmlspecialchars(strip_tags($final))))
			$search_terms[] = $wpdb->prepare("`final` = '%s'", htmlspecialchars(strip_tags($final)));
		else
			$search_terms[] = "`final` = ''";
		
		if ($group != false && $this->groupExists($league_id, htmlspecialchars(strip_tags($group))))
			$search_terms[] = $wpdb->prepare("`group` = '%s'", htmlspecialchars(strip_tags($group)));
		elseif ( $group === "" )
			$search_terms[] = "`group` = ''";
		
		// get only finished rubbers with score for time 'latest'
		if ( $time == 'latest' ) {
			$home_points = $away_points = false;
			$search_terms[] = "(`home_points` != '' OR `away_points` != '')";
		}
		
		$search = "";
		if (count($search_terms) > 0) {
			$search = implode(" AND ", $search_terms);
		}
		
		// Force ordering by date ascending if next rubbers are queried
		if ( $time == 'next' ) {
			$orderby['date'] = 'ASC';
		}
		// Force ordering by date descending if previous/latest rubbers are queried
		if ( $time == 'prev' || $time == 'latest' ) {
			$orderby['date'] = 'DESC';
		}
		
		$orderby_string = ""; $i = 0;
		foreach ($orderby AS $order => $direction) {
			if (!in_array($direction, array("DESC", "ASC", "desc", "asc"))) $direction = "ASC";
			if ($this->databaseColumnExists("rubbers", $order)) {
				$orderby_string .= "`".$order."` ".$direction;
				if ($i < (count($orderby)-1)) $orderby_string .= ",";
			}
			$i++;
		}
		$order = $orderby_string;
		
		$offset = 0;
		
		if ( $count ) {
			$sql = "SELECT COUNT(ID) FROM {$wpdb->leaguemanager_rubbers}";
			if ( $search != "") $sql .= " WHERE $search";
			$sql .= " ORDER BY $order";
			//if ( $limit ) $sql .= " LIMIT ".intval($offset).",".intval($limit)."";
			
			$cachekey = md5($sql);
			if ( isset($this->num_rubbers2[$cachekey]) && $cache && $count )
				return intval($this->num_rubbers2[$cachekey]);
		
			$this->num_rubbers2[$cachekey] = $wpdb->get_var($sql);
			return $this->num_rubbers2[$cachekey];
		} else {
			$sql = "SELECT `group`, `home_player_1`, `home_player_2`, `away_player_1`, `away_player_2`, DATE_FORMAT(`date`, '%Y-%m-%d %H:%i') AS date, DATE_FORMAT(`date`, '%e') AS day, DATE_FORMAT(`date`, '%c') AS month, DATE_FORMAT(`date`, '%Y') AS year, DATE_FORMAT(`date`, '%H') AS `hour`, DATE_FORMAT(`date`, '%i') AS `minutes`, `match_id`, `home_points`, `away_points`, `winner_id`, `loser_id`, `post_id`, `id`, `custom`, `rubber_number` FROM {$wpdb->leaguemanager_rubbers}";
			if ( $search != "") $sql .= " WHERE $search";
			$sql .= " ORDER BY $order";
			
			$cachekey = md5($sql.$output);
			// use cached objects
			if ( isset($this->rubbers[$cachekey]) && $cache && !$count )
				return $this->rubbers[$cachekey];
			
			$rubbers = $wpdb->get_results( $sql, $output );
		}

		$i = 0;
		$class = '';
		foreach ( $rubbers AS $rubber ) {
			$class = ( 'alternate' == $class ) ? '' : 'alternate';
			$rubbers[$i]->class = $class;
						
			$rubbers[$i]->custom = $rubber->custom = stripslashes_deep(maybe_unserialize($rubber->custom));
			$rubbers[$i] = (object)array_merge((array)$rubber, (array)$rubber->custom);
		
			$rubbers[$i]->start_time = ( '00:00' == $rubber->hour.":".$rubber->minutes ) ? '' : mysql2date(get_option('time_format'), $rubber->date);
			$rubbers[$i]->rubber_date = ( substr($rubber->date, 0, 10) == '0000-00-00' ) ? 'N/A' : mysql2date(get_option('date_format'), $rubber->date);
			
            if ( $rubbers[$i]->home_points != NULL && $rubber->away_points != NULL ) {
				$rubbers[$i]->homeScore = $rubber->home_points;
				$rubbers[$i]->awayScore = $rubber->away_points;
				$rubbers[$i]->score = sprintf("%s - %s", $rubbers[$i]->homeScore, $rubbers[$i]->awayScore);
			} else {
				$rubbers[$i]->homeScore = "-";
				$rubbers[$i]->awayScore = "-";
				$rubbers[$i]->score = sprintf("%s:%s", $rubbers[$i]->homeScore, $rubbers[$i]->awayScore);
			}

			$rubbers[$i]->homePlayer1 = $rubber->home_player_1;
            $rubbers[$i]->homePlayer2 = $rubber->home_player_2;
			$rubbers[$i]->awayPlayer1 = $rubber->away_player_1;
			$rubbers[$i]->awayPlayer2 = $rubber->away_player_2;
			$rubbers[$i]->rubber_number = $rubber->rubber_number;
			
			$rubbers[$i]->report = ( $rubber->post_id != 0 ) ? '(<a href="'.get_permalink($rubber->post_id).'">'.__('Report', 'leaguemanager').'</a>)' : '';
			
			$i++;
		}
		
		$this->rubbers[$cachekey] = $rubbers;
		return $this->rubbers[$cachekey];
	}
	
	
	/**
	 * get single rubber
	 *
	 * @param int $rubber_id
	 * @param boolean $cache
	 * @param boolean $logos
	 * @return object
	 */
	function getRubber( $rubber_id, $cache = true )
	{
		global $wpdb;

		// use cached object
		if ( isset($this->rubber[$rubber_id]) && $cache )
			return $this->rubber[$rubber_id];
		
		$rubber = $wpdb->get_results("SELECT `group`, `home_player_1`, `home_player_2`, `away_player_1`, `away_player_2`, DATE_FORMAT(`date`, '%Y-%m-%d %H:%i') AS date, DATE_FORMAT(`date`, '%e') AS day, DATE_FORMAT(`date`, '%c') AS month, DATE_FORMAT(`date`, '%Y') AS year, DATE_FORMAT(`date`, '%H') AS `hour`, DATE_FORMAT(`date`, '%i') AS `minutes`, `match_id`, `home_points`, `away_points`, `winner_id`, `loser_id`, `post_id`, `id`, `custom`, `rubber_number` FROM {$wpdb->leaguemanager_rubbers} WHERE `id` = '".intval($rubber_id)."'");
		
		if ( !$rubber ) return false;
		
		$rubber = $rubber[0];

		$rubber->custom = stripslashes_deep(maybe_unserialize($rubber->custom));
		$rubber = (object)array_merge((array)$rubber, (array)$rubber->custom);
		$rubber->time = ( '00:00' == $rubber->hour.":".$rubber->minutes ) ? '' : mysql2date(get_option('time_format'), $rubber->date);

		$this->rubber[$rubber_id] = $rubber;
		return $this->rubber[$rubber_id];
	}

	/**
	 * gets roster from database
	 * 
	 * @param array $args
	 * @param string $output (optional)
	 * @return array
	 */
	function getRoster( $args, $output = 'OBJECT' )
	{
	 	global $wpdb;
	
		$defaults = array( 'league_id' => false, 'count' => false, 'season' => false, 'team' => false, 'club' => false, 'player' => false, 'gender' => false, 'cache' => true, 'orderby' => array("firstname" => "ASC", "surname" => "ASC" ));
		$args = array_merge($defaults, (array)$args);
		extract($args, EXTR_SKIP);
		
		//$cachekey = md5(implode(array_map(function($entry) { if(is_array($entry)) { return implode($entry); } else { return $entry; } }, $args)) . $output);
		
		$search_terms = array();
		if ($team) {
			$search_terms[] = $wpdb->prepare("`affiliatedclub` in (select `affiliatedclub` from {$wpdb->leaguemanager_teams} where `id` = '%d')", intval($team));
		}

		if ($club) {
			$search_terms[] = $wpdb->prepare("`affiliatedclub` = '%ds'", intval($club));
		}
		
		if ($player) {
			$search_terms[] = $wpdb->prepare("`player_id` = '%ds'", intval($player));
		}
		
        if ($gender) {
            $search_terms[] = $wpdb->prepare("`gender` = '%s'", htmlspecialchars(strip_tags($gender)));
        }
        
		$search = "";
		if (count($search_terms) > 0) {
			$search = implode(" AND ", $search_terms);
		}
		
		$orderby_string = ""; $i = 0;
		foreach ($orderby AS $order => $direction) {
			if (!in_array($direction, array("DESC", "ASC", "desc", "asc"))) $direction = "ASC";
			if ($this->databaseColumnExists("player", $order)) {
				$orderby_string .= "`".$order."` ".$direction;
				if ($i < (count($orderby)-1)) $orderby_string .= ",";
			}
			$i++;
		}
		$order = $orderby_string;
		
		$offset = 0;
		
		if ( $count ) {
			$sql = "SELECT COUNT(ID) FROM {$wpdb->leaguemanager_roster}";
			if ( $search != "") $sql .= " WHERE $search";
			$cachekey = md5($sql);
			if ( isset($this->num_players[$cachekey]) && $cache && $count )
				return intval($this->num_players[$cachekey]);
			
			$this->num_players[$cachekey] = $wpdb->get_var($sql);
			return $this->num_players[$cachekey];
		} else {
			$sql = "SELECT A.`id` as `roster_id`, B.`id` as `player_id`, `firstname`, `surname`, `gender`, `btm`, `affiliatedclub`, A.`removed_date` FROM {$wpdb->leaguemanager_roster} A INNER JOIN {$wpdb->leaguemanager_players} B ON A.`player_id` = B.`id`" ;
			if ( $search != "") $sql .= " WHERE $search";
			if ( $order != "") $sql .= " ORDER BY $order";
			
			$cachekey = md5($sql.$output);
			// use cached objects
			if ( isset($this->roster[$cachekey]) && $cache && !$count )
				return $this->roster[$cachekey];
			
			$rosters = $wpdb->get_results( $sql, $output );
		}

		$i = 0;
		$class = '';
		foreach ( $rosters AS $roster ) {
			$class = ( 'alternate' == $class ) ? '' : 'alternate';
			$rosters[$i]->class = $class;
						
			$rosters[$i] = (object)(array)$roster;
		
			$rosters[$i]->roster_id = $roster->roster_id;
			$rosters[$i]->player_id = $roster->player_id;
			$rosters[$i]->firstname = $roster->firstname;
			$rosters[$i]->surname = $roster->surname;
			$rosters[$i]->gender = $roster->gender;
			$rosters[$i]->removed_date = $roster->removed_date;
			$rosters[$i]->btm = $roster->btm;
			
			$i++;
		}
		
		$this->rosters[$cachekey] = $rosters;
		return $this->rosters[$cachekey];
	}
	
	/**
	 * gets single roster entry from database
	 *
	 * @param int $roster_id
	 * @param string $output (optional)
	 * @return array
	 */
	function getRosterEntry( $roster_id, $cache = true )
	{
		global $wpdb;
		
			// use cached object
		if ( isset($this->roster[$roster_id]) && $cache ) {
			return $this->roster[$roster_id];
		}
		
		$roster = $wpdb->get_results( "SELECT B.`id` as `player_id`, `firstname`, `surname`, `gender`, `btm`, `affiliatedclub`, A.`removed_date` FROM {$wpdb->leaguemanager_roster} A INNER JOIN {$wpdb->leaguemanager_players} B ON A.`player_id` = B.`id` WHERE A.`id`= '".intval($roster_id)."'") ;
		
		$roster[] = new stdClass();
		$roster = $roster[0];
		$this->roster[$roster_id] = $roster;
		return $this->roster[$roster_id];
	}
	
	/**
	 * get list of  player
	 *
	 * @param array $args
	 * @param int $player_id
	 * @param boolean $cache
	 * @param boolean $logos
	 * @return object
	 */
	function getPlayers( $args )
	{
		global $wpdb;
		
		$defaults = array( 'player_id' => false, 'btm' => false, 'firstname' => false, 'surname' => false, 'cache' => true, 'orderby' => array("surname" => "ASC", "firstname" => "ASC") );
		$args = array_merge($defaults, (array)$args);
		extract($args, EXTR_SKIP);
		
			//		$cachekey = md5(implode(array_map(function($entry) { if(is_array($entry)) { return implode($entry); } else { return $entry; } }, $args)) . $output);
		
		$search_terms = array();
		if ($player_id) {
			$search_terms[] = $wpdb->prepare("`player_id` = '%d'", intval($player_id));
		}
		
		if ($btm) {
			$search_terms[] = $wpdb->prepare("`btm` = '%d'", intval($btm));
		}
		
		if ($firstname) {
			$search_terms[] = $wpdb->prepare("`firstname` = '%s'", htmlspecialchars(strip_tags($firstname)));
		}
		
		if ($surname) {
			$search_terms[] = $wpdb->prepare("`surname` = '%s'", htmlspecialchars(strip_tags($surname)));
		}
		
		$search = "";
		if (count($search_terms) > 0) {
			$search = implode(" AND ", $search_terms);
		}
		
		$orderby_string = ""; $i = 0;
		foreach ($orderby AS $order => $direction) {
			if (!in_array($direction, array("DESC", "ASC", "desc", "asc"))) $direction = "ASC";
			if ($this->databaseColumnExists("player", $order)) {
				$orderby_string .= "`".$order."` ".$direction;
				if ($i < (count($orderby)-1)) $orderby_string .= ",";
			}
			$i++;
		}
		$order = $orderby_string;

			// use cached object
			//		if ( isset($this->player[$player_id]) && $cache )
			//			return $this->player[$player_id];
		
		$sql = "SELECT `firstname`, `surname`, `gender`, `id`, `btm`, `removed_date` FROM {$wpdb->leaguemanager_players}";
		if ( $search != "") $sql .= " WHERE $search";
		if ( $order != "") $sql .= " ORDER BY $order";
		
		$cachekey = md5($sql);

		$players = $wpdb->get_results( $sql );
		if ( !$players ) return false;
		
		$i = 0;
		foreach ( $players AS $player ) {
			
			$players[$i] = (object)(array)$player;
			
			$i++;
		}
		
		$this->players[$cachekey] = $players;
		return $this->players[$cachekey];
	}
	
	/**
	 * get single player
	 *
	 * @param array $args
	 * @param int $player_id
	 * @param boolean $cache
	 * @param boolean $logos
	 * @return object
	 */
	function getPlayer( $args )
	{
		global $wpdb;

		$defaults = array( 'player_id' => false, 'btm' => false, 'firstname' => false, 'surname' => false, 'cache' => true );
		$args = array_merge($defaults, (array)$args);
		extract($args, EXTR_SKIP);
		
			//		$cachekey = md5(implode(array_map(function($entry) { if(is_array($entry)) { return implode($entry); } else { return $entry; } }, $args)) . $output);
		
		$search_terms = array();
		if ($player_id) {
			$search_terms[] = $wpdb->prepare("`player_id` = '%d'", intval($player_id));
		}
		
		if ($btm) {
			$search_terms[] = $wpdb->prepare("`btm` = '%d'", intval($btm));
		}
		
		if ($firstname) {
			$search_terms[] = $wpdb->prepare("`firstname` = '%s'", htmlspecialchars(strip_tags($firstname)));
		}
		
		if ($surname) {
			$search_terms[] = $wpdb->prepare("`surname` = '%s'", htmlspecialchars(strip_tags($surname)));
		}
		
		$search = "";
		if (count($search_terms) > 0) {
			$search = implode(" AND ", $search_terms);
		}
		
		// use cached object
			//		if ( isset($this->player[$player_id]) && $cache )
			//			return $this->player[$player_id];

		$sql = "SELECT `firstname`, `surname`, `gender`, `id` FROM {$wpdb->leaguemanager_players}";
		if ( $search != "") $sql .= " WHERE $search";
		$player = $wpdb->get_results( $sql );
		if ( !$player ) return false;
		
		$player = $player[0];

		$player = (object)(array)$player;

		$this->player[$player_id] = $player;
		return $this->player[$player_id];
	}

	/**
	 * get player stats
	 *
	 * @param array $args
	 * @param int $player_id
	 * @param boolean $cache
	 * @param boolean $logos
	 * @return object
	 */
	function getPlayerStats( $args, $output = 'OBJECT' )
	{
		global $wpdb;
		
		$defaults = array( 'player_id' => false, 'competition' => false, 'season' => false, 'cache' => true, 'club' => false, 'league_id' => false );
		$args = array_merge($defaults, (array)$args);
		extract($args, EXTR_SKIP);
		
			//		$cachekey = md5(implode(array_map(function($entry) { if(is_array($entry)) { return implode($entry); } else { return $entry; } }, $args)) . $output);
		
		$search_terms1 = $search_terms2 = array();
		if ($player_id) {
			$search_terms1[] = $wpdb->prepare("p.`id` = '%d'", intval($player_id));
		}
		if ($season) {
			$search_terms2[] = $wpdb->prepare("m.`season` = '%s'", htmlspecialchars(strip_tags($season)));
		}
		if ($competition) {
			$search_terms2[] = $wpdb->prepare("m.`league_id` IN (SELECT `id` FROM {$wpdb->leaguemanager} WHERE `competition_id` = '%d')", intval($competition));
		}
		if ($league_id) {
			$search_terms2[] = $wpdb->prepare("m.`league_id` = '%d'", intval($league_id));
		}
		
		if ($club) {
			$search_terms1[] = $wpdb->prepare("ro.`affiliatedclub` = '%d'", intval($club));
		}
		
		$search1 = "";
		if (count($search_terms1) > 0) {
			$search1 = implode(" AND ", $search_terms1);
		}
		$search2 = "";
		if (count($search_terms2) > 0) {
			$search2 = implode(" AND ", $search_terms2);
		}
		
		$order = "`affiliatedclub`, `firstname`, `surname` ";
		$order3 = "m.`season`, m.`match_day` ";
		
		$sql1 = "SELECT p.id AS `player_id`, p.firstname, p.surname, p.gender, p.btm, ro.`id` AS `roster_id`,  ro.`affiliatedclub` FROM {$wpdb->leaguemanager_roster} AS ro, {$wpdb->leaguemanager_players} AS p WHERE ro.`player_id` = p.`id`";
		$sql2 = "FROM {$wpdb->leaguemanager_teams} AS t, {$wpdb->leaguemanager_rubbers} AS r, {$wpdb->leaguemanager_matches} AS m, {$wpdb->leaguemanager_roster} as ro WHERE r.`winner_id` != 0 AND (r.`home_player_1` = ro.`id` OR r.`home_player_2` = ro.`id` OR r.`away_player_1` = ro.`id` OR r.`away_player_2` = ro.`id`) AND( m.`home_team` = t.`id` OR m.`away_team` = t.`id`) AND ro.`affiliatedclub` = t.`affiliatedclub` AND r.`match_id` = m.`id` ";

		if ( $search1 != "") $sql1 .= " AND $search1";
		if ( $search2 != "") $sql2 .= " AND $search2";
		
		$sql = $sql1."	AND ro.`id` in (SELECT ro.id ".$sql2.")";
		
		if ( $order != "") $sql .= " ORDER BY $order";
		$cachekey = md5($sql.$output);

			//use cached object
		if ( isset($this->playerstats[$cachekey]) && $cache )
			return $this->playerstats[$cachekey];

		$playerstats = $wpdb->get_results( $sql );

		$i = 0;
		$class = '';
		foreach ( $playerstats AS $playerstat ) {
			
			$playerstats[$i] = (object)(array)$playerstat;
			
			$sql3 = "SELECT t.`id` AS team_id,  t.`title` AS team_title, m.`season`, m.`match_day`, m.`home_team`, m.`away_team`, m.`winner_id` AS match_winner, m.`home_points`, m.`away_points`, m.`loser_id` AS match_loser, r.`rubber_number`, r.`home_player_1`, r.`home_player_2`, r.`away_player_1`, r.`away_player_2`, r.`winner_id` AS rubber_winner, r.`loser_id` AS rubber_loser, r.`custom`";
			$sql3 .= $sql2." AND ro.`ID` = ".$playerstat->roster_id;
			$sql3 .= " ORDER BY $order3";

			$stats = $wpdb->get_results( $sql3 );
			
			$s = 0;
			foreach ( $stats AS $stat ) {
				
				$stats[$s] = (array)$stat;
				$stats[$s] = $stat;
				$stats[$s]->custom = $stat->custom = stripslashes_deep(maybe_unserialize($stat->custom));
				$s++;
				
			}
			$playerstats[$i]->matchdays = $stats;
			$i++;
		}

		$this->playerstats[$cachekey] = $playerstats;
		return $this->playerstats[$cachekey];
	}
	
	/**
	 * display pagination
	 *
	 * @param int $current_page
	 * @param string $base
	 * @return string
	 */
	function getPageLinks($current_page = false, $base = 'match_paged')
	{
		if (!$current_page) $current_page = $this->getCurrentPage();
		
		if (is_admin()) $query_args = array('league_id' => $this->getLeagueID());
		else $query_args = (isset($this->query_args)) ? $this->query_args : array();
		
		if (isset($_POST['match_day']) && is_string($_POST['match_day'])) {
			$query_args['match_day'] = htmlspecialchars(strip_tags($_POST['match_day']));
		}

		$page_links = paginate_links( array(
			'base' => add_query_arg( $base, '%#%' ),
			'format' => '',
			'prev_text' => '&#9668;',
			'next_text' => '&#9658;',
			'total' => $this->getNumPages(),
			'current' => $current_page,
			'add_args' => $query_args
		));
			
		return $page_links;
	}
	
	
	/**
	 * retrieve current page
	 *
	 * @param none
	 * @return int
	 */
	function getCurrentPage($league_id = false)
	{
		global $wp;
		
		if (!$league_id) $league_id = $this->getLeagueID();
		
		$key = "match_paged_".$league_id;
		if (isset($_GET['match_paged']))
			$this->current_page = intval($_GET['match_paged']);
		elseif (isset($_POST['match_paged']))
			$this->current_page = intval($_POST['match_paged']);
		elseif (isset($wp->query_vars['match_paged']))
			$this->current_page = max(1, intval($wp->query_vars['match_paged']));
		elseif (isset($_GET[$key]))
			$this->current_page = intval($_GET[$key]);
		elseif (isset($_POST[$key]))
			$this->current_page = intval($_POST[$key]);
		elseif (isset($wp->query_vars[$key]))
			$this->current_page = max(1, intval($wp->query_vars[$key]));
		else
			$this->current_page = 1;
		if ( $this->current_page > $this->getNumPages() )
			$this->current_page = $this->getNumPages();	
		
		// Prevent negative offsets
		if ( $this->current_page == 0 )
			$this->current_page = 1;
		
		return intval($this->current_page);
	}
	
	
	/**
	 * get number of matches per page
	 *
	 * @param none
	 * @return int
	 */
	function getNumMatchesPerPage()
	{
		//return intval($this->league->num_matches_per_page);
		return $this->num_matches_per_page;
	}
	
	
	/**
	 * set number of matches per page
	 *
	 * @param int $num
	 * @return void
	 */
	function setNumMatchesPerPage( $num )
	{
		$this->num_matches_per_page = $num;
	}
	
	
	/**
	 * set number of pages
	 *
	 * @param int $num_max_pages
	 * @return none
	 */
	function getNumPages()
	{
		$this->num_max_pages = ( 0 == $this->getNumMatchesPerPage() ) ? 1 : ceil( $this->getNumMatchesQuery()/$this->getNumMatchesPerPage() );
		if ( $this->num_max_pages == 0 ) $this->num_max_pages = 1;
		
		return $this->num_max_pages;
	}
	
	
		/**
	 * get specific field for crosstable
	 *
	 * @param int $curr_team_id
	 * @param int $opponent_id
	 * @param int $home
	 * @return string
	 */
	function getCrosstableField($curr_team_id, $opponent_id, $home, $league_id)
	{
		$match = $this->getMatches( array("home_team" => $curr_team_id, "away_team" => $opponent_id, "league_id" => $league_id) );
		if ($match) $match = $match[0];

 		if ( $match ) {
			$score = $this->getScore($curr_team_id, $opponent_id, $match, $home);
		} else {
			$match = $this->getMatches( array("home_team" => $opponent_id, "away_team" => $curr_team_id, "league_id" => $league_id) );
			if ($match) $match = $match[0];
			$score = $this->getScore($curr_team_id, $opponent_id, $match, $home);
		}
		
		return $score;
	}


	/**
	 * get score for specific field of crosstable
	 *
	 * @param int $curr_team_id
	 * @param int $opponent_id
	 * @param int $home
	 * @return string
	 */
	function getScore($curr_team_id, $opponent_id, $match, $home = 0)
	{
		if ($match) {
			if ( !empty($match->penalty['home']) && !empty($match->penalty['away']) ) {
				$match->overtime = maybe_unserialize($match->overtime);
				$match->penalty = maybe_unserialize($match->penalty);
				$points = array( 'home' => $match->overtime['home']+$match->penalty['home'], 'away' => $match->overtime['away']+$match->penalty['away']);
			} elseif ( !empty($match->overtime['home']) && !empty($match->overtime['away']) ) {
				$match->overtime = maybe_unserialize($match->overtime);
				$points = array( 'home' => $match->overtime['home'], 'away' => $match->overtime['away']);
			} else {
				$points = array( 'home' => $match->home_points, 'away' => $match->away_points );
			}
		}
		
		// unplayed match
		if ( !$match || (NULL == $match->home_points && NULL == $match->away_points) )
			$out = "-:-";
		// match at home
		elseif ( $curr_team_id == $match->home_team )
			$out = sprintf("%s:%s", $points['home'], $points['away']);
		// match away
		elseif ( $opponent_id == $match->home_team )
			$out = sprintf("%s:%s", $points['away'], $points['home']);
		
		if ( $home == 1 ) $out = "<strong>".$out."</strong>";
		
		$out = "<td class='num'>".$out."</td>";
		return $out;
	}
	
	
	/**
	 * get card name
	 *
	 * @param string $type
	 * @return nice card name
	 */
	function getCards( $type = false )
	{
		$cards = array( 'red' => __( 'Red', 'leaguemanager' ), 'yellow' => __( 'Yellow', 'leaguemanager' ), 'yellow-red' => __( 'Yellow/Red', 'leaguemanager' ) );
		$cards = apply_filters( 'leaguemanager_cards', $cards );

		if ( $type )
			return $cards[$type];
		else
			return $cards;
	}
	
	function lm_pagination($paged, $pages = '', $range = 4) {
		$showitems = ($range * 2)+1;
	
		if(empty($paged)) $paged = 1;
		if($pages == '') {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if(!$pages) {
				$pages = 1;
			}
		}

		$div_output = '';
		if (1 != $pages) {
			$div_output .= "<ul class='pagination'>";
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) $div_output .= "<li><a href='".get_pagenum_link(1)."' class='first_page'>&laquo; First</a></li>";
			if($paged > 1) $div_output .= "<li><a href='".get_pagenum_link($paged - 1)."' class='prev_page'>&lsaquo; Previous</a></li>";
				
			for ($i=1; $i <= $pages; $i++) {
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
					$div_output .= ($paged == $i)? "<li class='active'><a href=''>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive'>".$i."</a></li>";
				}
			}
	
			if ($paged < $pages) $div_output .= "<li><a href='".get_pagenum_link($paged + 1)."' class='next_page'>Next &rsaquo;</a></li>";
			if ($paged < $pages-1 && $paged+$range-1 < $pages && $showitems < $pages) $div_output .= "<li><a href='".get_pagenum_link($pages)."' class='last_page'>Last &raquo;</a></li>";
			$div_output .= "</ul>\n";
		}
		return $div_output;
	}
	
	
	/**
	 * some security checks to prevent SQL injections
	 *
	 */
	function seasonExists($league_id, $season)
	{
		$league = $this->getLeague($league_id);
		if (is_array($league->seasons) && in_array($season, array_keys($league->seasons)))
			return true;
		else
			return false;
	}
	
	function groupExists($league_id, $group)
	{
		$league = $this->getLeague($league_id);
		if (isset($league->groups)) {
			$groups = explode(";", $league->groups);
			if (in_array($group, $groups))
				return true;
		}
		return false;
	}
	
	function finalExists($final)
	{
		global $championship;
		$finals = $championship->getFinals();
		if (in_array($final, array_keys($finals)))
			return true;
		else
			return false;
	}
	
	function databaseColumnExists($table, $column)
	{
		global $wpdb;
		
		if ($table == "teams")
			$table = $wpdb->leaguemanager_teams;
		elseif ($table == "table")
		$table = $wpdb->leaguemanager_table;
		elseif ($table == "matches")
			$table = $wpdb->leaguemanager_matches;
        elseif ($table == "rubbers")
            $table = $wpdb->leaguemanager_rubbers;
		elseif ($table == "players")
			$table = $wpdb->leaguemanager_players;
		elseif ($table == "roster")
			$table = $wpdb->leaguemanager_roster;
		elseif ($table == "player")
			$table = $wpdb->leaguemanager_players;
		elseif ($table == "leagues")
			$table = $wpdb->leaguemanager;
		else
			return false;
		
		if ( isset($this->database_column_exists[$table][$column]) )
			return $this->database_column_exists[$table][$column];
		
		$num = $wpdb->query( $wpdb->prepare("SHOW COLUMNS FROM {$table} LIKE %s", $column) );
		if ($num == 1)
			$this->database_column_exists[$table][$column] = true;
		else
			$this->database_column_exists[$table][$column] = false;
		
		return $this->database_column_exists[$table][$column];
	}
	
	
	/**
	 * small helper function to correct 0:0 in overtime fields to empty values
	 */
	function cleanOvertime()
	{
		global $wpdb;
		
		// get all matches from database
		$matches = $wpdb->get_results( "SELECT * FROM {$wpdb->leaguemanager_matches} ORDER By id ASC" );
		foreach ( $matches AS $match ) {
			$match->custom = stripslashes_deep(maybe_unserialize($match->custom));
			
			if ( count($match->custom) && $match->custom['overtime']['home'] == "0" && $match->custom['overtime']['away'] == "0" ) {
				$match->custom['overtime'] = array('home' => '', 'away' => '');
			}
			$wpdb->query($wpdb->prepare( "UPDATE {$wpdb->leaguemanager_matches} SET `custom` = '%s' WHERE `id` = '%d'", maybe_serialize($match->custom), $match->id ));
		}
	}
}
?>
