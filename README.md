# YOOTheme modules for WordPress

_useful modules to extend WordPress YooTheme Pro theme functionalitites_  


## Requirements

These modules have been tested:
+ WordPress 6.0.1
+ YOOTheme Pro 2.7.22
+ PHP 8.1.8


## Install

On your `child YOOTheme Pro theme` root directory:
+ create a new `modules` folder
+ create a new `config.php` file
+ upload all these modules in the `modules` folder
+ enjoy.

Here is the `config.php` content:

````php
<?php

$app->load(__DIR__ . '/modules/*/bootstrap.php');

return [];

````


## How to use

### Extended Post

This module allows you to get the featured image orientation.  

On `Custom Post(s)` dynamic contents, you will find a new `Featured Image Orientation` field.  
This field will return:
+ `portrait` if featured post image's width is lower than its height
+ `square` if width is equal to height
+ or `landscape` in all other cases

**Idea:**
+ So now you are able to choose the display of a specific section depending on the featured image orientation.  

**Examples of use:**
+ Landscape (default) format: https://www.thecapsule.fr/culture/films-series-culture/blonde-le-nouveau-film-qui-retrace-le-destin-de-marilyn-monroe-50384/
+ Portrait format: https://www.thecapsule.fr/lifestyle/adresses-lifestyle/les-terrasses-parisiennes-preferees-de-la-redaction-thecapsule-48601/

### Extended User

This module allows you to get all social links provided in the User section thanks to [WordPress SEO plugin](https://wordpress.org/plugins/wordpress-seo/).  

Everywhere with Author contents, you will find a new list of social networks. Here is a list of SN you can find:
+ Instagram
+ YouTube
+ Facebook
+ Twitter
+ LinkedIn
+ Pinterest
+ MySpace
+ SoundCloud
+ Tumblr
+ Wikipedia

### Menu Items

This module allows you to select a WordPress menu as a dynamic content.  
Note this feature is available on YOOTheme Pro 3.0 version.  

A new `Custom Menu Items` dynamic content will appears in the list of dynamic contents.  
This option will let you choose a custom WordPress menu and display its items.  

**Idea:**
+ So now you are able to combine List element and Custom Menu Items dynamic contents to build your footer links.  

**Examples of use:**
+ The footer is built with this module: https://www.thecapsule.fr/

### Popular Posts

This module makes [WordPress Popular Posts](https://wordpress.org/plugins/wordpress-popular-posts/) plugin works on YOOTheme Pro 2.x until the 3.x version.  

### Sticky Posts

This module allows you to get your Sticky posts as dynamic contents.  

A new `Sticky Articles` dynamic content will appears in the list of dynamic contents.  
This option will let you choose and filter through WordPress default filters and display sticky posts.  

**Idea:**
+ So now you are able to combine `List element` and Custom Menu Items dynamic contents to build your footer links.  

**Examples of use:**
+ The footer is built with this module: https://www.thecapsule.fr/


## Authors and Copyright

Made with ♥ by **[Achraf Chouk](http://github.com/crewstyle "Achraf Chouk")**

+ http://fr.linkedin.com/in/achrafchouk/
+ http://twitter.com/crewstyle
+ http://github.com/crewstyle

Please, read [LICENSE](https://github.com/crewstyle/yohoho.slyder/blob/master/LICENSE "LICENSE") for more details.
