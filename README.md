# TNT-PHP-Functions

While working on different personal websites I noticed that I always need same kind of PHP functions (truncate text, resize images, etc) so I decided to keep a centralised location for them.

Many of them might need work, so feel free to improve/comment/etc.

Thanks. 

<h2>Usage</h2>

Download tnt_php_functions.php and include it in your php file with:

<code>include("tnt_php_functions.php");</code>

Then inside your call any of the included functions:

<code>remove_var_from_url("a", "script.php?a=1&b=2");</code>

(will return `script.php?b=2`).

<h2>Functions list</h2>

<h3>Make a post request</h3>
Make an array of values and post them to given URL, get reply like with <code>get_url();</code>

<code>tnt_post_url('script.php', $post_data_array);</code>

<h3>Add variable to url</h3>
Add a variable to an URL (that might already contain this or other variables)
<code>add_var_to_url($variable_name,$variable_value,$url_string);</code>

<h3>Print time difference like "2 days ago"</h3>
Print a time like "10 seconds ago", "3 days ago", "2 years ago" out of a timestamp like 1490279856
<code>timestamp_to_ago(1490279856);</code>

<h3>See how long it took to execute any part of your code</h3>
Time how long different operations took on your website, get a timer for each, output is an array of all timers you used. 
Note that you can define more than one timer in your code, how long it takes to load users from db, how long it takes to then loop over an array of users, etc.

```
// start a timer
timer("my db query");

// put some code here that loads users from database

// call again to stop a timer
timer("my db query"); 

// prints total seconds: 0.123
echo $timer["my db query"];
```



