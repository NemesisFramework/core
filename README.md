NEMESIS FRAMEWORK 
=============================

Author
------------
* Nicolas Castelli (castelli.nc@gmail.com)

Requires
------------
* PHP v5.3 or higher
* htaccess (for Apache) and url_rewriting PHP modules

Apache .htaccess
------------
* htaccess and url_rewriting PHP modules


Htaccess
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
			"^/(.*)\.(.+)$" => "$0",
			"^/(.+)/?$" => "/index.php/$1"
		)
	}

Tree
------------
* /apps : contains all applications built with NEMESIS FRAMEWORK
* /cache : common cache directory, it contains all thumbnails and css/js compressed files
* /core : contains all NEMESIS core components
* /logs : contains file PHP errors logs (check it as often as you get a bug !)
* /plugins : contains all plugins for NEMESIS FRAMEWORK
* /public : contains all public files sent via FTP (example: "/public/myfile.pdf" is accessible in public) 

Configuration
------------
* CHMOD 777 on /cache 
* Define Applications Instances in /index.php with "$NEMESIS" loader var


Changelog
---------

### 0.1
* Initial Release
