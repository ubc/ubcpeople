<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
	<?php wp_enqueue_script("jquery"); ?>
	
	<?php wp_head(); ?>
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/fileuploader.css" rel="stylesheet" type="text/css">	
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/colorbox.css" rel="stylesheet" type="text/css">	
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/fileuploader.js" type="text/javascript"></script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.colorbox-min.js" type="text/javascript"></script>

	
	<link type="text/css" href="<?php echo get_stylesheet_uri() ?>" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
	<script src="http://ajax.cdnjs.com/ajax/libs/json2/20110223/json2.js"></script>
	<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
	<script type="text/javascript">
		var ajaxURL = '<?php echo admin_url('admin-ajax.php?action=ubcpeople_update_post')?>';
	</script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/profile.js" type="text/javascript"></script>
	
</head>
<body>