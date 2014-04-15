Easy Folder Listing Pro

Version: 1.5
Copyright: Michael A. Gilkes
License: GNU/GPL v2


Minimum Requirements:
 > Joomla 1.6 or 1.7
 > PHP 5.2+
 

Description:
This is a flexible, simple-to-use plugin that is used to list the contents 
of any folder in either a table or a list. The folder listing can display 
the filename, with or without the extension, with or without the date 
modified and file size, as well as a icon representing the file type. 
It has the feature of allowing the user to specify whether the filename 
listed should be linked or not.
This plugin has the additional feature that allows for displaying subfolders 
and viewing the contents of subfolders as well. The user can also click on 
subfolder name to show or hide its contents The action to show or hide the 
contents is animated via jQuery. The administrator can also specify the 
show/hide transition method. For table display, the administrator can specify 
the text headings for the columns as well.

Purchase:
This plugin is available at the nominal cost of USD $4.00.

Main features:
 > List files in any specified folder
 > Each instance of the plugin on the page can have its own custom parameter set
 > 40 Parameters are customizable per instance
 > Option to override all styles via style sheet editing
 > Show icons for file types
 > List files in either a table or a unordered list
 > Sorting in Acsending or Descening order, by filename, date modified or file size
 > Option to show/hide size, date, or date and time of the files
 > Option to link to the files or not
 > Option to specify a list of file types that should not be listed
 > Option to specify a list of file types that will only be listed
 > Color scheme of the table rows and border can be customized
 > Specify the maximum subfolder level to explore
 > Option to show subfolders
 > Collapse or Reveal all subfolder listings
 > Specify transtion type, easing and duration of subfolder sections animation
 > Specify the text to display for an empty folder
 > Specify the table headings text
 > Customize the date and time format to be displayed
 > Specify the list bullet style
 > Exclude specific files or folders from the listing
 > Option to show/hide empty folder text
 > Option to force download files
 > Option to enforce UTF-8 charset via header or meta tag
 > Option to load jQuery or not
 > User can interactively filter/search listing!
 > Plugin Manager colors available through custom Color Picker
 > Option to enclose jQuery code in noConflict()


Changes:
See changelog.txt

Known Issues:
Please note that the table transitions work best in Firefox 3.6+. Webkit-based browsers 
(Chrome, Safari) don’t render the jQuery slide transitions for tables well. Opera 10 and 
IE 8 don’t render the table transitions at all. Transitions seem to work for lists on all 
the browsers, though.


Installation:
This module is designed for Joomla 1.6 and 1.7. To install go to the install/uninstall extensions 
page of Joomla Administrator and upload the package file. Then go to the Plugin Manager 
page and activate the plugin.


Usage:
To use this plugin as content in an article, first enable the plugin and configure the 
default parameters in the Plugin Manager. To control which types of users have access to 
this plugin, set the Access Level in the plugin.

Secondly, in the article, type '{easyfolderlistingpro}'. It will work based on the default 
parameters if typed with no custom parameters.

To have multiple uploaders on the same page, just add 
'{easyfolderlistingpro parameter1='value1'|parameter2='value2'|...|parameterN='valueN'}' for
each instance you want. Please note that each instance must have something different about 
their listed parameters.


