<?PHP

$shell = array();
$base = '../';

include 'config.php';

function draw_shell() {
  global $shell, $base;
  
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>Ben Alman &raquo; <?= $shell['title1'] ?><? if ( $shell['title2'] ) { print ' &raquo; ' . $shell['title2']; } ?></title>
  <script type="text/javascript" src="<?= $base ?>../shared/ba-debug.js"></script>
<?
  
  if ( $shell['jquery'] ) {
    ?>  <script type="text/javascript" src="<?= $base ?>../shared/<?= $shell['jquery'] ?>"></script>
<?
  }
  
  ?>  <script type="text/javascript" src="<?= $base ?>../shared/SyntaxHighlighter/scripts/shCore.js"></script>
<?
  
  if ( $shell['shBrush'] ) {
    foreach ( $shell['shBrush'] as $brush ) {
      ?>  <script type="text/javascript" src="<?= $base ?>../shared/SyntaxHighlighter/scripts/shBrush<?= $brush ?>.js"></script>
<?
    }
  }
  
  ?>
  <link rel="stylesheet" type="text/css" href="<?= $base ?>../shared/SyntaxHighlighter/styles/shCore.css">
  <link rel="stylesheet" type="text/css" href="<?= $base ?>../shared/SyntaxHighlighter/styles/shThemeDefault.css">
  <link rel="stylesheet" type="text/css" href="<?= $base ?>index.css">
  
<?= $shell['html_head'] ?>

</head>
<body>

<div id="page">
  <div id="header">
    <h1>
      <a href="http://benalman.com/" class="title"><b>Ben</b> Alman</a>
      <?
      
      if ( $shell['link1'] ) {
        print ' &raquo; <a href="' . $shell['link1'] . '">' . $shell['title1'] . '</a>';
      } else {
        print ' &raquo; ' . $shell['title1'];
      }
      
      if ( $shell['title2'] ) {
        print ' &raquo; ' . $shell['title2'];
      }
      
      ?>
    </h1>
    <?
    if ( $shell['h2'] ) {
      print '<h2>' . $shell['h2'] . '</h2>';
    }
    if ( $shell['h3'] ) {
      print '<h3>' . $shell['h3'] . '</h3>';
    }
    
    print $shell['html_header'];
    ?>
  </div>
  <div id="content">
    <?= $shell['html_body'] ?>
  </div>
  <div id="footer">
    <p>
      If console output is mentioned, but your browser has no console, this example is using <a href="http://benalman.com/projects/javascript-debug-console-log/">JavaScript Debug</a>. Click this bookmarklet: <a href="javascript:if(!window.firebug){window.firebug=document.createElement(&quot;script&quot;);firebug.setAttribute(&quot;src&quot;,&quot;http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js&quot;);document.body.appendChild(firebug);(function(){if(window.firebug.version){firebug.init()}else{setTimeout(arguments.callee)}})();void (firebug);if(window.debug&&debug.setCallback){(function(){if(window.firebug&&window.firebug.version){debug.setCallback(function(b){var a=Array.prototype.slice.call(arguments,1);firebug.d.console.cmd[b].apply(window,a)},true)}else{setTimeout(arguments.callee,100)}})()}};">Debug + Firebug Lite</a> to add the Firebug lite console to the current page. Syntax highlighting is handled by <a href="http://alexgorbatchev.com/">SyntaxHighlighter</a>.
    </p>
    <p>
      All original code is Copyright © 2009 "Cowboy" Ben Alman and licensed under the MIT license. View the <a href="http://benalman.com/about/license/">license page</a> for more details. 
    </p>
    <div class="donate">
      <p>Let me know how much you appreciate my work with a small donation!</p>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="hosted_button_id" value="5791421">
      <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">
      <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
      </form>
    </div>
  </div>
</div>

</body>
</html><?

}

if ( count( get_included_files() ) == 2 ) {
  $shell['title2'] = "Examples";
  
  $shell['h2'] = 'Select an example:';
  $shell['h3'] = '';
  
  $shell['html_body'] = '';
  
  $files = scandir( '.' );
  foreach ( $files as $file ) {
    if ( $file != '.' && $file != '..' && file_exists( "$file/index.php" ) ) {
      $file_contents = file_get_contents( "$file/index.php" );
      $title = preg_replace( '/^.*\$shell\[\'title2\'\]\s*=\s*"(.*)";.*$/s', '$1', $file_contents );
      $title = $title == $file_contents ? $file : stripcslashes( $title );
      $shell['html_body'] .= "<a href=\"$file/\">$title</a><br>";
    }
  }
  
  $base = '';
  draw_shell();
}

?>