<?PHP

include "../index.php";

// Include the XMLpage class.
require "XMLpage.php";

// Initialize the XMLpage, fetching the appropriate page's data.
$page = new XMLpage(array( 'id' => $_REQUEST['_escaped_fragment_'] ));

// This is a special case for the 404 error page. Only if it is defined in the
// XML and a page isn't found will a 404 header be set.
if ( $page->attr['status'] == '404' ) {
  header( 'HTTP/1.0 404 Not Found' );
}

// Set the title appropriately, based on the XMLpage title attribute.
$shell['title'] = $page->attr['title'];

$shell['h2'] = 'Cached AJAX + fragment + history + bookmarking = Tasty!';

// ========================================================================== //
// SCRIPT
// ========================================================================== //

ob_start();
?>
$(function(){
  
  // Enable "AJAX Crawlable" mode.
  $.param.fragment.ajaxCrawlable( true );
  
  // Keep a mapping of url-to-container for caching purposes.
  var cache = {
    // If url is '' (no fragment), display this div's content.
    '': {
      title: "<?= $page->attr['title'] ?>",
      elem: $('.bbq-item')
    }
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
    url && $( 'a[href="#!' + url + '"]' ).addClass( 'bbq-current' );
    
    if ( cache[ url ] ) {
      // Since the element is already in the cache, it doesn't need to be
      // created, so instead of creating it again, let's just show it!
      cache[ url ].elem.show();
      
      // Update the document title.
      document.title = cache[ url ].title;
      
    } else {
      // Show "loading" content while AJAX content loads.
      $( '.bbq-loading' ).show();
      
      // Load external content (stored in the XML data file) via AJAX. The
      // purpose of page.php is simply to provide the data stored in XML in
      // the more friendly JSON format.
      $.getJSON( 'page.php', { id: url }, function(data){
        
        // Ensure that data was actually returned. You could easily go a step
        // further and check that data.attr.status isn't '404', for example.
        if ( data ) {
          // Update the document title.
          document.title = data.attr.title;
          
          // Update the internal cache with a reference to this element as well
          // as its title.
          cache[ url ] = {
            title: data.attr.title,
            elem: $( '<div class="bbq-item"/>' )
              
              .html( data.content )
              
              // Append the content container to the parent container.
              .appendTo( '.bbq-content' )
          };
        }
        
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
  (EXPLAIN)
</p>

<h3>Navigation</h3>

<div class="bbq-link">
  <div class="bbq-nav bbq-nav-top">
    <a href="#!burger">Burgers</a> |
    <a href="#!chicken">Chicken</a> |
    <a href="#!kebabs">Kebabs</a> |
    <a href="#!kielbasa">Kielbasa</a> |
    <a href="#!ribs">Ribs</a> |
    <a href="#!steak">Steak</a>
  </div>
  
  <div class="bbq-content">
    
    <!-- This will be shown while loading AJAX content. You'll want to get an image that suits your design at http://ajaxload.info/ -->
    <div class="bbq-loading" style="display:none;">
      <img src="/shell/images/ajaxload-15-white.gif" alt="Loading"/> Loading content...
    </div>
    
    <!-- This content will be shown if no path is specified in the URL fragment. -->
    <div class="bbq-item">
      <?= $page->content ?>
    </div>
    
  </div>
  
  <div class="bbq-nav bbq-nav-bottom">
    <a href="#!burger">Burgers</a> |
    <a href="#!chicken">Chicken</a> |
    <a href="#!kebabs">Kebabs</a> |
    <a href="#!kielbasa">Kielbasa</a> |
    <a href="#!ribs">Ribs</a> |
    <a href="#!steak">Steak</a>
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