<html>
<head></head>
<body>
<?php
include_once("Reddit.php");
include_once("Subreddit.php");
$reddit = new Reddit("TheDevin");

//echo $reddit->getRawJSON();
echo "Username: " . $reddit->username() . "<br />";
echo "Link Karma: " . $reddit->linkKarma() . "<br />";
echo "Comment Karma: " . $reddit->commentKarma() . "<br />";
echo "Days until Cake Day: " . $reddit->getDaysUntilCakeDay() . "<br />";

$sub = new Subreddit("funny");
echo $sub->url();
$sub->getRawJSON();
?>
</body>
</html>