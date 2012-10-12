<?php

$username = "TheDevin";
$json = file_get_contents("http://www.reddit.com/user/$username/about.json");
$obj = json_decode($json, true);

//echo "<pre>";
//print_r($obj);
//echo "</pre>";

$link_karma = $obj['data']['link_karma'];
$comment_karma = $obj['data']['comment_karma'];
$has_mail = ($obj['data']['has_mail']) ? true : false;

$cake_day = $obj['data']['created_utc'];
$next_cake_day = strtotime("+1 year", $cake_day);
$days_left = floor(($next_cake_day - time()) /60/60/24);

echo "Link Karma: " . $link_karma . "<br>";
echo "Comment Karma: " . $comment_karma . "<br>";
echo "Has Mail: ";
echo ($has_mail == true) ? "true" : "false";
echo "<br>";
echo "Days until next cake day: " . $days_left;

?>