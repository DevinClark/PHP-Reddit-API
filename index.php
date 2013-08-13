<html>
<head></head>
<body>
<?php
include_once("Reddit.php");
include_once("Subreddit.php");
$reddit = new Reddit("reddit");

//echo $reddit->getRawJSON();
echo "Username: " . $reddit->username() . "<br />";
echo $reddit->linkKarma() . ":" . $reddit->commentKarma() . "<br />";
echo $reddit->getDaysUntilCakeDay() . " Days" . "<br />";

$sub = new Subreddit("funny");
echo $sub->url();
$sub->getRawJSON();
?>
</body>
</html>