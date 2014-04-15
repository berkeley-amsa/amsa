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
if(!class_exists('ThumbBase')){
    require_once JPATH_SITE.'/modules/mod_jvslideshow/classes/ThumbBase.inc.php';
}
if(!class_exists('GdThumb')){
    require_once JPATH_SITE.'/modules/mod_jvslideshow/classes/GdThumb.inc.php';
}


class ModJVSlideShowHelper
{
    public static function getImagesFolder($path, $titles, $description, $links){
        $items = array();        
        if(!JFolder::exists($path)) return;                
        $filter = '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$';        
        $files  = JFolder::files($path, $filter);        
        if(!count($files)) return;        
    	foreach($files as $key => $value){
    		$items[] = (object) array(
                            'name'          => JURI::base() . $path . $value, 
                            'title'         => (isset($titles[$key]) && $titles[$key] != '' ? $titles[$key] : $value), 
                            'description'   => (isset($description[$key]) && $description[$key] != '' ? $description[$key] : $value),  
                            'link'          => (isset($links[$key]) && $links[$key] != '' ? $links[$key] : ''));
    	}
        
        return $items;
    }
    	
	public static function getThumbnails($items){
        $thumbnails = array();                
		foreach($items as $key => $value){				
			$tmp = explode('/', $value->name);
			$tmp[count($tmp) - 1] = 'thumbnails/' . $tmp[count($tmp) - 1];
			$thumbnails[] = implode('/', $tmp);
    	}
        return $thumbnails;
    }
    
    public static function getImageLists($images, $titles, $description, $links, $path){
        $items = array();
        if(!count($images))return;
        
        foreach($images as $key => $value){
    		$items[] = (object) array(
                            'name'          => (preg_match('/http:\/\//', $value) || preg_match('/https:\/\//', $value)) ?  trim($value): JURI::base() . $path . trim($value), 
                            'title'         => (isset($titles[$key]) && $titles[$key] != '' ? $titles[$key] : $value), 
                            'description'   => (isset($description[$key]) && $description[$key] != '' ? $description[$key] : $value),  
                            'link'          => (isset($links[$key]) && $links[$key] != '' ? $links[$key] : ''));
    	}
        
         return $items;
    }  
        
    public static function getBanners($bannerid){  
        $items = array();        
		if($bannerid == 0) return $items;
    	$db    =& JFactory::getDBO();
    	$query = "SELECT * FROM #__banner WHERE showBanner = 1 AND catid=$bannerid ORDER BY sticky DESC, ordering";
    	$db->setQuery($query);
    	$result = $db->loadObjectList();
        
        foreach($result as $key => $value){		
    		$items[] = (object) array(
                            'name'          => JURI::base() . 'images/banners/' .$value->imageurl, 
                            'title'         => $value->description, 
                            'description'   => $value->description, 
                            'link'          => $value->clickurl);
    	}
        
        if(!count($items)) return;
        
        return $items;
    }
    
    public static function createThumbnails($images, $mode ='adaptiveresize', $thumbnail_width, $thumbnail_height){
        $resizePath = JPATH_ROOT.DS."images/resized/modules/mod_jvslideshow/".substr($mode, 0, 5)."_{$thumbnail_width}_{$thumbnail_height}/";
        $link       = JURI::base()."images/resized/modules/mod_jvslideshow/".substr($mode, 0, 5)."_{$thumbnail_width}_{$thumbnail_height}/";
        $regex      = '/<img.*src=[\'\"]([0-9A-Za-z.\/]*)?[\'\"].*>/i';
        $thumbnails = array();
        
        if(!JFolder::exists($resizePath)){
            if(!JFolder::create($resizePath)) return;
            $index = "<html><body></body></html>";
            if(!JFile::write($resizePath.DS.'index.html',$index)) return;
        }
        
        
        if(count($images)){
            foreach($images as $image){
                $imagename  = trim(self::getFileName($image->name)); 
				$fileNameParts = explode('.',$imagename);                        
                $fileExtension = array_pop($fileNameParts);  
                $fileExtension = current(explode('?',$fileExtension));
                $imagename     = current($fileNameParts).'.'.$fileExtension;
                
                if(!JFile::exists($resizePath.$imagename)){
                    if((preg_match('/http:\/\//', $image->name) || preg_match('/https:\/\//', $image->name))){
                        $thumb = new GdThumb($image->name); 
                    }else{
                        $thumb = new GdThumb(JPATH_SITE.'/'.$image->name);
                    }
                    
                    switch($mode){
                        case 'resize':
                            $thumb->resize($thumbnail_width, $thumbnail_height);
                        break;
                        case 'adaptiveresize':
                            $thumb->adaptiveResize($thumbnail_width, $thumbnail_height);
                        break;
                        case 'crop':
                            $thumb->crop(0, 0, $thumbnail_width, $thumbnail_height);
                        break;
                        case 'cropfromcenter':
                            $thumb->cropFromCenter($thumbnail_width, $thumbnail_height);
                        break;                            
                    }
                    
                    $thumb->save($resizePath.$imagename, $fileExtension);              
                }
                $thumnails[] = $link.$imagename;
            }                    
       }
       
       return $thumbnails;
        
    }
    
    public static function getFileName($url){
        if (is_string($url)) {
			$parts = explode('/', $url);
			return $parts[count($parts) - 1];
		}
        
        return false;
    }
    
	public static function endsWith($haystack,$needle,$case=true){
		if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
		return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
    }	
}