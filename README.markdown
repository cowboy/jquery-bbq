# jQuery BBQ: Back Button & Query Library #
[http://benalman.com/projects/jquery-bbq-plugin/](http://benalman.com/projects/jquery-bbq-plugin/)

Version: 1.3pre, Last updated: 8/26/2010

jQuery BBQ enables simple, yet powerful bookmarkable #hash history via a cross-browser window.onhashchange event. In addition, jQuery BBQ provides a full jQuery.deparam() method, along with both fragment and query string parse and merge utility methods.

Visit the [project page](http://benalman.com/projects/jquery-bbq-plugin/) for more information and usage examples!


## Documentation ##
[http://benalman.com/code/projects/jquery-bbq/docs/](http://benalman.com/code/projects/jquery-bbq/docs/)


## Examples ##
These working examples, complete with fully commented code, illustrate a few
ways in which this plugin can be used.

[http://benalman.com/code/projects/jquery-bbq/examples/fragment-basic/](http://benalman.com/code/projects/jquery-bbq/examples/fragment-basic/)  
[http://benalman.com/code/projects/jquery-bbq/examples/fragment-advanced/](http://benalman.com/code/projects/jquery-bbq/examples/fragment-advanced/)  
[http://benalman.com/code/projects/jquery-bbq/examples/fragment-jquery-ui-tabs/](http://benalman.com/code/projects/jquery-bbq/examples/fragment-jquery-ui-tabs/)  
[http://benalman.com/code/projects/jquery-bbq/examples/deparam/](http://benalman.com/code/projects/jquery-bbq/examples/deparam/)

## Support and Testing ##
Information about what version or versions of jQuery this plugin has been
tested with, what browsers it has been tested in, and where the unit tests
reside (so you can test it yourself).

### jQuery Versions ###
1.2.6, 1.3.2, 1.4.1, 1.4.2

### Browsers Tested ###
Internet Explorer 6-8, Firefox 2-4, Chrome 5-6, Safari 3.2-5, Opera 9.6-10.60, iPhone 3.1, Android 1.6-2.2, BlackBerry 4.6-5.

### Unit Tests ###
[http://benalman.com/code/projects/jquery-bbq/unit/](http://benalman.com/code/projects/jquery-bbq/unit/)


## Known issues ##

While the included jQuery hashchange event implementation is quite stable and robust, there are a few unfortunate browser bugs surrounding expected hashchange event-based behaviors, independent of any JavaScript window.onhashchange abstraction. See the following examples for more information:

Chrome: Back Button  
[http://benalman.com/code/projects/jquery-hashchange/examples/bug-chrome-back-button/](http://benalman.com/code/projects/jquery-hashchange/examples/bug-chrome-back-button/)

Firefox: Remote XMLHttpRequest  
[http://benalman.com/code/projects/jquery-hashchange/examples/bug-firefox-remote-xhr/](http://benalman.com/code/projects/jquery-hashchange/examples/bug-firefox-remote-xhr/)

WebKit: Back Button in an Iframe  
[http://benalman.com/code/projects/jquery-hashchange/examples/bug-webkit-hash-iframe/](http://benalman.com/code/projects/jquery-hashchange/examples/bug-webkit-hash-iframe/)

Safari: Back Button from a different domain  
[http://benalman.com/code/projects/jquery-hashchange/examples/bug-safari-back-from-diff-domain/](http://benalman.com/code/projects/jquery-hashchange/examples/bug-safari-back-from-diff-domain/)

## Release History ##

1.3pre - (8/26/2010) Integrated jQuery hashchange event v1.3, which adds document.title and document.domain support in IE6/7, BlackBerry support, better Iframe hiding for accessibility reasons, and the new jQuery.fn.hashchange "shortcut" method. Added the jQuery.param.sorted method which reduces the possibility of extraneous hashchange event triggering. Added the jQuery.param.fragment.ajaxCrawlable method which can be used to enable Google "AJAX Crawlable mode."  
1.2.1 - (2/17/2010) Actually fixed the stale window.location Safari bug from jQuery hashchange event in BBQ, which was the main reason for the previous release!  
1.2   - (2/16/2010) Integrated jQuery hashchange event v1.2, which fixes a Safari bug, the event can now be bound before DOM ready, and IE6/7 page should no longer scroll when the event is first bound. Also added the jQuery.param.fragment.noEscape method, and reworked the hashchange event (BBQ) internal "add" method to be compatible with changes made to the jQuery 1.4.2 special events API.  
1.1.1 - (1/22/2010) Integrated jQuery hashchange event v1.1, which fixes an obscure IE8 EmulateIE7 meta tag compatibility mode bug.  
1.1   - (1/9/2010) Broke out the jQuery BBQ event.special hashchange event functionality into a separate plugin for users who want just the basic event & back button support, without all the extra awesomeness that BBQ provides. This plugin will be included as part of jQuery BBQ, but also be available separately. See jQuery hashchange event plugin for more information. Also added the jQuery.bbq.removeState method and added additional jQuery.deparam examples.  
1.0.3 - (12/2/2009) Fixed an issue in IE 6 where location.search and location.hash would report incorrectly if the hash contained the ? character. Also jQuery.param.querystring and jQuery.param.fragment will no longer parse params out of a URL that doesn't contain ? or #, respectively.  
1.0.2 - (10/10/2009) Fixed an issue in IE 6/7 where the hidden IFRAME caused a "This page contains both secure and nonsecure items." warning when used on an https:// page.  
1.0.1 - (10/7/2009) Fixed an issue in IE 8. Since both "IE7" and "IE8 Compatibility View" modes erroneously report that the browser supports the native window.onhashchange event, a slightly more robust test needed to be added.  
1.0   - (10/2/2009) Initial release


## License ##
Copyright (c) 2010 "Cowboy" Ben Alman  
Dual licensed under the MIT and GPL licenses.  
[http://benalman.com/about/license/](http://benalman.com/about/license/)
