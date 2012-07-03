<?php
function ubcpeople_ubc_wiki($username){
	echo do_shortcode("[wiki-embed url='http://wiki.ubc.ca/User:" . $username . "'   no-edit no-contents ]"); 
	echo '<p><a href="http://wiki.ubc.ca/Special:Contributions/' . $username . '">See wiki contributions</a></p>';
}