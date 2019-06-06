# grab_file_content
cURL HTTP request function to fetch remote HTML data from URL, with a file_get_contents method as fallback

First choice of use is cURL since it's much faster and flexible yet we only use standard settings here for the HTTP call.
