<?php
header("Content-type: text/css");
if (isset($_GET['font'])) 
{ $font = $_GET['font'];}
else { $font = 'sans-serif'; }
if (isset($_GET['font_title'])) 
{ $font_title = $_GET['font_title'];}
else { $font_title = 'sans-serif'; }
if (isset($_GET['font_content'])) 
{ $font_content = $_GET['font_content'];}
else { $font_content = 'sans-serif'; }
if (isset($_GET['width_menu'])) 
{ $width_menu = $_GET['width_menu'];}
else { $width_menu = '280'; }
if (isset($_GET['width_content'])) 
{ $width_content = $_GET['width_content'];}
else { $width_content = '350px'; }
if (isset($_GET['width_left'])) 
{ $width_left = $_GET['width_left'];}
else { $width_left = '160px'; }
if (isset($_GET['width_right'])) 
{ $width_right = $_GET['width_right'];}
else { $width_right = '160px'; }
?>

/**		FONT	**/

.logo span {
font-family: '<?php echo $font_title ; ?>', 'Open Sans';
}

h1, .componentheading, h2.contentheading, .blog-featured h2 {
font-family: '<?php echo $font ; ?>', 'Open Sans';
}

.drop-down ul li a, .drop-down ul li span.separator, .search-module .inputbox {
font-family: '<?php echo $font ; ?>', 'Open Sans';
}

.left_column h3, .right-module-position h3, .top-module-position h3, .bottom-module-position h3, .user1 h3, .user2 h3, .user3 h3, 
.user4 h3, .user5 h3, .user6 h3, .address h3, .translate h3, .search h3, top_menu h3, .bottom_menu h3 {
font-family: '<?php echo $font ; ?>', 'Open Sans';
}

.submenu li a, .submenu li span.separator {
font-family: '<?php echo $font ; ?>', 'Open Sans';
}

body {
font-family: '<?php echo $font_content ; ?>';
}


/**			Width 		**/

#column-menu, .drop-down, .search-module {
width:<?php echo $width_menu ; ?>px;
}

#column-content {
margin: 0 0 0 <?php echo $width_menu ; ?>px;
}

.module_google_map, .module_video {
padding-left: <?php echo $width_menu ; ?>px;
}

#main-column-content {
width:<?php echo $width_content ; ?>;
}

.left_column {
width:<?php echo $width_left ; ?>;
}

.right-module-position {
width:<?php echo $width_right ; ?>;
}

/**  Minimum width for no responsive mode 		**/

#column-content {
min-width : <?php echo (980 - $width_menu) ; ?>px;
}