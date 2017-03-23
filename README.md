# TNT-PHP-Functions

While working on different personal websites I noticed that I always need same kind of PHP functions (truncate text, resize images, etc) so I decided to keep a centralised location for them.

Many of them might need work, so feel free to improve/comment/etc.

Thanks. 

<h2>Functions list</h2>

Make an array of values and post them to given URL, get reply like with <code>get_url();</code>

<code>tnt_post_url('script.php', $post_data_array);</code>

Add a variable to an URL (that might already contain this or other variables)
<code>add_var_to_url($variable_name,$variable_value,$url_string);</code>

Print a time like "10 seconds ago", "3 days ago", "2 years ago" out of a timestamp like 1490279856
<code>timestamp_to_ago(1490279856);?></code>


