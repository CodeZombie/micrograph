micrograph
==========

Tiny blog system built to sit behind your website, letting you take full control.

#####How it works:
insert the files into a directory in your website, and then just include("micrograph/micrograph.php"); into any php files on your website that need it.
after that, make calls wherever needed to grab content from the backend:

mg_getPosts($offset,$amount, $order = "asc", $tagfilter = false) returns an object containing all posts that match the paramaters PLUS as paginate object 
mg_getPostById($id) returns an array with a single posts data
