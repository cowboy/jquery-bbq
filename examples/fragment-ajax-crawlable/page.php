<?PHP

// Include the XMLpage class.
require "XMLpage.php";

// Generate appropriate content-type header.
$is_xhr = strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest';
header( 'Content-type: application/' . ( $is_xhr ? 'json' : 'x-javascript' ) );

// Initialize the XMLpage, fetching the appropriate page's data.
$page = new XMLpage(array( 'id' => $_GET['id'] ));

// Return the requested page data in JSON format.
print json_encode(array(
  'attr' => $page->attr,
  'content' => $page->content,
));

?>