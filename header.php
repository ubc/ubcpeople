<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
	<?php wp_enqueue_script("jquery"); ?>
	
	<?php wp_head(); ?>
	
	<link type="text/css" href="<?php echo get_stylesheet_uri() ?>" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
	<script type="text/javascript">
		var postData = { box: {}, bg: {} };
		var ajaxURL = '<?php echo admin_url('admin-ajax.php?action=ubcpeople_update_post')?>';
	</script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/profile.js" type="text/javascript"></script>
	
</head>
<body>