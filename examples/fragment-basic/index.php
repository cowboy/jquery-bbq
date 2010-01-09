<?PHP

include "../index.php";

$shell['title3'] = "hashchange » Basic";

$shell['h2'] = 'Cached AJAX + fragment + history + bookmarking = Tasty!';

// ========================================================================== //
// SCRIPT
// ========================================================================== //

ob_start();
?>
$(function(){
  
  // Keep a mapping of url-to-container for caching purposes.
  var cache = {
    // If url is '' (no fragment), display this div's content.
    '': $('.bbq-default')
  };
  
  // Bind an event to window.onhashchange that, when the history state changes,
  // gets the url from the hash and displays either our cached content or fetches
  // new content to be displayed.
  $(window).bind( 'hashchange', function(e) {
    
    // Get the hash (fragment) as a string, with any leading # removed. Note that
    // in jQuery 1.4, you should use e.fragment instead of $.param.fragment().
    var url = $.param.fragment();
    
    // Remove .bbq-current class from any previously "current" link(s).
    $( 'a.bbq-current' ).removeClass( 'bbq-current' );
    
    // Hide any visible ajax content.
    $( '.bbq-content' ).children( ':visible' ).hide();
    
    // Add .bbq-current class to "current" nav link(s), only if url isn't empty.
    url && $( 'a[href="#' + url + '"]' ).addClass( 'bbq-current' );
    
    if ( cache[ url ] ) {
      // Since the element is already in the cache, it doesn't need to be
      // created, so instead of creating it again, let's just show it!
      cache[ url ].show();
      
    } else {
      // Show "loading" content while AJAX content loads.
      $( '.bbq-loading' ).show();
      
      // Create container for this url's content and store a reference to it in
      // the cache.
      cache[ url ] = $( '<div class="bbq-item"/>' )
        
        // Append the content container to the parent container.
        .appendTo( '.bbq-content' )
        
        // Load external content via AJAX. Note that in order to keep this
        // example streamlined, only the content in .infobox is shown. You'll
        // want to change this based on your needs.
        .load( url, function(){
          // Content loaded, hide "loading" content.
          $( '.bbq-loading' ).hide();
        });
    }
  })
  
  // Since the event is only triggered when the hash changes, we need to trigger
  // the event now, to handle the hash the page may have loaded with.
  $(window).trigger( 'hashchange' );
  
});
<?
$shell['script'] = ob_get_contents();
ob_end_clean();

// ========================================================================== //
// HTML HEAD ADDITIONAL
// ========================================================================== //

ob_start();
?>
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

.bbq {
  margin-bottom: 1em;
}

.bbq-content {
  border-left: 1px solid #913D00;
  border-right: 1px solid #913D00;
  padding: 8px;
  margin: 0;
  float: left;
  width: 682px;
  height: 302px;
}

.bbq-item h1 {
  margin: 0;
  font-size: 180%;
}

.bbq-item p {
  font-size: 150%;
  margin: 5px 0 0;
}

.bbq-item img {
  border: 1px solid #913D00;
  float: right;
  margin-left: 10px;
}

a.bbq-current {
  font-weight: 700;
  text-decoration: none;
}

.bbq-nav {
  padding: 0.3em;
  color: #C4884F;
  border: 1px solid #C4884F;
  background: #FFD6AF;
  clear: both;
  text-align: center;
}

.bbq-nav-top {
  margin-bottom: 0;
  -moz-border-radius-topleft: 10px;
  -moz-border-radius-topright: 10px;
  -webkit-border-top-left-radius: 10px;
  -webkit-border-top-right-radius: 10px;
}

.bbq-nav-bottom {
  margin-top: 0;
  -moz-border-radius-bottomleft: 10px;
  -moz-border-radius-bottomright: 10px;
  -webkit-border-bottom-left-radius: 10px;
  -webkit-border-bottom-right-radius: 10px;
}

#page {
  width: 700px;
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
  With <a href="http://benalman.com/projects/jquery-bbq-plugin/">jQuery BBQ</a> you can keep track of state, history and allow bookmarking while dynamically modifying the page via AJAX and/or DHTML.. just click the links, use your browser's back and next buttons, reload the page.. and when you're done playing, check out the code!
</p>

<p>
  In this basic example, window.location.hash is used to store a simple string value of the file to be loaded via AJAX, so that not only a history entry is added, but also so that the page, in its current state, can be bookmarked. Because the hash contains only a single filename, this example doesn't support multiple content boxes, each with their own state, on the same page, but that's definitely still possible! Just check out the <a href="../fragment-advanced/">advanced window.onhashchange</a> example.
</p>

<h3>Navigation</h3>

<div class="bbq">
  <div class="bbq-nav bbq-nav-top">
    <a href="#burger.html">Burgers</a> |
    <a href="#chicken.html">Chicken</a> |
    <a href="#kebabs.html">Kebabs</a> |
    <a href="#kielbasa.html">Kielbasa</a> |
    <a href="#ribs.html">Ribs</a> |
    <a href="#steak.html">Steak</a>
  </div>
  
  <div class="bbq-content">
    
    <!-- This will be shown while loading AJAX content. You'll want to get an image that suits your design at http://ajaxload.info/ -->
    <div class="bbq-loading" style="display:none;">
      <img src="/shell/images/ajaxload-15-white.gif" alt="Loading"/> Loading content...
    </div>
    
    <!-- This content will be shown if no path is specified in the URL fragment. -->
    <div class="bbq-default bbq-item">
      <img src="bbq.jpg" width="400" height="300">
      <h1>jQuery BBQ</h1>
      <p>Click a nav item above or below to load some delicious AJAX content! Also,
        once the content loads, feel free to further explore our savory delights by
        clicking any inline links you might see.</p>
    </div>
    
  </div>
  
  <div class="bbq-nav bbq-nav-bottom">
    <a href="#burger.html">Burgers</a> |
    <a href="#chicken.html">Chicken</a> |
    <a href="#kebabs.html">Kebabs</a> |
    <a href="#kielbasa.html">Kielbasa</a> |
    <a href="#ribs.html">Ribs</a> |
    <a href="#steak.html">Steak</a>
  </div>
</div>

<h3>The code</h3>

<p>Note that a lot of the following code is very similar to the <a href="../fragment-advanced/">advanced window.onhashchange</a> example. That's intentional! They're functionally very similar, but while this version is far less robust, it is much more simple. Look at both to see which meets your needs, and don't be afraid to adapt. Also, if you want to see a robust AND simple implementation, be sure to check out the <a href="../fragment-jquery-ui-tabs/">jQuery UI Tabs</a> example.</p>

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