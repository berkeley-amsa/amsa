<?php 
/**
 # mod_jvslideshow - JV Slideshow
 # @version		1.6.x
 # ------------------------------------------------------------------------
 # author    Open Source Code Solutions Co
 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.
 # Websites: http://www.joomlavi.com
 # Technical Support:  http://www.joomlavi.com/my-tickets.html
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
?>
<div id="jv-slideshow-<?php echo $module->id?>" class="jv-slideshow">
	<div class="jv-slideshow-content">
		<div class="jv-slideshow-items" style="height:<?php echo $jvslideshow_height; ?>px">					
			<div class="loader">
				<img src="modules/mod_jvslideshow/assets/images/loader.gif" alt="Loading"/>
			</div>
		</div>	
    	<?php if($jvslideshow_captions == 1){?>    	
		<div class="jv-slideshow-captions">
			<div class="description"></div>
		</div>		
       	<?php } ?> 
        
	</div>
	<?php if($jvslideshow_arrows == 1){?>
	<div class="jv-slideshow-arrows">
		<ul>
			<?php if($jvslideshow_firstarrow == 1){ ?>
			<li class="first"><a title="First (Shift + Leftwards Arrow)">First (Shift + Leftwards Arrow)</a></li>
			<?php } ?>
			<?php if($jvslideshow_prevarrow == 1){ ?>
			<li class="prev"><a title="Previous (Leftwards Arrow)">Previous (Leftwards Arrow)</a></li>
			<?php } ?>
			<?php if($jvslideshow_pausearrow == 1){ ?>
			<li class="pause"><a title="Pause/Play (P)">Pause/Play (P)</a></li>
			<?php } ?>
			<?php if($jvslideshow_nextarrow == 1){ ?>
			<li class="next"><a title="Next (Rightwards Arrow)">Next (Rightwards Arrow)</a></li>
			<?php } ?>
			<?php if($jvslideshow_lastarrow == 1){ ?>
			<li class="last"><a title="Last (Shift + Rightwards Arrow)">Last (Shift + Rightwards Arrow)</a></li>
			<?php } ?>
		</ul>		
	</div>	
	<?php } ?>
	<div class="jv-slideshow-controls">
		<ul>
			<?php
			for($i = 1; $i <= $images_count; $i++)
			{			
			?>
			<li><a href="#<?php echo $i;?>" title="<?php echo $i;?>"><?php 
				if($jvslideshow_thumbnail != 0 && $jvslideshow_bulleticon != 0){
					echo '<img class="thumb" width="'.$jvslideshow_thumbnailwidth.'" height="'.$jvslideshow_thumbnailheight.'" src="'. $thumbnails[$i-1] .'" alt="'.$i.'"/>';
				}
				elseif($jvslideshow_bulleticon == 1)
				{ 
					echo '<img class="icon" src="modules/mod_jvslideshow/assets/images/dummy.gif" alt="'.$i.'"/>';
				}
				else
				{ 
					echo $i;
				}?></a></li>
			<?php
			}
			?>
		</ul>
	</div>	
</div>