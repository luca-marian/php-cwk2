<?php
#some basic HTML generation functions

function htmlHeading($text, $level) {
	$heading = trim(strtolower($text));
	switch ($level) {
		case 1 :
		case 2 :
			$heading = ucwords($heading);
			break;
		case 3 :
		case 4 :
		case 5 :
		case 6 :
			$heading = ucfirst($heading);
			break;
		default: #traps unknown heading level exception
			$heading = '<FONT COLOR="#ff0000">Unknown heading level:' . $level . '</FONT>';
		}
	return '<h' . $level . '>' . htmlentities($heading) . '</h' . $level .  '>';
}

function htmlParagraph($text) {
	return '<p>' . htmlentities(trim($text)) . '</p>';
}

#ADD YOUR USER DEFINED FUNCTIONS HERE
?>