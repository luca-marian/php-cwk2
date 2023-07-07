<?php
$headTitle = "Academic View";
$viewHeading = htmlHeading("Academic View - Module Results", 2);
$content = '';
$content .= htmlHeading("Web Programming using PHP", 2);
$content .= calculateStatistics("moduleResults");
