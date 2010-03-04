<?php
/**
 *
 * The MIT License
 *
 * Copyright (c) 2008-2009 David Evans
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
**/

PHP_SAPI === 'cli' or die('Please run from the command line');

$javascript_files = array
(
	'FancyUpload2'        => array('"Swiff.Uploader"', '"Fx.ProgressBar"'),
	'FancyUpload3.Attach' => array('"Swiff.Uploader"'),
	'Fx.ProgressBar'      => array('<mootools/Fx.Transitions>'),
	'Swiff.Uploader'      => array('<mootools/Swiff>', '<mootools/Class.Extras>'),
	'Uploader'            => array('<mootools/Element.Event>', '<mootools/Class.Extras>'),
);

$remote_url = 'http://github.com/digitarald/digitarald-fancyupload/tree/master/source/';

$output_folder = realpath(dirname(__FILE__)).'/javascript/fancyupload/';

echo "From: $remote_url\n";
echo "Saving in: $output_folder\n";

foreach($javascript_files as $file => $dependencies)
{
	echo "Downloading $file\n";
	
	$script = file_get_contents($remote_url.$file.'.js?raw=true');
	
	// Replace path to SWF file with PHP to generate appropriate path using url::site()
	$script = str_replace("path: 'Swiff.Uploader.swf',", 'path: \'<?php echo url::site("assets/fancyupload/Swiff.Uploader.swf");?>\',', $script);
	
	$header = '';
	foreach($dependencies as $dependency)
	{
		$header .= "//= require $dependency\n"; 
	}
	$script = $header."\n".$script;
	
	file_put_contents($output_folder.$file.'.js', $script);
}


$file = 'Swiff.Uploader.swf';
echo "Downloading $file\n";
file_put_contents(realpath(dirname(__FILE__)).'/swf/'.$file, file_get_contents($remote_url.$file.'?raw=true'));
