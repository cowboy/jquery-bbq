<?PHP
/*!
 * XMLpage - v0.1pre - 8/30/2010
 * http://benalman.com/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */

// About:
// 
// A very simple class for retrieving HTML content and other arbitrary metadata
// from a very simple XML file.
// 
// Usage:
// 
// $page = new XMLpage(); // Initialize.
// $page = new XMLpage(array('id' => `id`)); // Initialize.
// 
// $page->attr['id']      // The page node "id" attribute.
// $page->attr['title']   // The page node "title" attribute.
// $page->content         // The contents of the page node.
// 
// $page->options['id']   // The requested "id".
// 
// Notes: 
// 
// * Either use valid XHTML content, or wrap the content inside the page node
//   with <![CDATA[ ... ]]> tags.
// * Page attribute values are completely arbitrary and will be auto-populated
//   into the `attr` array. The only required attribute is "id".

class XMLpage {
  
  private $dom, $xpath;
  
  public  $page, 
          $content, 
          $attr     = array(), 
          $options  = array(
            'id'            => '',
            'xml'           => 'pages.xml',
            'fallback_ids'  => array( '404', '' )
          );  
  
  public function __construct( $options = array() ) {
  
    // Override any default options with passed options.
    $this->options  = array_merge($this->options, $options);
    
    // Initialize XML and XPath objects.
    $this->dom      = new DOMDocument();
    $this->dom->load( $this->options['xml'] );
    $this->xpath    = new DOMXPath( $this->dom );
    
    // While the requested id will always be tried first, in case that page
    // doesn't exist, the first page specified in `fallback_ids` will be used
    // instead.
    array_unshift( $this->options['fallback_ids'], $this->options['id'] );
    
    foreach ( $this->options['fallback_ids'] as $id ) {
      // If `id` page is defined in the XML, load and initialize it.
      if ( $this->load_page( $id ) ) {
        $this->init_page( $id );
        break;
      }
    }
    
    return $this;
  }
  
  // Load a page node from the XML document and return true if successful.
  private function load_page( $id = '' ) {
    // Get the page node matching this ID.
    $this->page = $this->xpath->query( "/pages/page[@id='$id']" )->item(0);
    
    // Return the success value.
    return isset( $this->page );
  }
  
  // Initialize page vars from the XML.
  private function init_page( $id ) {
    // The HTML content of the page node.
    $dom = new DOMDocument();
    $dom->loadXML( $this->dom->saveXML( $this->page ) );
    $this->content = $dom->saveHTML();
    
    // An array of attribute values.
    foreach ( $this->page->attributes as $name => $node ) {
      $this->attr[ $name ] = $node->nodeValue;
    }
    
    return $this;
  }
  
};