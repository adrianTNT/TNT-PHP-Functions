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

Make an array of values and post them to given URL, get reply like with <code>get_url();</code>

<code>tnt_post_url('script.php', $post_data_array);</code>

Add a variable to an URL (that might already contain this or other variables)
<code>add_var_to_url($variable_name,$variable_value,$url_string);</code>

Print a time like "10 seconds ago", "3 days ago", "2 years ago" out of a timestamp like 1490279856
<code>timestamp_to_ago(1490279856);</code>

Time how long different operations took on your website, get a timer for each, output is an array of all timers you used:
<code>
timer("loading users from database"); // starts timer
// put some code here that loads users from database
timer("loading users from database"); // stops timer
print_r($timer); 
// or 
echo $timer["loading users from database"];
// prints total seconds: "0.123"
</code>



