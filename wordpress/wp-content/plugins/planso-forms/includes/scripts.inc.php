<?php
/*
WP-Style
F j, Y g:i a - November 6, 2010 12:50 am
F j, Y - November 6, 2010
F, Y - November, 2010
g:i a - 12:50 am
g:i:s a - 12:50:48 am
l, F jS, Y - Saturday, November 6th, 2010
M j, Y @ G:i - Nov 6, 2010 @ 0:50
Y/m/d \a\t g:i A - 2010/11/06 at 12:50 AM
Y/m/d \a\t g:ia - 2010/11/06 at 12:50am
Y/m/d g:i:s A - 2010/11/06 12:50:48 AM
Y/m/d - 2010/11/06
*/

if(isset($j->date_format) && !empty($j->date_format)){
	$moment_date_format = $j->date_format;
	$moment_date_format = str_replace('D','aaa',$moment_date_format);
	$moment_date_format = str_replace('d','DD',$moment_date_format);
	$moment_date_format = str_replace('j','D',$moment_date_format);
	$moment_date_format = str_replace('M','MMM',$moment_date_format);
	$moment_date_format = str_replace('m','MM',$moment_date_format);
	$moment_date_format = str_replace('n','M',$moment_date_format);
	$moment_date_format = str_replace('F','MMMM',$moment_date_format);
	$moment_date_format = str_replace('y','YY',$moment_date_format);
	$moment_date_format = str_replace('Y','YYYY',$moment_date_format);
	$moment_date_format = str_replace('l','dddd',$moment_date_format);
	$moment_date_format = str_replace('aaa','ddd',$moment_date_format);
} else {
	$moment_date_format = 'l';
}
/*
d, dd: Numeric date, no leading zero and leading zero, respectively. Eg, 5, 05.
D, DD: Abbreviated and full weekday names, respectively. Eg, Mon, Monday.
m, mm: Numeric month, no leading zero and leading zero, respectively. Eg, 7, 07.
M, MM: Abbreviated and full month names, respectively. Eg, Jan, January
yy, yyyy: 2- and 4-digit years, respectively. Eg, 12, 2012.
*/
if(isset($j->date_format) && !empty($j->date_format)){
	$eternicode_date_format = $j->date_format;
	$eternicode_date_format = str_replace('D','aaa',$eternicode_date_format);
	$eternicode_date_format = str_replace('d','dd',$eternicode_date_format);
	$eternicode_date_format = str_replace('j','d',$eternicode_date_format);
	$eternicode_date_format = str_replace('M','MM',$eternicode_date_format);
	$eternicode_date_format = str_replace('m','mm',$eternicode_date_format);
	$eternicode_date_format = str_replace('n','m',$eternicode_date_format);
	$eternicode_date_format = str_replace('F','MMMM',$eternicode_date_format);
	$eternicode_date_format = str_replace('y','yy',$eternicode_date_format);
	$eternicode_date_format = str_replace('Y','yyyy',$eternicode_date_format);
	$eternicode_date_format = str_replace('l','yyyy',$eternicode_date_format);
	$eternicode_date_format = str_replace('aaa','DD',$eternicode_date_format);
} else {
	$eternicode_date_format = 'yyyy-mm-dd';
}

echo '<script type="text/javascript">';
echo 'var planso_form_builder = {};';
echo 'planso_form_builder.locale="'.substr(get_locale(),0,2).'";';
echo 'planso_form_builder.locale_long="'.get_locale().'";';
if(isset($j->datepicker) && !empty($j->datepicker))echo 'planso_form_builder.datepicker="'.$j->datepicker.'";';
if(isset($j->date_format) && !empty($j->date_format))echo 'planso_form_builder.date_format="'.$j->date_format.'";';
if(isset($moment_date_format) && !empty($moment_date_format))echo 'planso_form_builder.moment_date_format="'.$moment_date_format.'";';
if(isset($eternicode_date_format) && !empty($eternicode_date_format))echo 'planso_form_builder.eternicode_date_format="'.$eternicode_date_format.'";';
//echo 'console.log(planso_form_builder);';
echo '</script>';

?>