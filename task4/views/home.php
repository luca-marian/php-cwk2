<?php
$headTitle = 'PHP Cwk2 Home Page';
$viewHeading = htmlHeading('Home Page View', 2);
$content = htmlParagraph("Test Users :");
$content .= htmlParagraph("username:ubadmin01 password:Aaaa1111# userType:admin");
$content .= htmlParagraph("username:ubacadem01 password:Aaaa1111# userType:academic");
$content .= htmlParagraph("username:fflint01 password:Aaaa1111# userType:student");

$users = getUsers();
foreach ($users as $user) {
    $content .= htmlParagraph("username:{$user['username']} password:{$user['password']} userType:{$user['userType']}");
}

$content .= htmlParagraph("The last view a user was browsing should be saved as a Cookie and re-load on subsequent login for up to one week for that user");
