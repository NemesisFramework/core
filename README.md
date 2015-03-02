# Nemesis Framework  #
Nemesis is a small PHP Framework that I've started to sustain when I realized I needed a 
**lightweight**, **native**, **minimalistic** and **flexible** tool to quickly develop web app for testing and specific requests which require to work on a basic shared web hosting with few hardware resources.

## Features ##

The architecture is built on well known patterns (Model Controller or Model View Controller) with a basic bootstraper / class autoloader and some procedural functions, so, what PHP can do properly without any extra-dependencies.

## Core components ##

The core is customizable according to the components needed and their dependencies

### Classes ###


**Loader** : autoloader

**Hook** : trigger a hook, depends to Loader

**Session** : manage a secure session, depends to Loader

**Plugin** : manage plugins in plugins/* with a class controller, depends to Loader 

**URL** : get headers received, hash and output URLs, depends to Loader

**Router** : create routes, depends to Loader, URL 

**Api** : manage a simple JSON Web Api with a class controller, depends to Loader, URL, Router

**MVC** : add all components to build a MVC app like a view builder, depends to Loader, Hook, URL, Routes
 
**App** : manage a web app built on a MVC pattern with a class controller, depends to Loader, Hook, URL, Routes, MVC



### Functions ###

**String parser** : strip_accents, strip_specialchars, beautify, minimize, excerpt, is_email, is_phone_fr, is_date, datetime, sanitize_output

**File manager / Shorcuts** : getperms, filename, extension, upload, download

**CURL** : url_get_contents


## Required PHP version and modules ##

- PHP v5.3 or higher installed on your server or web hosting

- htaccess (for Apache) and url_rewriting PHP modules


## Installation ##


### URL Rewriting Configuration ###
For Apache Servers the content of the .htaccess file located to the framework root directory is :

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


And for Lighttpd Servers the content of Lighttpd.conf :

	$HTTP["host"]  =~ "www\.mydomain\.com"{
		server.document-root = "/pathToNemesisRoot/"
		accesslog.filename   = "/PathToLogs/nemesisaccess.log"
	 	url.rewrite = (
			"^/apps/(.*)\.(.+)$" => "$0",
			"^/public/(.*)\.(.+)$" => "$0",
			"^/(.+)/?$" => "/index.php/$1"
		)
	}


### Optional directories ###
* /apps : contains all applications built with Nemesis Framework
* /public : contains all public files sent via FTP (example: "/public/myfile.pdf" is accessible in public) 
* /plugins : contains all plugins for Nemesis Framework


### Specific configuration on a shared web hosting ###
If the framework works on a traditionnal hosting, make sure CHMOD is set to 0777 on /core/errors.log

### *index.php* instructions ###
Require bootstraper

	require_once 'core/bootstrap.php';

If the core functions are required

	core_functions();

If the autoloader is required

	core_autoloader();

Router initialization
	
	$loader = Loader::getInstance();
	$loader->initClass('Router');

Example of a web app initialization 

	$blogApp = App::getInstance('blog'); // apps/blog/app.php
	$blogApp->setAsDefault(); // if this is the default app, the URL will be / otherwise /blog
	$blogApp->run();
	echo $blogApp;

Example of a plugin initialization 

	$loader = Loader::getInstance();
	$loader->initClass('Plugin');
	$htmlPlugin = Plugin::getInstance('HTMLhelpers');
	echo $htmlPlugin->text(array('placeholder' => 'My input')); // will display <input type="text" placeholder="My input" /> 


For more examples, check the others repositories prefixed with "nemesis-" like nemesis-api-newsletter or nemesis-app-blog


Changelog
---------
### 0.4
* New class : class.Api.php to manage a JSON Web Api
* New class : class.Session.php to manage a secure session

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
