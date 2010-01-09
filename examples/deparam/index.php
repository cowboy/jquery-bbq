<?PHP

include "../index.php";

$shell['title3'] = "jQuery.deparam";

$shell['h2'] = 'The opposite of jQuery.param, pretty much.';

array_push( $shell['shBrush'], 'Php' );

// ========================================================================== //
// SCRIPT
// ========================================================================== //

ob_start();
?>
$(function(){
  
  // Values are not coerced.
  var params = $.deparam.querystring();
  
  debug.log( 'not coerced', params );
  $('#deparam_string').text( JSON.stringify( params, null, 2 ) );
  
  // Values are coerced.
  params = $.deparam.querystring( true );
  
  debug.log( 'coerced', params );
  $('#deparam_coerced').text( JSON.stringify( params, null, 2 ) );
  
  // Highlight the current sample query string link
  var qs = $.param.querystring();
  
  $('li a').each(function(){
    if ( $(this).attr( 'href' ) === '?' + qs ) {
      $(this).addClass( 'current' );
    }
  });
  
});
<?
$shell['script'] = ob_get_contents();
ob_end_clean();

// ========================================================================== //
// HTML HEAD ADDITIONAL
// ========================================================================== //

ob_start();
?>
<script type="text/javascript" language="javascript">

// I want to use json2.js because it allows me to format stringified JSON with
// pretty indents, so let's nuke any existing browser-specific JSON parser.
window.JSON = null;

</script>
<script type="text/javascript" src="../../shared/json2.js"></script>
<script type="text/javascript" src="../../jquery.ba-bbq.js"></script>
<script type="text/javascript" language="javascript">

<?= $shell['script']; ?>

$(function(){
  
  // Syntax highlighter.
  SyntaxHighlighter.highlight();
  
});

</script>
<style type="text/css" title="text/css">

/*
bg: #FDEBDC
bg1: #FFD6AF
bg2: #FFAB59
orange: #FF7F00
brown: #913D00
lt. brown: #C4884F
*/

#page {
  width: 700px;
}

#page * {
  word-wrap: break-word;
}

.deparam {
  width: 343px;
}

.deparam-1 {
  float: left;
}

.deparam-2 {
  float: right;
}

.current {
  color: #FF7F00;
  text-decoration: none;
}

li {
  margin-bottom: 0.6em;
}

</style>
<?
$shell['html_head'] = ob_get_contents();
ob_end_clean();

// ========================================================================== //
// HTML BODY
// ========================================================================== //

ob_start();
?>
<?= $shell['donate'] ?>

<p>
  <a href="http://benalman.com/projects/jquery-bbq-plugin/">jQuery BBQ</a> includes a powerful jQuery.deparam method that is capable of fully deserializing not only any params string that jQuery.param can create, but that PHP and Rails (and hopefully everything else) can create. And even though only the query string is being parsed this example, methods for parsing a params string out of the fragment (hash) as well parsing any stand-alone params string are included. jQuery BBQ can also be used to merge params from multiple URLs or objects into a new URL, even within element attributes. See <a href="../../docs/">the documentation</a> for a full list of methods!
</p>

<h3>Sample query strings for you to click:</h3>

<ol>
  <li>Arrays encoded like this won't work as-expected in PHP / Rails, but work in BBQ and many older server-side frameworks (jQuery 1.3.2 or older $.param, jQuery 1.4 with $.param.traditional = true):<br>
    <a href="?a=1&a=2&a=3&b=4&c=true&d=0">?a=1&a=2&a=3&b=4&c=true&d=0</a></li>
  <li>Arrays encoded like this will work as-expected in PHP / Rails / BBQ (jQuery 1.4 or newer $.param):<br>
    <a href="?a[]=1&a[]=2&a[]=3&b=4&c=true&d=0">?a[]=1&a[]=2&a[]=3&b=4&c=true&d=0</a></li>
  <li>jQuery BBQ and PHP can handle non-shallow arrays, but Rails (rack) cannot (jQuery 1.4 or newer $.param):<br>
    <a href="?a[]=0&a[1][]=1&a[1][]=2&a[2][]=3&a[2][1][]=4&a[2][1][]=5&a[2][2][]=6&a[3][b][]=7&a[3][b][1][]=8&a[3][b][1][]=9&a[3][b][2][0][c]=10&a[3][b][2][0][d]=11&a[3][b][3][0][]=12&a[3][b][4][0][0][]=13&a[3][b][5][e][f][g][]=14&a[3][b][5][e][f][g][1][]=15&a[3][b][]=16&a[]=17">?a[]=0&a[1][]=1&a[1][]=2&a[2][]=3&a[2][1][]=4&a[2][1][]=5&a[2][2][]=6&a[3][b][]=7&a[3][b][1][]=8&a[3][b][1][]=9
  &a[3][b][2][0][c]=10&a[3][b][2][0][d]=11&a[3][b][3][0][]=12&a[3][b][4][0][0][]=13&a[3][b][5][e][f][g][]=14
  &a[3][b][5][e][f][g][1][]=15&a[3][b][]=16&a[]=17</a></li>
  <li>Some simple implicitly non-string values that BBQ can optionally coerce:<br>
    <a href="?a=true&b=false&c=undefined&d=&f=1&g=2&h=hello+world">?a=true&b=false&c=undefined&d=&f=1&g=2&h=hello+world</a></li>
  <li>More nested data structures. Since the arrays are shallow, this works in Rails (rack).<br>
    <a href="?a[]=4&a[]=5&a[]=6&b[x][]=7&b[y]=8&b[z][]=9&b[z][]=0&b[z][]=true&b[z][]=false&b[z][]=undefined&b[z][]=&c=1">?a[]=4&a[]=5&a[]=6&b[x][]=7&b[y]=8&b[z][]=9&b[z][]=0&b[z][]=true&b[z][]=false&b[z][]=undefined&b[z][]=&c=1</a></li>
</ol>

<h3>Urldecoded query string</h3>

<?= urldecode( $_SERVER['QUERY_STRING'] ) ?>


<h3>Query string params, as parsed by jQuery BBQ (and converted to JSON)</h3>

<div class="deparam deparam-1">
  <p><code>$.deparam.querystring();</code></p>
  <pre class="brush:js" id="deparam_string"></pre>
</div>

<div class="deparam deparam-2">
  <p><code>$.deparam.querystring( true );</code></p>
  <pre class="brush:js" id="deparam_coerced"></pre>
</div>

<div style="clear:both;"></div>


<h3>GET params, as parsed by PHP</h3>
<p><code>print_r( $_GET );</code></p>

<pre class="brush:php">
<? print_r( $_GET ) ?>
</pre>


<h3>The code</h3>

<pre class="brush:js">
<?= htmlspecialchars( $shell['script'] ); ?>
</pre>

<?
$shell['html_body'] = ob_get_contents();
ob_end_clean();

// ========================================================================== //
// DRAW SHELL
// ========================================================================== //

draw_shell();

?>