# jQuery BBQ: Back Button & Query Library #
[http://benalman.com/projects/jquery-bbq-plugin/](http://benalman.com/projects/jquery-bbq-plugin/)

Version: 1.1, Last updated: 1/9/2010

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
1.3.2, 1.4a2

### Browsers Tested ###
Internet Explorer 6-8, Firefox 2-3.7, Safari 3-4, Chrome, Opera 9.6-10.1.

### Unit Tests ###
[http://benalman.com/code/projects/jquery-bbq/unit/](http://benalman.com/code/projects/jquery-bbq/unit/)


## Release History ##

1.1   - (1/9/2010) Broke out the jQuery BBQ event.special window.onhashchange functionality into a separate plugin for users who want just the basic event & back button support, without all the extra awesomeness that BBQ provides. This plugin will be included as part of jQuery BBQ, but also be available separately. See jQuery hashchange event plugin for more information. Also added the $.bbq.removeState method.  
1.0.3 - (12/2/2009) Fixed an issue in IE 6 where location.search and location.hash would report incorrectly if the hash contained the ? character. Also $.param.querystring and $.param.fragment will no longer parse params out of a URL that doesn't contain ? or #, respectively.  
1.0.2 - (10/10/2009) Fixed an issue in IE 6/7 where the hidden IFRAME caused a "This page contains both secure and nonsecure items." warning when used on an https:// page.  
1.0.1 - (10/7/2009) Fixed an issue in IE 8. Since both "IE7" and "IE8 Compatibility View" modes erroneously report that the browser supports the native window.onhashchange event, a slightly more robust test needed to be added.  
1.0   - (10/2/2009) Initial release


## License ##
Copyright (c) 2010 "Cowboy" Ben Alman  
Dual licensed under the MIT and GPL licenses.  
[http://benalman.com/about/license/](http://benalman.com/about/license/)
