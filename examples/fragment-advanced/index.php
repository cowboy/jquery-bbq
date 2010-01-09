<?PHP

include "../index.php";

$shell['title3'] = "hashchange » Advanced";

$shell['h2'] = 'Cached AJAX + fragment + history + bookmarking = Tasty!';

// ========================================================================== //
// SCRIPT
// ========================================================================== //

ob_start();
?>
$(function(){
  
  // For each .bbq widget, keep a data object containing a mapping of
  // url-to-container for caching purposes.
  $('.bbq').each(function(){
    $(this).data( 'bbq', {
      cache: {
        // If url is '' (no fragment), display this div's content.
        '': $(this).find('.bbq-default')
      }
    });
  });
  
  // For all links inside a .bbq widget, push the appropriate state onto the
  // history when clicked.
  $('.bbq a[href^=#]').live( 'click', function(e){
    var state = {},
      
      // Get the id of this .bbq widget.
      id = $(this).closest( '.bbq' ).attr( 'id' ),
      
      // Get the url from the link's href attribute, stripping any leading #.
      url = $(this).attr( 'href' ).replace( /^#/, '' );
    
    // Set the state!
    state[ id ] = url;
    $.bbq.pushState( state );
    
    // And finally, prevent the default link click behavior by returning false.
    return false;
  });
  
  // Bind an event to window.onhashchange that, when the history state changes,
  // iterates over all .bbq widgets, getting their appropriate url from the
  // current state. If that .bbq widget's url has changed, display either our
  // cached content or fetch new content to be displayed.
  $(window).bind( 'hashchange', function(e) {
    
    // Iterate over all .bbq widgets.
    $('.bbq').each(function(){
      var that = $(this),
        
        // Get the stored data for this .bbq widget.
        data = that.data( 'bbq' ),
        
        // Get the url for this .bbq widget from the hash, based on the
        // appropriate id property. In jQuery 1.4, you should use e.getState()
        // instead of $.bbq.getState().
        url = $.bbq.getState( that.attr( 'id' ) ) || '';
      
      // If the url hasn't changed, do nothing and skip to the next .bbq widget.
      if ( data.url === url ) { return; }
      
      // Store the url for the next time around.
      data.url = url;
      
      // Remove .bbq-current class from any previously "current" link(s).
      that.find( 'a.bbq-current' ).removeClass( 'bbq-current' );
      
      // Hide any visible ajax content.
      that.find( '.bbq-content' ).children( ':visible' ).hide();
      
      // Add .bbq-current class to "current" nav link(s), only if url isn't empty.
      url && that.find( 'a[href="#' + url + '"]' ).addClass( 'bbq-current' );
      
      if ( data.cache[ url ] ) {
        // Since the widget is already in the cache, it doesn't need to be
        // created, so instead of creating it again, let's just show it!
        data.cache[ url ].show();
        
      } else {
        // Show "loading" content while AJAX content loads.
        that.find( '.bbq-loading' ).show();
        
        // Create container for this url's content and store a reference to it in
        // the cache.
        data.cache[ url ] = $( '<div class="bbq-item"/>' )
          
          // Append the content container to the parent container.
          .appendTo( that.find( '.bbq-content' ) )
          
          // Load external content via AJAX. Note that in order to keep this
          // example streamlined, only the content in .infobox is shown. You'll
          // want to change this based on your needs.
          .load( url, function(){
            // Content loaded, hide "loading" content.
            that.find( '.bbq-loading' ).hide();
          });
      }
    });
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
  clear: both;
}

.bbq-content {
  border: 1px solid #913D00;
  border-top: none;
  padding: 8px;
  margin: 0;
  float: left;
  width: 682px;
  height: 152px;
  -moz-border-radius-bottomleft: 10px;
  -moz-border-radius-bottomright: 10px;
  -webkit-border-bottom-left-radius: 10px;
  -webkit-border-bottom-right-radius: 10px;
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
  In this example, window.location.hash is used to store a serialized data object representing the state of multiple "widgets". Due to the flexibility of $.bbq.pushState(), a widget doesn't need to know the state of any other widget to push a state change onto the history, only their state needs to be specifed and it will be merged in, creating a new history entry and a page state that is bookmarkable. Of course, if you only want to keep track of a single item on the page, you might want to check out the <a href="../fragment-basic/">basic window.onhashchange</a> example.
</p>


<h3>This div.bbq widget has id "bbq1"</h3>

<div class="bbq" id="bbq1">
  <div class="bbq-nav bbq-nav-top">
    <a href="#burger.html">Burgers</a> |
    <a href="#chicken.html">Chicken</a> |
    <a href="#kebabs.html">Kebabs</a>
  </div>
  
  <div class="bbq-content">
    
    <!-- This will be shown while loading AJAX content. You'll want to get an image that suits your design at http://ajaxload.info/ -->
    <div class="bbq-loading" style="display:none;">
      <img src="/shell/images/ajaxload-15-white.gif" alt="Loading"/> Loading content...
    </div>
    
    <!-- This content will be shown if no path is specified in the URL fragment. -->
    <div class="bbq-default bbq-item">
      <img src="bbq.jpg" width="200" height="150">
      <h1>jQuery BBQ</h1>
      <p>Click a nav item above to load some delicious AJAX content! Also, once
        the content loads, feel free to further explore our savory delights by
        clicking any inline links you might see.</p>
    </div>
    
  </div>
  
  <div style="clear:both;"></div>
</div>

<h3>This div.bbq widget has id "bbq2"</h3>

<div class="bbq" id="bbq2">
  <div class="bbq-nav bbq-nav-top">
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
      <img src="bbq.jpg" width="200" height="150">
      <h1>jQuery BBQ</h1>
      <p>And there's plenty more where that came from! Don't forget to click
        here for some down-home AJAX content, cooked special, just for this
        content area. You just can't have too much of a good thing!</p>
    </div>
    
  </div>
  
  <div style="clear:both;"></div>
</div>

<h3>The code</h3>

<p>Note that a lot of the following code is very similar to the <a href="../fragment-basic/">basic window.onhashchange</a> example. That's intentional! They're functionally very similar, but while this version is far more robust, it is somewhat more complex. Look at both to see which meets your needs, and don't be afraid to adapt. Also, if you want to see a robust AND simple implementation, be sure to check out the <a href="../fragment-jquery-ui-tabs/">jQuery UI Tabs</a> example.</p>

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