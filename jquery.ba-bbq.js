/*!
 * jQuery BBQ: Back Button & Query Library - v0.1pre - 9/22/2009
 * http://benalman.com/projects/jquery-bbq-plugin/
 * 
 * Copyright (c) 2009 "Cowboy" Ben Alman
 * Licensed under the MIT license
 * http://benalman.com/about/license/
 */

// Script: jQuery BBQ: Back Button & Query Library
//
// Version: 0.1pre, Date: 9/22/2009
// 
// Tested with jQuery 1.3.2 in Internet Explorer 6-8, Firefox 3-3.6a,
// Safari 3-4, Chrome, Opera 9.6.
// 
// Home       - http://benalman.com/projects/jquery-bbq-plugin/
// Source     - http://github.com/cowboy/jquery-bbq/raw/master/jquery.ba-bbq.js
// (Minified) - http://github.com/cowboy/jquery-bbq/raw/master/jquery.ba-bbq.min.js (3.1kb)
// Unit Tests - http://benalman.com/code/projects/jquery-bbq/unit/
// 
// About: License
// 
// Copyright (c) 2009 "Cowboy" Ben Alman
// 
// Licensed under the MIT license
// 
// http://benalman.com/about/license/
// 
// About: Revision History
// 
// 0.1pre - Pre-initial release