Parameters:
Name       -      Keyword      -      Default Value      -       Description
==========   =================   =======================   ======================
Location of the Listing Folder - folder - images - This is the actual folder where the files are kept.
Max Subfolder Level - level - 3 - This is the maximum subfolder level that we are going to give access to.
Show Subfolders - subfolders - Yes (1) - If this option is set to Yes, all subfolders will be listed.
Reset Header Charset to UTF-8 - ** NO KEYWORD ** - No (0) - If this option is set to Yes, a meta tag will be placed in the html head to specify UTF-8 as the charset. This parameter can *ONLY* be specified in the Plugins Manager.
Add UTF-8 Meta Tag - ** NO KEYWORD ** - No (0) - If this option is set to Yes, a META tag will be added to the webpaage to set charset to UTF-8. This parameter can *ONLY* be specified in the Plugins Manager.
jQuery to Load - ** NO KEYWORD ** - bundled - This specifies whether to load the bundled jQuery, the latest jQuery supplied online, or none at all (assuming jQuery is loaded on your site already). This parameter can *ONLY* be specified in the Plugins Manager.
Enable noConflict - ** NO KEYWORD ** - No(0) - If Yes, all jQuery code is enclosed in noConflict()
Collapse Subfolders - collapse - Yes (1) - If this option is set to Yes, all subfolders will be collapsed. If set to No, all subfolders will be expanded.
Transition Method - transition - slide (slideToggle) - This specifies the type of transition that will be toggled to show or hide the subfolder contents. Other options are fade (fadeToggle), and slide and fade (toggle)
Transition Easing - easing - swing - This specifies the type of easing that the transition will use. Other option is linear.
Transition Speed - duration - slow - This specifies the speed at which subfolder contents transitions to and from being viewed. Other option is fast.
Show Icons - icons - Yes (1) - If this option is set to Yes, each file will have an icon to describe the type of the file.
Show File Extensions - extensions - No (0) - If this option is set to No, each file name will be displayed without the file extension.
Filename Text - filetext - Filename - This is the header text for the file column.
Size Text - sizetext - Size - This is the header text for the size column.
Date Text - datetext - Date - This is the header text for the date column.
Show File Size - size - Yes (1) - If this option is set to Yes, the size of the file will be shown.
Show Date - date - Yes (1) - If this option is set to Yes, the date that each file was modified will be shown.
Show Time (with the Date above) - time - Yes (1) - If this option is set to Yes, the time stamp that each file was modified will be shown. If it is set to No, then only the Date will be shown. Note that if Date (above) is not shown at all, then time will not show either.
Date Format - dateformat - Y-m-d - The format for the date only column. For help go to: http://www.php.net/manual/en/function.date.php
DateTime Format - timeformat - Y-m-d H:i:s - The format for the date and time format. For help go to: http://www.php.net/manual/en/function.date.php
Link To Files - linktofiles - Yes (1) - If this option is set to Yes, each file will be hyperlinked for easy downloading.
Link Target - target - _blank - This specifies the link taget when you Link To Files (above). Other options are _parent, _self, and _top
Download Target - download - icon - This specifies whether to allow users to download files. You can add a download icon (icon). Or, you can make them download by clicking the linked filename (link). Or you can disable it (none).
Show Empty Message - showempty - Yes (1) - If this option is set to Yes, the empty folder message below will be shown. If set to No, the empty folder message will not be shown.
Empty Folder Message - empty - There are no files to list. - This is the message that will be displayed if a folder is empty.
Show Filter Textbox - showfilter - Yes (1) - If this option is set to Yes, the a filter textbox will be shown. If set to No, the filter textbox will not be shown.
Filter Label - filterlabel - Filter - This is the lable that will be shown preceding the textbox.
Filter Error Message - filtererror - File not found! - This is the error message that will be displayed if a filter text is not found in the listing.
Delay after typing (ms) - filterwait - 500 - This is the time delay (in milliseconds) after the user stops typing in the filter textbox before the filter is applied.
Forbidden file types (separate by semi-colon) - forbidden - htm;html;php - This is a black list of the file types that are forbidden to be listed (separated by semi-colon).
Permitted file types (separate by semi-colon) - whitelist - * - This is a white list of the only file types that are to be listed (separated by semi-colon). Use '*' to list any file type that is not forbidden above. This allows you to only list certain types of files from a folder with many different types.
Excluded Files (separate by semi-colon) - exfiles - Desktop.ini;.@__thumb;.DS_Store - This is a black list of the files that are to be excluded from the listing (separated by semi-colon).
Excluded Folders (separate by semi-colon) - exfolders - .svn;CVS;.AppleDB;.AppleDesktop;.AppleDouble - This is a black list of the folders that should be excluded from the listing (separated by semi-colon).
Display Method - method - table - If Table is selected, the folder listing will be displayed in an HTML TABLE. If List is selected, it will be displayed using the Unordered List element. Other option is list.
List Item Bullet Style - liststyle - none - This specifies the style of the list item bullet. Other options are disc, circle, square, decimal, decimal-leading-zero, lower-roman, upper-roman, lower-greek, lower-latin, upper-latin, armenian, georgian, lower-alpha, and upper-alpha. This can be overridden in the styles.css stylesheet.
Ratio of Name Column Width to Other Columns - ratio - 2 - This is the ratio of the name column's width to the other columns.
Sort Column - sortcolumn - name - This specifies the column that the list is sorted by. Other options are size and date.
Sort Direction - sortdirection - asc - This specifies whether we are sorting by ascending or descending order. Other option is desc.
Odd Table Row Background Color - oddcolor - #F0F0F6 - The background color of the odd table row.
Even Table Row Background Color - evencolor - #FFFFFF - The background color of the even table row.
Heading Row Background Color - headcolor - #E6EEEE - The background color of the heading row.
Sub Folder Background Color - subcolor - #EFEFEF - The background color of the table's subfolder rows.
Border Color - bordercolor - #CDCDCD - The color of the table's border.


Credits:
Silk icon set 1.3 by Mark James [ http://www.famfamfam.com/lab/icons/silk/ ]
jQuery 1.6.1 [ http://jquery.com ]
Color Picker jQuery Plugin by Stefan Petre [ http://www.eyecon.ro/colorpicker/ ]

