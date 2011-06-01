<?php

	$attr['class'] .= ' ' . dw_campaigns_make_body_class();
	if($tabs || $tabs2) {
		$attr['class'] .= ' sidebar-right';	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <?php print $head ?>
    <?php print $styles ?>
    <?php print $scripts ?>
    <title><?php print $head_title ?></title>
  </head>
  <body <?php print drupal_attributes($attr) ?>>


  <?php print $skipnav ?>

  <?php if ($help || ($show_messages && $messages)): ?>
    <div id='console'><div class='limiter clear-block'>
      <?php print $help;
 ?>
      <?php
		if($show_messages && $messages) : print $messages;
		endif; ?>
    </div></div>
  <?php endif; ?>

  <?php if ($header): ?>
    <div id='header'><div class="inner"><div class='limiter clear-block'>
      <?php print $header;
 ?>
    </div></div></div>
  <?php endif; ?>

  <div id='branding'><div class='limiter clear-block'>
    <?php if ($site_name): ?><h1 class='site-name'><?php print $site_name ?></h1><?php endif; ?>
    <?php if ($search_box): ?><div class="block block-theme"><?php print $search_box ?></div><?php endif; ?>
  </div></div>

  <div id='navigation'><div class='limiter clear-block'>
    <?php if (isset($primary_links)) : ?>
      <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>
    <?php endif; ?>
    <?php if (isset($secondary_links)) : ?>
      <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')) ?>
    <?php endif; ?>
  </div></div>

  <div id='page'><div class='limiter clear-block'>
  	
    <div id='content-top' class='clear-block'><?php print $contentTop;
 ?></div>


    <div id='main' class='clear-block'>
    	<div class="bg">
		    
		    <?php if ($left): ?>
		      <div id='left' class='clear-block'>
		      	<?php print $left ?>
	      	</div>
		    <?php endif; ?>
		    
	        <?php if ($right || $tabs || $tabs2): ?>
		      <div id='right' class='clear-block'>
		      	<?php if ($tabs) print $tabs ?>
		      	<?php if ($tabs2) print $tabs2 ?>
		      	<?php print $right ?>
	      	</div>
		    <?php endif; ?>
		    
	        <?php if($breadcrumb) { print $breadcrumb; } ?>
	        
	        <?php
				if($mission) : print '<div id="mission">' . $mission . '</div>';
				endif; ?>
	        <?php if ($title): ?><h1 class='page-title'><?php print $title ?></h1><?php endif; ?>
	        
	        
	        
	        <div id='content' class='clear-block'>
	        	<?php print $content ?>
	        	<?php print $contentInnerBottom ?>
        	</div>
	        
	    </div>
    </div>

    <div id='content-bottom' class='clear-block'><?php print $contentBottom;?></div>
    

  </div></div>

  <div id="footer"><div class='limiter clear-block'>
    <?php print $feed_icons ?>
    <?php print $footer ?>
    <?php print $footer_message ?>
  </div></div>

  <?php print $closure ?>


  </body>
</html>