(function($){
  '$:nomunge'; // Used by YUI compressor.
  
  // A few constants.
  var window = this,
    undefined,
    
    // Some convenient shortcuts.
    loc = document.location,
    aps = Array.prototype.slice,
    decode = decodeURIComponent,
    
    // Method references.
    jq_param = $.param,
    jq_param_fragment,
    jq_deparam,
    jq_deparam_fragment,
    jq_history = $.history = $.history || {},
    jq_history_add,
    fake_onhashchange,
    
    // Reused strings.
    hashchange = 'hashchange',
    str_querystring = 'querystring',
    str_fragment = 'fragment',
    str_hash = 'hash',
    str_href = 'href',
    str_src = 'src',
    
    // Does the browser support window.onhashchange?
    supports_onhashchange = 'on' + hashchange in window,
    
    // Reused RegExp.
    re_trim_querystring = /^.*\?|#.*$/g,
    re_trim_fragment = /^.*\#/, // /^[^#]*\#?/,
    
    // Used by jQuery.param.urlAttr.
    tag_attr = {};
  
  // A few commonly used bits, broken out to help reduce minified file size.
  
  function is_string( arg ) {
    return typeof arg === 'string';
  };
  
  // Why write the same function twice? Let's curry! Mmmm, curry..
  
  function curry() {
    var args = aps.call( arguments ),
      func = args.shift();
    
    return function() {
      return func.apply( this, args.concat( aps.call( arguments ) ) );
    };
  };
  
  // Method: jQuery.param.querystring
  // 
  // Retrieve the query string from a URL or the current document. If a params
  // object is passed in, a serialized params string is returned.
  // 
  // Usage:
  // 
  //  jQuery.param.querystring( [ params ] );                                - -
  // 
  // Arguments:
  // 
  //  params_or_url - (String or Object) A params string or URL containing query
  //    string params to be parsed, or an object to be serialized.
  // 
  // Returns:
  // 
  //  (String) A params string with urlencoded data in the format 'a=b&c=d&e=f'.
  
  // Method: jQuery.param.querystring (build url)
  // 
  // Merge a URL (with or without pre-existing query string params) plus any
  // object, params string or URL containing query string params into a new URL.
  // 
  // Usage:
  // 
  //  jQuery.param.querystring( url, params [, merge_mode ] );               - -
  // 
  // Arguments:
  // 
  //  url - (String) A valid URL for params to be merged into. This URL may
  //    contain a query string and/or fragment (hash).
  //  params - (Object) An object, params string, or URL containing query string
  //    params to be merged into the URL.
  //  merge_mode - (Number) Merge behavior defaults to 0 if merge_mode is not
  //    specified, and is as-follows:
  // 
  //    * 0: params argument will override any params in url.
  //    * 1: any params in url will override params argument.
  //    * 2: params argument will completely replace any params in url.
  // 
  // Returns:
  // 
  //  (String) Either a params string with urlencoded data or a URL with a
  //    urlencoded query string in the format 'a=b&c=d&e=f'.
  
  // Method: jQuery.param.fragment
  // 
  // Retrieve the fragment (hash) from a URL or the current document. If a
  // params object is passed in, a serialized params string is returned.
  // 
  // Usage:
  // 
  //  jQuery.param.fragment( [ params_or_url ] );                            - -
  // 
  // Arguments:
  // 
  //  params_or_url - (String or Object) A params string or URL containing
  //    fragment (hash) params to be parsed, or an object to be serialized.
  // 
  // Returns:
  // 
  //  (String) A params string with urlencoded data in the format 'a=b&c=d&e=f'.
  
  // Method: jQuery.param.fragment (build url)
  // 
  // Merge a URL, with or without pre-existing fragment (hash) params, plus any
  // object, params string or URL containing fragment (hash) params into a new
  // URL.
  // 
  // Usage:
  // 
  //  jQuery.param.fragment( url, params [, merge_mode ] );                  - -
  // 
  // Arguments:
  // 
  //  url - (String) A valid URL for params to be merged into. This URL may
  //    contain a query string and/or fragment (hash).
  //  params - (Object) An object, params string, or URL containing fragment
  //    (hash) params to be merged into the URL.
  //  merge_mode - (Number) Merge behavior defaults to 0 if merge_mode is not
  //    specified, and is as-follows:
  // 
  //    * 0: params argument will override any params in url.
  //    * 1: any params in url will override params argument.
  //    * 2: params argument will completely replace any params in url.
  // 
  // Returns:
  // 
  //  (String) Either a params string with urlencoded data or a URL with a
  //    urlencoded fragment (hash) in the format 'a=b&c=d&e=f'.
  
  function jq_param_sub( is_fragment, re, params_or_url, params, merge_mode ) {
    var result,
      qs,
      matches,
      url_params,
      hash;
    
    if ( params !== undefined ) {
      // Build URL by merging params into url string.
      
      // matches[1] = url part that precedes params, not including trailing ?/#
      // matches[2] = params
      // matches[3] = hash, if in 'querystring' mode, including leading #
      matches = params_or_url.match( is_fragment ? /^([^#]*)\#?(.*)$/ : /^([^#?]*)\??([^#]*)(#?.*)/ );
      
      // Convert relevant params in params_or_url to object.
      url_params = jq_deparam( matches[2] );
      
      // Get the hash if in 'querystring' mode, and it exists.
      hash = matches[3] || '';
      
      params = is_string( params )
        
        // Convert passed params string into object.
        ? jq_deparam[ is_fragment ? str_fragment : str_querystring ]( params )
        
        // Passed params object.
        : params;
      
      qs = merge_mode === 2 ? params                              // passed params replace url params
        : merge_mode === 1  ? $.extend( {}, params, url_params )  // url params override passed params
        : $.extend( {}, url_params, params );                     // passed params override url params
      
      // Convert params object to a string.
      qs = jq_param( qs );
      
      // Build URL from the base url, querystring and hash. In 'querystring'
      // mode, ? is only added if a query string exists. In 'fragment' mode, #
      // is always added.
      result = matches[1] + ( is_fragment ? '#' : qs || !matches[1] ? '?' : '' ) + qs + hash;
      
    } else if ( params_or_url ) {
      // Serialize params obj, or parse params from URL string.
      result = is_string( params_or_url )
        
        // Parse params out of string.
        ? params_or_url.replace( re, '' )
        
        // Call $.param on object.
        : jq_param( params_or_url );
      
    } else {
      result = is_fragment
        
        // Parse hash from location.href, removing leading #. Firefox urldecodes
        // location.hash by default, which breaks everything.
        ? loc[ str_hash ] ? loc[ str_href ].replace( re, '' ) : ''
        
        // Get location.search and removing any leading ?
        : loc.search.replace( /^\??/, '' );
    }
    
    return result;
  };
  
  jq_param[ str_querystring ]                  = curry( jq_param_sub, 0, re_trim_querystring );
  jq_param[ str_fragment ] = jq_param_fragment = curry( jq_param_sub, 1, re_trim_fragment );
  
  // Method: jQuery.deparam
  // 
  // Deserialize a params string into an object, optionally coercing numbers,
  // booleans, null and undefined values. This method is the counterpart to the
  // internal jQuery.param method.
  // 
  // Usage:
  // 
  //  jQuery.deparam( params_str [, coerce ] );                              - -
  // 
  // Arguments:
  // 
  //  params_str - (String) A params string to be parsed.
  //  coerce - (Boolean) If true, coerces any numbers or true, false, null, and
  //    undefined to their actual value. Defaults to false.
  // 
  // Returns:
  // 
  //  (Object) An object representing the deserialized params string.
  
  $.deparam = jq_deparam = function( params, coerce ) {
    var obj = {},
      coerce_types = { 'true': !0, 'false': !1, 'null': null };
    
    // If params is an object, serialize it first.
    params = is_string( params )
      ? params
      : jq_param( params );
    
    // Iterate over all name=value pairs.
    $.each( params.replace( /\+/g, ' ' ).split( '&' ), function(j,v){
      var param = v.split( '=' ),
        key = decode( param[0] ),
        val,
        cur = obj,
        i = 0,
        
        // If key is more complex than 'foo', like 'a[]' or 'a[b][c]', split it
        // into its component parts.
        keys = key.split( '][' ),
        keys_last = keys.length - 1;
      
      // If the first keys part contains [ and the last ends with ], then []
      // are correctly balanced.
      if ( /\[/.test( keys[0] ) && /\]$/.test( keys[ keys_last ] ) ) {
        // Remove the trailing ] from the last keys part.
        keys[ keys_last ] = keys[ keys_last ].replace( /\]$/, '' );
        
        // Split first keys part into two parts on the [ and add them back onto
        // the beginning of the keys array.
        keys = keys.shift().split('[').concat( keys );
        
        keys_last = keys.length - 1;
      } else {
        // Basic 'foo' style key.
        keys_last = 0;
      }
      
      // Are we dealing with a name=value pair, or just a name?
      if ( param.length === 2 ) {
        val = decode( param[1] );
        
        // Coerce values.
        if ( coerce ) {
          val = val && !isNaN(val)            ? +val              // number
            : val === 'undefined'             ? undefined         // undefined
            : coerce_types[val] !== undefined ? coerce_types[val] // true, false, null
            : val;                                                // string
        }
        
        if ( keys_last ) {
          // Complex key, build deep object structure based on a few rules:
          // * The 'cur' pointer starts at the object top-level.
          // * [] = array push (n is set to array length), [n] = array if n is 
          //   numeric, otherwise object.
          // * If at the last keys part, set the value.
          // * For each keys part, if the current level is undefined create an
          //   object or array based on the type of the next keys part.
          // * Move the 'cur' pointer to the next level.
          // * Rinse & repeat.
          for ( ; i <= keys_last; i++ ) {
            key = keys[i] === '' ? cur.length : keys[i];
            cur = cur[key] = i < keys_last
              ? cur[key] || ( keys[i+1] && isNaN( keys[i+1] ) ? {} : [] )
              : val;
          }
          
        } else {
          // Simple key, even simpler rules, since only scalars and shallow
          // arrays are allowed.
          
          if ( $.isArray( obj[key] ) ) {
            // val is already an array, so push on the next value.
            obj[key].push( val );
            
          } else if ( obj[key] !== undefined ) {
            // val isn't an array, but since a second value has been specified,
            // convert val into an array.
            obj[key] = [ obj[key], val ];
            
          } else {
            // val is a scalar.
            obj[key] = val;
          }
        }
        
      } else if ( key ) {
        // No value was defined, so set something meaningful.
        obj[key] = coerce
          ? undefined
          : '';
      }
    });
    
    return obj;
  };
  
  // Method: jQuery.deparam.querystring
  // 
  // Parse the query string from a URL or the current document, deserializing
  // it into an object, optionally coercing numbers, booleans, null and
  // undefined values.
  // 
  // Usage:
  // 
  //  jQuery.deparam.querystring( [ url ] [, coerce ] );                     - -
  // 
  // Arguments:
  // 
  //  url - (String) A params string or URL containing query string params to be
  //    parsed, or an object to be serialized.
  //  coerce - (Boolean) If true, coerces any numbers or true, false, null, and
  //    undefined to their actual value. Defaults to false.
  // 
  // Returns:
  // 
  //  (Object) An object representing the deserialized params string.
  
  // Method: jQuery.deparam.fragment
  // 
  // Parse the fragment (hash) from a URL or the current document, deserializing
  // it into an object, optionally coercing numbers, booleans, null and
  // undefined values.
  // 
  // Usage:
  // 
  //  jQuery.deparam.fragment( [ url ] [, coerce ] );                        - -
  // 
  // Arguments:
  // 
  //  url - (String) A params string or URL containing fragment (hash) params to
  //    be parsed, or an object to be serialized.
  //  coerce - (Boolean) If true, coerces any numbers or true, false, null, and
  //    undefined to their actual value. Defaults to false.
  // 
  // Returns:
  // 
  //  (Object) An object representing the deserialized params string.
  
  function jq_deparam_sub( mode, re, url_or_params, coerce ) {
    if ( url_or_params === undefined || typeof url_or_params === 'boolean' ) {
      // url_or_params not specified.
      coerce = url_or_params;
      url_or_params = jq_param[ mode ]();
    } else {
      url_or_params = is_string( url_or_params )
        ? url_or_params.replace( re, '' )
        : url_or_params;
    }
    
    return jq_deparam( url_or_params, coerce );
  };
  
  jq_deparam[ str_querystring ]                    = curry( jq_deparam_sub, str_querystring, re_trim_querystring );
  jq_deparam[ str_fragment ] = jq_deparam_fragment = curry( jq_deparam_sub, str_fragment, re_trim_fragment );
  
  // Method: jQuery.param.urlAttr
  // 
  // Get the internal "Default URL attribute per tag" list, or augment the list
  // with additional tag-attribute pairs, in case the defaults are insufficient.
  // 
  // In the <jQuery.fn.querystring> and <jQuery.fn.fragment> methods, this list is used to
  // determine which attribute contains the URL to be modified.
  // 
  // Default List:
  // 
  //  TAG    - URL ATTRIBUTE
  //  a      - href
  //  base   - href
  //  iframe - src
  //  img    - src
  //  form   - action
  //  link   - href
  //  script - src
  // 
  // Usage:
  // 
  //  jQuery.param.urlAttr( [ tag_attr_obj ] );                             - -
  // 
  // Arguments:
  // 
  //  tag_attr_obj - (Object) An list of tag names and associated default
  //    attribute names in the format { tag: 'attr', tag: 'attr', ... }.
  // 
  // Returns:
  // 
  //  (Object) The current internal "Default URL attribute per tag" list.
  
  (jq_param.urlAttr = function( attr_obj ) {
    return $.extend( tag_attr, attr_obj );
  })({
    // Set some reasonable defaults.
    a: str_href,
    base: str_href,
    iframe: str_src,
    img: str_src,
    form: 'action',
    link: str_href,
    script: str_src
  });
  
  // Method: jQuery.fn.querystring
  // 
  // Update URL attribute in one or more elements, merging the current URL (with
  // or without pre-existing params) plus any params object or string into a new
  // URL, which is then set into that attribute. Like <jQuery.param.querystring (build
  // url)>, but for all elements in a jQuery collection.
  // 
  // Usage:
  // 
  //  jQuery('selector').querystring( [ attr, ] params [, merge_mode ] );    - -
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    merge params into. See <jQuery.param.urlAttr> for a list of default
  //    attributes.
  //  params - (String or Object) Either a serialized params string or a params
  //    object to be merged into the URL.
  //  merge_mode - (Number) Merge behavior defaults to 0 if merge_mode is not
  //    specified, and is as-follows:
  //    
  //    * 0: params argument will override any params in attr URL.
  //    * 1: any params in attr URL will override params argument.
  //    * 2: params argument will completely replace any params in attr URL.
  // 
  // Returns:
  // 
  //  (jQuery) The initial jQuery collection of elements, but with modified URL
  //  attribute values.
  
  // Method: jQuery.fn.fragment
  // 
  // Update URL attribute in one or more elements, merging the current URL (with
  // or without pre-existing params) plus any params object or string into a new
  // URL, which is then set into that attribute. Like <jQuery.param.fragment (build
  // url)>, but for all elements in a jQuery collection.
  // 
  // Usage:
  // 
  //  jQuery('selector').fragment( [ attr, ] params [, merge_mode ] );       - -
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    merge params into. See <jQuery.param.urlAttr> for a list of default
  //    attributes.
  //  params - (String or Object) Either a serialized params string or a params
  //    object to be merged into the URL.
  //  merge_mode - (Number) Merge behavior defaults to 0 if merge_mode is not
  //    specified, and is as-follows:
  //    
  //    * 0: params argument will override any params in attr URL.
  //    * 1: any params in attr URL will override params argument.
  //    * 2: params argument will completely replace any params in attr URL.
  // 
  // Returns:
  // 
  //  (jQuery) The initial jQuery collection of elements, but with modified URL
  //  attribute values.
  
  function jq_fn_sub( mode, force_attr, params, merge_mode ) {
    if ( !is_string( params ) && typeof params !== 'object' ) {
      // force_attr not specified.
      merge_mode = params;
      params = force_attr;
      force_attr = undefined;
    }
    
    return this.each(function(){
      var that = $(this),
        
        // Get attribute specified, or default specified via $.param.urlAttr.
        attr = force_attr || tag_attr[ ( this.nodeName || '' ).toLowerCase() ],
        
        // Get URL value.
        url = attr && that.attr( attr ) || '';
      
      // Update attribute with new URL.
      that.attr( attr, jq_param[ mode ]( url, params, merge_mode ) );
    });
    
  };
  
  $.fn[ str_querystring ] = curry( jq_fn_sub, str_querystring );
  $.fn[ str_fragment ]    = curry( jq_fn_sub, str_fragment );
  
  // Method: jQuery.history.add
  // 
  // Adds a 'state' into the browser history at the current position, setting
  // location.hash, and triggering any bound <window.onhashchange> event
  // callbacks (provided the new state is different than the previous state).
  // 
  // Usage:
  // 
  //  jQuery.history.add( [ params [, merge_mode ] ] );                      - -
  // 
  // Arguments:
  // 
  //  params - (String or Object) Either a hash string beginning with #, a
  //    serialized params string or a data object to set as the current history
  //    state (location.hash). If omitted, sets the document hash to # (this is
  //    just a shortcut for $.history.add( {}, 2 ) and may cause your browser to
  //    scroll). If a hash string beginning with # is specified, will completely
  //    overwrite the existing hash.
  //  merge_mode - (Number) Merge behavior defaults to 0 if merge_mode is not
  //    specified, and is as-follows:
  // 
  //    * 0: params argument will override any params in document hash.
  //    * 1: any params in document hash will override params argument.
  //    * 2: params argument will completely replace any params in document
  //      hash.
  // 
  // Returns:
  // 
  //  Nothing.
  
  jq_history.add = jq_history_add = function( params, merge_mode ) {
    var has_args = params !== undefined,
      url = is_string( params ) && /^#/.test( params )
        
        // Params string begins with #, so overwrite document.location hash.
        ? loc[ str_href ].replace( /#.*$/, '' ) + params
        
        // Otherwise merge params into document.location using $.param.fragment.
        : jq_param_fragment( loc[ str_href ], has_args ? params : {}, has_args ? merge_mode : 2 );
    
    // Set new document.location.href. If hash is empty, use just # to prevent
    // browser from reloading the page.
    loc[ str_href ] = url + ( /#/.test( url ) ? '' : '#' );
  };
  
  // Method: jQuery.history.retrieve
  // 
  // Retrieves the current 'state' from the browser history, parsing
  // location.hash for a specific key or returning an object containing the
  // entire state, optionally coercing numbers, booleans, null and undefined
  // values.
  // 
  // Usage:
  // 
  //  jQuery.history.retrieve( [ key ] [, coerce ] );                        - -
  // 
  // Arguments:
  // 
  //  key - (String) An optional state key for which to return a value.
  //  coerce - (Boolean) If true, coerces any numbers or true, false, null, and
  //    undefined to their actual value. Defaults to false.
  // 
  // Returns:
  // 
  //  (Anything) If key is passed, returns the value corresponding with that key
  //    in the location.hash 'state', or undefined. If not, an object
  //    representing the entire 'state' is returned.
  
  jq_history.retrieve = function( key, coerce ) {
    return key === undefined || typeof key === 'boolean'
      ? jq_deparam_fragment( key ) // 'key' really means 'coerce' here
      : jq_deparam_fragment( coerce )[ key ];
  };
  
  // Property: jQuery.history.pollDelay
  // 
  // The numeric speed (in milliseconds) at which the <window.onhashchange>
  // polling loop polls. Defaults to 100.
  
  jq_history.pollDelay = 100;
  
  // Event: window.onhashchange
  // 
  // Fired when document.location.hash changes. In browsers that support it, the
  // native window.onhashchange event is used (IE8, FF3.6), otherwise a polling
  // loop is initialized and runs every <jQuery.history.pollDelay> milliseconds
  // to see if the hash has changed. In IE 6 and 7, a hidden IFRAME is created
  // to allow hash-based history to work.
  // 
  // Notes:
  // 
  // * The polling loop and iframe are not created until at least one callback
  //   is actually bound to 'hashchange'.
  // * If you need the bound callback(s) to execute immediately, in cases where
  //   the page 'state' exists on page load (via bookmark or page refresh, for
  //   example) use $(window).trigger( 'hashchange' );
  // 
  // Usage in 1.3.2:
  // 
  // > $(window).bind( 'hashchange', function() {
  // >   var hash_str = $.param.fragment(),
  // >     param_obj = $.history.retrieve(),
  // >     param_val = $.history.retrieve( 'param_name' );
  // >   ...
  // > });
  // 
  // Usage in 1.3.3+:
  // 
  // > $(window).bind( 'hashchange', function(e) {
  // >   var hash_str = e.hash,
  // >     param_obj = e.retrieve(),
  // >     param_val = e.retrieve( 'param_name' );
  // >   ...
  // > });
  
  $.event.special[ hashchange ] = {
    
    // Called only when the first 'hashchange' event is bound to window.
    setup: function() {
      // If window.onhashchange is supported natively, there's nothing to do..
      if ( supports_onhashchange ) { return false; }
      
      // Otherwise, we need to create our own.
      fake_onhashchange.start();
    },
    
    // Called only when the last 'hashchange' event is unbound from window.
    teardown: function() {
      // If window.onhashchange is supported natively, there's nothing to do..
      if ( supports_onhashchange ) { return false; }
      
      // Otherwise, we need to create our own.
      fake_onhashchange.stop();
    },
    
    // Augmenting the event object with the .hash property and .retrieve method
    // requires jQuery 1.3.3 or newer. Note: with 1.3.2, everything will work,
    // but the event won't be augmented)
    add: function( handler, data, namespaces ) {
      return function(e) {
        // e.hash is set to the value of location.hash (with any leading #
        // removed) at the time the event is triggered.
        var hash = e[ str_hash ] = jq_param_fragment();
        
        // e.retrieve() works just like $.history.retrieve(), but uses e.hash
        // stored in the event object.
        e.retrieve = function( key, coerce ) {
          return key === undefined || typeof key === 'boolean'
            ? jq_deparam( hash, key ) // 'key' really means 'coerce' here
            : jq_deparam( hash, coerce )[ key ];
        };
        
        handler.apply( this, arguments );
      };
    }
  };
  
  // fake_onhashchange does all the work of triggering the window.onhashchange
  // event for browsers that don't natively support it, including creating a
  // polling loop to watch for hash changes and in IE 6/7 creating a hidden
  // IFRAME to enable back and forward.
  fake_onhashchange = (function(){
    var self = {},
      timeout_id,
      stop,
      set_history,
      get_history;
    
    // Initialize. In IE 6/7, creates a hidden IFRAME for history handling.
    function init(){
      var iframe,
        browser = $.browser;
      
      // Most browsers don't need special methods here..
      set_history = get_history = function(val){ return val; };
      
      // But IE6/7 do!
      if ( browser.msie && browser.version < 8 ) {
        
        // Create hidden IFRAME at the end of the body.
        iframe = $('<iframe/>').hide().appendTo( 'body' )[0].contentWindow;
        
        // Get history by looking at the hidden IFRAME's location.hash.
        get_history = function() {
          return iframe.document.location[ str_hash ].replace( /^#/, '' );
        };
        
        // Set a new history item by opening and then closing the IFRAME
        // document, *then* setting its location.hash.
        set_history = function( hash, history_hash ) {
          if ( hash !== history_hash ) {
            var doc = iframe.document;
            doc.open();
            doc.close();
            doc.location[ str_hash ] = '#' + hash;
          }
        };
        
        // Set initial history.
        set_history( jq_param_fragment() );
      }
    };
    
    // Start the polling loop.
    self.start = function() {
      // First, stop the polling loop if it's already running (it shouldn't be).
      stop();
      
      // Remember the initial hash so it doesn't get triggered immediately.
      var last_hash = jq_param_fragment();
      
      // Initialize if not yet initialized.
      set_history || init();
      
      // This polling loop checks every $.history.pollDelay milliseconds to see
      // if location.hash has changed, and triggers the 'hashchange' event on
      // window when necessary.
      (function loopy(){
        var hash = jq_param_fragment(),
          history_hash = get_history( last_hash );
        
        if ( hash !== last_hash ) {
          set_history( last_hash = hash, history_hash );
          
          $(window).trigger( hashchange );
          
        } else if ( history_hash !== last_hash ) {
          jq_history_add( '#' + history_hash, 2 );
        }
        
        timeout_id = setTimeout( loopy, jq_history.pollDelay );
      })();
    };
    
    // Stop the polling loop.
    stop = self.stop = function() {
      timeout_id && clearTimeout( timeout_id );
      timeout_id = 0;
    };
    
    return self;
  })();
  
})(jQuery);
