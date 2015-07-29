bee4/robots.txt v1.0.0
======================

[![Build Status](https://img.shields.io/travis/bee4/robots.txt.svg?style=flat-square)](https://travis-ci.org/bee4/robots.txt)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/bee4/robots.txt.svg?style=flat-square)](https://scrutinizer-ci.com/g/bee4/robots.txt/?branch=develop)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/bee4/robots.txt.svg?style=flat-square)](https://scrutinizer-ci.com/g/bee4/robots.txt/)
[![SensiolabInsight](https://img.shields.io/sensiolabs/i/eeb48794-6ffb-4c54-8867-56c077d77008.svg?style=flat-square)](https://insight.sensiolabs.com/projects/eeb48794-6ffb-4c54-8867-56c077d77008)

[![License](https://img.shields.io/packagist/l/bee4/robots.txt.svg?style=flat-square)](https://packagist.org/packages/bee4/robots.txt)

This library allow to parse a Robots.txt file and then check for URL status according to defined rules.


Installing
----------
[![Latest Stable Version](https://img.shields.io/packagist/v/bee4/robots.txt.svg?style=flat-square)](https://packagist.org/packages/bee4/robots.txt)
[![Total Downloads](https://img.shields.io/packagist/dm/bee4/robots.txt.svg?style=flat-square)](https://packagist.org/packages/bee4/robots.txt)

This project can be installed using Composer. Add the following to your composer.json:

```JSON
{
    "require": {
        "bee4/robots.txt": "~1.0"
    }
}
```

or run this command:

```Shell
composer require bee4/robots.txt:~1.0
```

Example
-------

```PHP
<?php
$sample_robots_txt = trim("

User-agent: *
Allow: /

User-agent: google-bot
Disallow: /forbidden-directory

");

$parser = new \Bee4\RobotsTxt\Parser($sample_robots_txt);
$rules = $parser->parse();

// You can use match a url against parsed rules...
$rules->match('Google-Bot v01', '/'); // true
$rules->match('Google-Bot v01', '/forbidden-directory'); // false

// ...or get the applicable rule for a user-agent and match  
$rule = $rules->get('*');
$result = $rule->match('/'); // true                                                                                                                       
$result = $rule->match('/forbidden-directory'); // true
```
