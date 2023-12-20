# PHP ePub generator

PHPePub allows a php script to generate ePub Electronic books on the fly, and send them to the user as downloads.

PHPePub support most of the ePub 2.01 specification, and enough of the new ePub3 specification to make valid ePub 3 books as well.
The original project was: https://github.com/Grandt/PHPePub
Then the project was forkend into: https://github.com/wallabag/PHPePub
And then we forked the project to update the codebase with a modern version of PHP. To do that we used RectorPHP to update automatically the codebase to PHP 8.1.
Then we update the style according to PER standards https://www.php-fig.org/per/coding-style/.



## Installation

```
composer require hi-folks/phpepub
```


## Using PHPePub

Finally, you include the `autoload.php` file in the new `vendor` directory.
```php
<?php
    require 'vendor/autoload.php';
    .
    .
    .
```

## TODO:
The goal is to encompass the majority of the features in the ePub 2.0 and 3.0 specifications, except the Daisy-type files.
* Add better handling of Reference structures.
* Improve handling of media types and linked files.
* A/V content is allowed, but not recommended, and MUST have a fallback chain ending in a valid file. If no such chain is provided, the content should not be added.
* Documentation, no one reads it, but everyone complains if it is missing.
* Better examples to fully cover the capabilities of the EPub classes.
* more TODO's.
