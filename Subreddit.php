<?php

/**
* Subreddit
*
* Gets Information about a particular subreddit.
*
* @author    Devin Clark <dclarkdesign@gmail.com>
* @license   TBD
* @link      TBD
*/
class Subreddit extends Reddit {
	private $subreddit;
	private $obj;
	private $url;
	private $subscribers;
	private $accounts_active;
	private $description;
	private $public_description;
	private $is_over_18;

	function __construct($subreddit) {
		$this->subreddit = $subreddit;
		$json = parent::cacheFeedData($this->subreddit . "-about.json", "http://www.reddit.com/r/$this->subreddit/about.json", 3600*24);
		$this->obj = json_decode( $json, true );

		$this->url = "http://www.reddit.com" . $this->obj['data']['url'];
		$this->subscribers = $this->obj['data']['subscribers'];
		$this->accounts_active = $this->obj['data']['accounts_active'];
		$this->description = $this->obj['data']['description'];
		$this->public_description = $this->obj['data']['public_description'];
		$this->has_mod_mail = ( $this->obj['data']['over18'] ) ? true : false;
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
	 * Sets the URL of the subreddit if a value is passed, returns it if no value is passed.
	 *
	 * @param string    $val Sets the URL of the subreddit.
	 * @access public
	 * @return string    The URL of the subreddit.
	 */
	public function url( $val = null ) {
		if ( $val !== null )
			$this->url = $val;
		else
			return $this->url;
	}


}

