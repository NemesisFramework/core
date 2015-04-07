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

**Plugin** : DEPRECATED

**URL** : get headers received, hash and output URLs, depends to Loader

**Router** : create routes, depends to Loader, URL

**Api** : manage a simple JSON Web Api with a class controller, depends to Loader, URL, Router

**MVC** : add all components to build a MVC app like a view builder, depends to Loader, Hook, URL, Routes

**App** : manage a web app built on a MVC pattern with a class controller, depends to Loader, Hook, URL, Routes, MVC



### Functions ###

**String parser** : strip_accents, strip_specialchars, beautify, minimize, excerpt, is_email, is_phone_fr, is_date, datetime, sanitize_output

**File manager / Shorcuts** : getperms, filename, extension, upload, download

**CURL** : url_get_contents

**key** : Key_Generator


## Required PHP version and modules ##

- PHP v5.3 or higher installed on your server or web hosting

- htaccess (for Apache) and url_rewriting PHP modules


## Installation ##

### Composer ###
Nemesis-Framework is now on packagist, so it can be required as a dependency with Composer : 

[https://packagist.org/packages/kimihub/nemesis-framework](https://packagist.org/packages/kimihub/nemesis-framework)


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
		server.document-root = "/PathToServerRoot/"
		accesslog.filename   = "/PathToLogs/access.log"
	 	url.rewrite = (
			"^/(.+)/?$" => "/index.php/$1"
		)
	}

### *index.php* instructions ###
Require bootstraper

	require_once 'nemesis.php';
    
To log php errors

    require_once 'nemesis.dev.php';
        

If the core functions are required

	core_functions();

If the autoloader is required

	core_autoloader();

Router initialization

	$loader = Loader::getInstance();
	$loader->initClass('Router');

Example of a web app initialization

	$blogApp = App::getInstance('blog');
	$blogApp->run();
	echo $blogApp;

### More examples ###
For more examples, check the others repositories prefixed with "nemesis-" 

- nemesis-api-newsletter : [https://github.com/kimihub/nemesis-api-newsletter](https://github.com/kimihub/nemesis-api-newsletter)

- nemesis-app-blog : [https://github.com/kimihub/nemesis-app-blog](https://github.com/kimihub/nemesis-app-blog)


Changelog
---------
### 0.6
* Move the bootstrapper core/bootstrap.php to ./nemesis.php for more simplicity with Composer
* Move core/errors.log to ./errors.log 
* Add ./nemesis.dev.php to write logs in the errors file when included
* Add NEMESIS_PROCESS_PATH to ./nemesis.php and core/class.App.php
* Change behaviour of App system, an app can now be in the root server directory
* Deprecated App::setAsDefault() and App::$url
* Deprecated class.Plugin.php
* Define composer.json

### 0.5 
* New function in functions.php : key_generator($length=8)
* Removed hash/token generator and new secure ($_SESSION[$sessionName] = $expirationDate)
* Add Cross-Origin Resource Sharing (CORS) headers in class.Api.php with Api::CORS()

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
 