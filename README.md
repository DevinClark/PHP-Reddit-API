Reddit
======

Gets information about a reddit user.

More Info:

   - **Author:** Devin Clark <dclarkdesign@gmail.com> 
   - **License:** TBD 
   - **Link:** TBD

cacheFeedData($filename, $feed_url, $time=null)
-----------------------------------------------

Takes the contents of a remote JSON file and stores it in a cache file. This file is refreshed every 360 seconds by default and can be changed with the `$time` parameter.

More Info:

   - **Param:** string     $filename The name you choose for the cached file. 
   - **Param:** string     $feed_url The remote url of the feed to be cached. 
   - **Param:** int        $time The number of seconds to cache the file for. Default is 360 (6 minutes). 
   - **Access:** public 
   - **Return:** string    The contents of the feed or file.

cakeDay()
---------

Sets the Unix datetime of when the account was created if a value is passed, returns it if no value is passed.

More Info:

   - **Param:** int     $val Sets the time. 
   - **Access:** public 
   - **Return:** int    the Unix datetime in which the account was created.

commentKarma()
--------------

Sets the commentKarma if a value is passed, returns it if no value is passed.

More Info:

   - **Param:** int      $val Sets the comment karma. 
   - **Access:** public 
   - **Return:** int     the user's comment karma.

getDaysUntilCakeDay()
---------------------

Uses the Unix datetime for account creation to determine when the next cake day will be.

More Info:

   - **Access:** public 
   - **Return:** int    Number of days until the user's cake day.

getFeedData($url)
-----------------

Uses cURL to get the contents of the JSON API files.

More Info:

   - **Param:** string     $url The URL of a file. 
   - **Access:** public 
   - **Return:** string    The contents of the file passed in .

getRawJSON()
------------

Outputs the raw JSON data received from the reddit api. This method is mainly used for debugging purposes.

More Info:

   - **Access:** public

hasMail()
---------

Determines whether the user currently has mail.

More Info:

   - **Param:** boolean      $val Sets the value. 
   - **Access:** public 
   - **Return:** boolean     Whether the user has mail.

linkKarma()
-----------

Sets the linkKarma if a value is passed, returns it if no value is passed.

More Info:

   - **Param:** int      $val Sets the link karma. 
   - **Access:** public 
   - **Return:** int     the user's link karma.

username()
----------

Sets the username if a value is passed, returns it if no value is passed.

More Info:

   - **Param:** string     $val Sets the username. 
   - **Access:** public 
   - **Return:** string    the username.

__construct($username)
----------------------

   - **Param:** string     $username The username of the reddit user. 
   - **Access:** public