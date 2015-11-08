# Alt Three Emoji

An emoji parser for Laravel 5.


## Installation

Either [PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+ are required.

To get the latest version of Alt Three Emoji, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require alt-three/emoji
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "alt-three/emoji": "^2.0"
    }
}
```

Once Alt Three Emoji is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'AltThree\Emoji\EmojiServiceProvider'`


## Security

If you discover a security vulnerability within this package, please e-mail us at support@alt-three.com. All security vulnerabilities will be promptly addressed.


## License

Alt Three Emoji is licensed under [The MIT License (MIT)](LICENSE).
