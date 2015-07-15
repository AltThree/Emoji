# Alt Three Emoji

An emoji parser for Laravel 5.


## Installation

[PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Alt Three Emoji, simply add the following line to the require block of your `composer.json` file:

```
"alt-three/emoji": "~1.0"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Alt Three Emoji is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'AltThree\Emoji\EmojiServiceProvider'`


## License

Alt Three Emoji is licensed under [The MIT License (MIT)](LICENSE).
