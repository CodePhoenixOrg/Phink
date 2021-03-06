	 ____  _     _       _
	|  _ \| |__ (_)_ __ | | __  ___  _ __ __ _
	| |_) | '_ \| | '_ \| |/ / / _ \| '__/ _` |
	|  __/| | | | | | | |   < | (_) | | | (_| |
	|_|   |_| |_|_|_| |_|_|\_(_)___/|_|  \__, |
	                                     |___/

## What is Phink ?
Phink Framework is a component oriented PHP framework. It allows you to build sites like ASP.Net webform does with its user controls and master pages all in benefiting of an MVC architecture. It integrates a client-side layer in shape of a JavaScript API that looks very much like the PHP classes. This coupled solution is aiming to easily build Ajax Applications. A simple JavaScript starter statement can launch the entire application.

## Requirements
Apache (no other server is supported right now)
PHP 7.4  
PHP extensions : SQLite, CURL, ZIP, other ones should be setup by default.

## Installation
Phink is registered in the Packagist library so it's available via composer.

The simplest method is to install it as a standalone application like this:

	composer create-project phink/phink myproject "dev-master"

## Usage
Let's say myproject is a subdirectory of your local Apache server Document root.

In your browser, go to: 
	https://localhost/myproject/src/web/setup/

All necessary files will set up automatically and after a few seconds you'll be in front of the welcome page.

## Sample javascript bootstrap sample.js

	var sample = null;
	Phink.DOM.ready(function () {
		
		sample = TWebApplication.create('sample.com');
		sample.main = sample.createView('main');
		
		var sampleMain = sample.createController(sample.main, 'sample.main')
		.actions({
			goHome : function () {
				sampleMain.getSimpleView('master.html', function(data) {
					$(document.body).html(data.view);
					sampleMain.getSimpleView('home.html', function(data) {
						$('#homeContent').html(data.view);
					});
				});        
			}
		})
		.onload(function () {
			sampleMain = this;
			this.goHome();
		});
	});

## Sample HTML page 

	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta http-equiv="content-type" content="text/html; charset=UTF-8">
			<meta charset="utf-8">
			<title>Sample</title>
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			<link href="css/_3rdparty.css" rel="stylesheet">
		</head>
		<body data-twttr-rendered="true" data-spy="scroll" >
			<div id='body'></div>
			<script type="text/javascript" src="phink.js"
				data-depends="js/_3rd_party.js"
				data-sources="js/sample.js">
		        </script>
		</body>
	</html>
