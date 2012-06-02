<?php
// temporary debugging
$shell            = array();
$base             = '../';


// setup from my notes in index.php
$shell['titles']  = array('Ben Alman');
$shell['links']   = array('http://benalman.com/projects/jquery-bbq-plugin/');

//  Predeclare example template vars
for ( $i = 1; $i < 6; $i++ ) {
  $shell['h' . $i]      = '';
  $shell['title' . $i]  = '';
  $shell['link' . $i]   = '';
}


//  previous set up
$shell['title1']  = 'jQuery BBQ';
$shell['link1']   = 'http://benalman.com/projects/jquery-bbq-plugin/';

ob_start();
?>
  <a href="http://benalman.com/projects/jquery-bbq-plugin/">Project Home</a>,
  <a href="http://benalman.com/code/projects/jquery-bbq/docs/">Documentation</a>,
  <a href="http://github.com/cowboy/jquery-bbq/">Source</a>
<?php

$shell['h3'] = ob_get_contents();
ob_end_clean();


$shell['jquery']  = 'jquery-1.4.1.js';
//$shell['jquery'] = 'jquery-1.3.2.js';

$shell['shBrush'] = array( 'JScript' );

