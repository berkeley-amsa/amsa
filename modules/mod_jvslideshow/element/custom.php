
<?php
/**
 # mod_jvnews - JV NEWS
 # @version        1.6.x
 # ------------------------------------------------------------------------
 # author    Open Source Code Solutions Co
 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.
 # Websites: http://www.joomlavi.com
 # Technical Support:  http://www.joomlavi.com/my-tickets.html
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class JFormFieldCustom extends JFormField
{
    /**
     * The field type.
     *
     * @var        string
     */
    protected $type = 'Custom';

    /**
     * Method to get the field input markup.
     *
     * @return    string    The field input markup.
     * @since    1.6
     */
    protected function getInput()
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JURI::root(true). '/modules/mod_jvslideshow/element/custom.js');
        $doc->addStyleSheet(JURI::root(true). '/modules/mod_jvslideshow/element/custom.css');
        $doc->addScriptDeclaration('
            window.addEvent("domready",function(){
                new FieldAddImageList($("'.$this->id.'"));
            });
            
        ');
        return '<textarea style="display:none" id="'.$this->id.'" name="'.$this->name.'">'.($this->value?$this->value:'[]').'</textarea><div class="listimage"></div>';

    }

}
