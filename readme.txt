=== CALgraph ===
Contributors: pacius, rayholland
Donate link: http://abacms.org/
Tags: graph by date, line graph
Requires at least: 2.0
Tested up to: 3.5.1 
Stable tag: 0.9.1

Graphing by date (calendar) with lines and asterisk, cross or triangle. 

== Description ==

[Howto Video](http://abacms.org/?page_id=48). The x axis shows dates. Variables can be added/deleted and drawn as lines, asterisk, cross or triangle. As data is entered graph appears below. If mistake made entering data, data point can be deleted and graph is redrawn. When complete right-click graph and copy location, go to Editor "Add Image" and paste in location or "Browse Server" and select graph. Graph is drawn as .png file in (wp-content/uploads). Requires php-image-graph, php-image-canvas, php-gd, php-pear, Image_Color. Calgraph grew out of Simple Graph by Pasi Matilainen.


== Installation ==

1. Unzip calgraph.zip in `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Check `/wp-content/uploads/` exists and is writeable by webserver (permissions of 755 sufficient if owned by webserver user)
1. Requires php-image-graph, php-image-canvas, php-gd, php-pear, Image_Color installed (See below). 

= To Install Requirements: =
On OS like Ubuntu:

\# apt-get install php-image-graph php-image-canvas
(_Will also install php-pear and php-gd as dependencies_)

\# pear install Image_Color


== Frequently Asked Questions ==

= The graph is not drawn or redrawn with the new data I just added or deleted =
Your browser may be caching older image: clear cache. You can also set browser to check site for new image on each visit instead of using its cache.

= I logged in and can't find my graph =
Graphs are created per user and named "username""graph#"\_calgraph.png (like admin1\_calgraph.png). You can't view other users graphs from CALgraph page. You can though publish other users graphs if you know username and graph# - "Add Image" in Editor and "Browse Server" for file. 

= The data appears as a list below but no graph =
Check `/wp-content/uploads/` exists and is writeable by webserver (permissions of 755 sufficient if owned by webserver user).
You can verify this is likely the problem by opening `/wp-content/plugins/calgraph/calgraph.php` and commenting out line:19 

Change

`error_reporting(1);`

to

`//error_reporting(1);`

Retry Update/Display Graph and if you see warning similar to:
**Warning: imagepng() [function.imagepng]: Unable to open '../wp-content/uploads/admin1_calgraph.png' for writing: Permission denied in /usr/share/php/Image/Canvas/GD/PNG.php on line 119**

Make sure uploads directory exists and has sufficient write permissions. When graph appears ok change line:19 back to what it was.


== Screenshots ==

1. Data entered here.
2. Graph appearing below. Above graph: list of data points entered.
