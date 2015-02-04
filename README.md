nemesis -- Simple PHP5 Framework
=============================

Author
------------
* Kimi (kimhimitu@gmail.com)

Requires
------------
* PHP v5.3 or higher
* htaccess (for Apache) and url_rewriting PHP modules

Apache .htaccess
------------

	<IfModule mod_rewrite.c>
		#Symlinks maybe needed for URL rewriting
		Options +FollowSymLinks
		RewriteEngine On
		#if you want to exclude some directories from url rewriting
		#RewriteCond %{REQUEST_URI} !^/(site2|site3/.*)$
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule ^(.*)$ index.php?/$1 [QSA,L]
	</IfModule>


Lighttpd.conf
------------

	$HTTP["host"]  =~ "www\.mydomain\.com"{
		server.document-root = "/pathToNemesisRoot/"
		accesslog.filename   = "/PathToLogs/nemesisaccess.log"
	 	url.rewrite = (
			"^/apps/(.*)\.(.+)$" => "$0",
			"^/public/(.*)\.(.+)$" => "$0",
			"^/(.+)/?$" => "/index.php/$1"
		)
	}

Tree
------------
* /core : contains all NEMESIS core components

With App Class
* /apps : contains all applications built with NEMESIS FRAMEWORK
* /public : contains all public files sent via FTP (example: "/public/myfile.pdf" is accessible in public) 

With Plugin Class
* /plugins : contains all plugins for NEMESIS FRAMEWORK

With CSSMin Plugin
* /cache : common cache directory, it contains all thumbnails and css/js compressed files

Configuration
------------
* CHMOD 777 on /core/errors.log

Changelog
---------

### 0.3
* Simplification, re-organization of classes dependencies
* init.php replaced with bootstrap.php, it is now clean and
* new way to instance plugins and apps independently from Loader Class
* Routes configuration has now its own class with URL Class dependence
* Loader class can initialize a class with a initClass method
* errors.log changes its path to /core/errors.log
* core_functions() and core_loader() appears in bootstrap to include the required libraries

### 0.2
* Test on Lighttpd / Fixed url rewriting

### 0.1
* Initial Release
