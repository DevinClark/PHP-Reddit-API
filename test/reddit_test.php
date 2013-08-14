<?php
require 'Reddit.php';

class RedditTest extends PHPUnit_Framework_TestCase {
	public function testUsername() {
		$reddit = new Reddit("reddit");
		$this->assertEquals("reddit", $reddit->username());
	}
}