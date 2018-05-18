Version 1.0.0:
+ Login system
+ Landing page
+ "My lists"
+ "Trash"

Version 1.1.0:
+ FeatureDiscovery added to the FAB on "My lists"
+ Added initalizeHead(), initalizeNavbar(), initalizeScripts() function to functions.php
~ Switched from MaterializeCSS 0.97 to MaterializeCSS 1.0.0-rc1
~ All MaterializeCSS JS commands are replaced with M.AutoInit() 
~ New design for the item adding section to the lists
~ New header for the lists (now with halfway-fabs)
~ The server communication is switched from PHP-only to JavaScript (communicating with PHP)
~ Scale animations added when putting list to the trash, restoring list, deleting list or adding item to bought list
~ functions.php and settings.php are moved to their parent folder (/list)
- Removed head.php, navbar.php, scripts.php
- Removed includes folder

~~ UPLOADED TO GITHUB! Yey!

Version 1.1.1:
~ Fixed a bug, that you can't add item to any list.

Version 1.2:
+ Added "About" page
+ Switched from pure SHA256 to SHA256 + SALT when login and register
+ MIT License
~ Reworked login page

Version 1.2.1:
+ You can use "Bought all" function
+ No grey row left after buying all item from your list
