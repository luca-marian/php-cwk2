<?php
$headTitle = "Admin View";
$viewHeading = htmlHeading("Admin View - show all user browsing history",2);
$content = '';
#retrieve all usernames from usersTable in the database into an array 

#iterate over all usernames retrieved from database and detect if cookies saved for username; add to new array if found

#if > 0 usernames with cookies set content to be username and last page viewed for all usernames
#else set content to No users have any browsing history saved.

?>