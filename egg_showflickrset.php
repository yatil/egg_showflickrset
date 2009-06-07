<?php
// <?php This helps to show syntax highlighting in Textmate when c&p code from the plugin composer

function egg_set($atts)
{

	global $isFeed; // Set $isFeed to 1 to alter the feed display

	if (is_array($atts)) extract($atts);

	$set = isset($set) ? $set : '72157618717025654';
	$max = isset($max) ? $max : '20'; 
	$align = isset($align) ? ' '.$align : ' full';
	$desc = isset($desc) ? $desc : 'yes';

	/* Get Infos */

	$params = array(
		'api_key'	=> '44fb49dfa041df6dac938a2c25166288',
		'method'	=> 'flickr.photosets.getInfo',
		'photoset_id'	=> $set,
		'format'	=> 'php_serial'
	);

	$encoded_params = array();

	foreach ($params as $k => $v) {
		$encoded_params[] = urlencode($k).'='.urlencode($v);
	}


	$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);

    	$ch = curl_init($url);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	
		$output = curl_exec($ch);       
    	
		curl_close($ch);

		$return = unserialize($output);

		$title = $return['photoset']['title']['_content'];
		$description = $return['photoset']['description']['_content'];
		$rest = $return['photoset']['photos']-20;

		$owner = $return['photoset']['owner'];

/* Get Infos END */

/* Get Photos */

	$params = array(
		'api_key'	=> '44fb49dfa041df6dac938a2c25166288',
		'method'	=> 'flickr.photosets.getPhotos',
		'photoset_id'	=> $set,
		'per_page' => $max,
		'format'	=> 'php_serial'
	);

	$encoded_params = array();

	foreach ($params as $k => $v){
		$encoded_params[] = urlencode($k).'='.urlencode($v);
	}


	$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
	$output = curl_exec($ch);       

    curl_close($ch);

	$photos = unserialize($output);

	$photos = $photos['photoset']['photo'];
	$i = 0;

	$o = '<h3 class="photoset">Fotosammlung: »<a href="http://www.flickr.com/photos/'.$owner.'/sets/'.$set.'">'.$title.'</a>«</h3>';
	
	if ($desc=="yes") {
		$o .= ($description==="") ? "" : '<p class="photoset">'.$description."</p>";
	}
	
	$o .= '<ul class="photoset'.$align.'">';
	
	foreach ($photos as $photo) {
		$i++;
		$o .= "<li>";
		$o .= '<a href="http://www.flickr.com/photos/'.$owner.'/'.$photo['id'].'/in/set-'.$set.'/"><img src="http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_s.jpg" alt="'.$photo['title'].'">';
		$o .= ($isFeed) ? "" : '<img src="http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_m.jpg" alt="" class="large">';
		$o .= '</a>';
		$o .= "</li>";
	}

	if ($i==$max) {
		$o .='<li class="lastone"><a href="http://www.flickr.com/photos/'.$owner.'/sets/'.$set.'">'.$rest.' weitere Fotos anzeigen&hellip;</a></li>';
	}

	$o .="</ul>";

return $o;

}
/*
--- PLUGIN METADATA ---
Name: egg_showflickrset
Version: 0.21
Type: 0
Description: Displays a flickr set
Author: Eric Eggert
Link: http://yatil.de/
--- BEGIN PLUGIN HELP ---
<h1>egg_flickrset</h1>

<p>Takes the following parameters:

<dl><dt><code>set</code></dt><dd>ID of the set to display, you can extract that easily from the URL: <code>http://www.flickr.com/photos/yatil/sets/<strong> 72157618717025654 </strong>/</code></dd><dt><code>max</code></dt><dd>Shows x photos. If there are more photos in a set, a link to the set is included.</dd><dt><code>align</code></dt><dd>Adds an additional class to the ul, to use with CSS.</dd><dt><code>desc</code></dt><dd>If description should be included the value is <code>yes</code> (default value), else <code>no</code>.</dd></dl>

<p>Some things are still hardcoded, like german text strings or the double photo for CSS tricks. This will be changed at some  time in the future.
--- END PLUGIN HELP & METADATA ---
*/
?>