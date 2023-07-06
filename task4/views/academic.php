<?php
$headTitle = "Academic View";
$viewHeading = htmlHeading("Academic View - Module Results",2);
$content = '<table>
				<tr><th>Statistic</th><th>Number</th></tr>
				<tr><td>1st</td><td>3</td></tr>
				<tr><td>2.1</td><td>5</td></tr>
				<tr><td>2.2</td><td>5</td></tr>
				<tr><td>3rd</td><td>3</td></tr>
				<tr><td>Pass</td><td>3</td></tr>
				<tr><td>Fail</td><td>2</td></tr>
				<tr><td>Average Mark</td><td>56</td></tr>
				<tr><td>TOTAL students</td><td>21</td></tr>
			</table>';


#To get full marks for this component you need to generate the above table by reading the 'moduleResults' table
#in your MySQL database.  If you completed Task 3, you should be able to simply re-use the code.
#Remember modular design and use of user defined functions will get you higher marks.
?>