<?php

/**
 * Gets information about a reddit user.
 *
 * @author   Devin Clark <dclarkdesign@gmail.com>
 * @license  TBD
 * @link     TBD
 */
class Reddit {
	/** Instance Variables */
	private $obj;
	private $username;
	private $link_karma;
	private $comment_karma;
	private $has_mail;
	private $has_mod_mail;
	private $is_gold;
	private $cake_day;

	/**
	 * @param string     $username The username of the reddit user.
	 * @access public
	 *
	 */
	function __construct( $username ) {
		$this->username = $username;

		$json = $this->cacheFeedData($this->username . "-about.json", "http://www.reddit.com/user/$this->username/about.json");
		$this->obj = json_decode( $json, true );

		$this->link_karma = $this->obj['data']['link_karma'];
		$this->comment_karma = $this->obj['data']['comment_karma'];
		$this->has_mail = ( $this->obj['data']['has_mail'] ) ? true : false;
		$this->has_mod_mail = ( $this->obj['data']['has_mod_mail'] ) ? true : false;
		$this->is_gold = ( $this->obj['data']['is_gold'] ) ? true : false;
		$this->cake_day = $this->obj['data']['created'];
	}

	/**
	 * Outputs the raw JSON data received from the reddit api. This method is mainly used for debugging purposes.
	 *
	 * @access public
	 */
	public function getRawJSON() {
		echo "<pre>";
		print_r( $this->obj );
		echo "</pre>";
	}

	/**
	 * Sets the username if a value is passed, returns it if no value is passed.
	 *
	 * @param string     $val Sets the username.
	 * @access public
	 * @return string    the username.
	 */
	public function username( $val = null ) {
		if ( $val !== null )
			$this->username = $val;
		else
			return $this->username;
	}

	/**
	 * Sets the linkKarma if a value is passed, returns it if no value is passed.
	 *
	 * @param int      $val Sets the link karma.
	 * @access public
	 * @return int     the user's link karma.
	 */
	public function linkKarma( $val = null ) {
		if ( $val !== null )
			$this->link_karma = $val;
		else
			return $this->link_karma;
	}

	/**
	 * Sets the commentKarma if a value is passed, returns it if no value is passed.
	 *
	 * @param int      $val Sets the comment karma.
	 * @access public
	 * @return int     the user's comment karma.
	 */
	public function commentKarma( $val = null ) {
		if ( $val !== null )
			$this->comment_karma = $val;
		else
			return $this->comment_karma;
	}

	/**
	 * Determines whether the user currently has mail.
	 *
	 * @param boolean      $val Sets the value.
	 * @access public
	 * @return boolean     Whether the user has mail.
	 */
	public function hasMail( $val = null ) {
		if ( $val !== null )
			$this->has_mail = $val;
		else
			return $this->has_mail;
	}

	/**
	 * Sets the Unix datetime of when the account was created if a value is passed, returns it if no value is passed.
	 *
	 * @param int     $val Sets the time.
	 * @access public
	 * @return int    the Unix datetime in which the account was created.
	 */
	public function cakeDay( $val = null ) {
		if ( $val !== null )
			$this->cake_day = $val;
		else
			return $this->cake_day;
	}

	/**
	 * Uses the Unix datetime for account creation to determine when the next cake day will be.
	 *
	 * @access public
	 * @return int    Number of days until the user's cake day.
	 */
	public function getDaysUntilCakeDay() {
		$next_cake_day = strtotime( "+1 year", $this->cake_day );
		$days_left = floor( ( $next_cake_day - time() ) /60/60/24 );
		return $days_left;
	}

	/**
	 * Uses cURL to get the contents of the JSON API files.
	 *
	 * @param string     $url The URL of a file.
	 * @access public
	 * @return string    The contents of the file passed in .
	 */
	public function getFeedData($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	/**
	 * Takes the contents of a remote JSON file and stores it in a cache file. This file is refreshed every 360 seconds by default and can be changed with the `$time` parameter.
	 *
	 * @param string     $filename The name you choose for the cached file.
	 * @param string     $feed_url The remote url of the feed to be cached.
	 * @param int        $time The number of seconds to cache the file for. Default is 360 (6 minutes).
	 * @access public
	 * @return string    The contents of the feed or file.
	 */
	public function cacheFeedData($filename, $feed_url, $time = 360) {
		$cache_time = $time;

		$cache_file = "./cache/" . $filename;
		$time_diff = (time() - filemtime($cache_file));

		if (file_exists($cache_file) && $time_diff < $cache_time) {
			$string = file_get_contents($cache_file);
		}
		else {
			$string = $this->getFeedData( $feed_url );
			if ($f = fopen($cache_file, 'w')) {
				fwrite ($f, $string, strlen($string));
				fclose($f);
			}
		}
		return $string;
	}

}

/* Documentation Code */
require_once 'Doc.php';
$doc = new Respect\Doc( 'Reddit' );
$markdown = (string) $doc;
$doc = file_put_contents( 'README.md', $markdown );
