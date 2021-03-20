# laravel-json-api/hashids

Encode model keys to JSON:API resource ids using [vinkla/hashids](https://github.com/vinkla/laravel-hashids). This is a
plugin for [Laravel JSON:API](https://laraveljsonapi.io).

## Installation

Install using [Composer](https://getcomposer.org)

```bash
composer require laravel-json-api/hashids
```

This will also install [vinkla/hashids](https://github.com/vinkla/laravel-hashids). After installing, you will need to
publish the configuration for that package:

```bash
php artisan vendor:publish --provider="Vinkla\Hashids\HashidsServiceProvider"
```

Refer to the [README](https://github.com/vinkla/laravel-hashids/blob/master/README.md) in that package for more
configuration information.

## License

Laravel JSON:API is open-sourced software licensed under the [Apache 2.0 License](./LICENSE).
