<?php

$GLOBALS['is_example']  = isset($GLOBALS['is_example']) ?  $GLOBALS['is_example'] : false;

include 'config.php';

// set up as per my suggestion 
array_push($shell['titles'],  'Examples');
array_push($shell['links'],   '../');

// previous set up - deprecate
$shell['title2']  = 'Examples';
$shell['link2']   = '../';


ob_start();
?>
    <div id="donate">
      <p>Your generous donation allows me to continue developing and updating my code!</p>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="hosted_button_id" value="5791421">
      <input class="submit" type="image" src="../donate.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">
      <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
      </form>
      <div class="clear"></div>
    </div>
<?php 
$shell['donate'] = ob_get_contents();
ob_end_clean();

function draw_shell() {
  global $shell, $base;
  
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>
      <?php  
        /* 
          Changed this handling to an array

          ex: 

          // initial assignment/declaration that would be in config.php
          $shell['titles'] = array('jQuery BBQ');

          // then, later in the application as your adding information...
          array_push($shell['titles'], 'a second title string');
          array_push($shell['titles'], 'a third title string');

          // then here, where it's printed, you can do this:

          echo implode(' &raquo; ', $shell['titles']);

        */          

        if ( isset($shell['title']) AND !empty($shell['title']) ) {

          echo $shell['title'];
        } else {
          echo implode(' &raquo; ', $shell['titles']);
        }

      ?>
    </title>
    <script type="text/javascript" src="<?php echo  $base ?>../shared/ba-debug.js"></script>
    <?php 
    if ( isset($shell['jquery']) ) {
      ?>
      <script type="text/javascript" src="<?php echo  $base ?>../shared/<?php echo  $shell['jquery'] ?>"></script>
      <?php 
    }

    ?>
    <script type="text/javascript" src="<?php echo  $base ?>../shared/SyntaxHighlighter/scripts/shCore.js"></script>
    <?php 

    if ( isset($shell['shBrush']) ) {
      foreach ( $shell['shBrush'] as $brush ) {
        ?>
        <script type="text/javascript" src="<?php echo  $base ?>../shared/SyntaxHighlighter/scripts/shBrush<?php echo  $brush ?>.js"></script>
        <?php 
      }
    }
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo  $base ?>../shared/SyntaxHighlighter/styles/shCore.css">
    <link rel="stylesheet" type="text/css" href="<?php echo  $base ?>../shared/SyntaxHighlighter/styles/shThemeDefault.css">
    <link rel="stylesheet" type="text/css" href="<?php echo  $base ?>index.css">

  <?php 

    echo  ( isset($shell['html_head']) ? $shell['html_head'] : '' );

  ?>

  </head>
  <body>

  <div id="page">
    <div id="header">
      <h1>
        <a href="http://benalman.com/" class="title"><b>Ben</b> Alman</a>
        <?php 
        $i = 1;

        while ( isset($shell['title' . $i]) ) {

          if ( !empty($shell['title' . $i]) ) {
            print ' &raquo; ';
            if ( isset($shell['link' . $i]) ) {
              print '<a href="' . $shell['link' . $i] . '">' . $shell['title' . $i] . '</a>';
            } else {
              print $shell['title' . $i];
            }
          }
          $i++;
        }
        ?>
      </h1>
      <?php 
        $i = 2;
        while ( isset($shell['h' . $i]) ) {
          if ( !empty($shell['h' . $i]) ) {
            print "<h$i>" . $shell['h' . $i] . "</h$i>";
          }
          $i++;
        }
        
        if ( isset($shell['html_header']) ) {
          echo  $shell['html_header'];
        }
      ?>
    </div>
    <div id="content">
      <?php 
        if ( isset($shell['html_body']) ) {
          echo  $shell['html_body'];
        }      
      ?>
    </div>
    <div id="footer">
      <p>
        If console output is mentioned, but your browser has no console, this example is using <a href="http://benalman.com/projects/javascript-debug-console-log/">JavaScript Debug</a>. Click this bookmarklet: <a href="javascript:if(!window.firebug){window.firebug=document.createElement(&quot;script&quot;);firebug.setAttribute(&quot;src&quot;,&quot;http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js&quot;);document.body.appendChild(firebug);(function(){if(window.firebug.version){firebug.init()}else{setTimeout(arguments.callee)}})();void (firebug);if(window.debug&&debug.setCallback){(function(){if(window.firebug&&window.firebug.version){debug.setCallback(function(b){var a=Array.prototype.slice.call(arguments,1);firebug.d.console.cmd[b].apply(window,a)},true)}else{setTimeout(arguments.callee,100)}})()}};">Debug + Firebug Lite</a> to add the Firebug lite console to the current page. Syntax highlighting is handled by <a href="http://alexgorbatchev.com/">SyntaxHighlighter</a>.
      </p>
      <p>
        All original code is Copyright &copy; 2010 "Cowboy" Ben Alman and dual licensed under the MIT and GPL licenses. View the <a href="http://benalman.com/about/license/">license page</a> for more details. 
      </p>
    </div>
  </div>

  </body>
  </html>
  <?php 

}


if ( !$GLOBALS['is_example'] ) {

  $shell['link2']     = '';
  
  $shell['h2']        = 'Select an example:';
  $shell['h3']        = '';
  
  $shell['html_body'] = '';
  
  $files = scandir( '.' );
  foreach ( $files as $file ) {
    
    if ( $file != '.' && $file != '..' && file_exists( $file . '/index.php' ) ) {

      $file_contents  = file_get_contents( $file . '/index.php' );
      $title          = preg_replace( 
                          '/^.*\$shell\[\'title3\'\]\s*=\s*"(.*?)";.*$/s', '$1', 
                          $file_contents
                        );
      
      $shell['html_body'] .= '<a href="'.$file.'/">' . 
                             ( ( $title == $file_contents ) ? $file : stripcslashes( $title ) ) . 
                             '</a><br>';
    }
  }
  
  $base = '';
  draw_shell();
}

?>
