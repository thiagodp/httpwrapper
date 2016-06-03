# HTTP Wrapper

This library provides a wrapper for the 
[PSR-7](http://www.php-fig.org/psr/psr-7/)'s `ResponseInterface`.
 
You have to use it with a library/framework that offers
a [PSR-7](http://www.php-fig.org/psr/psr-7/) implementation,
such as [Slim 3](http://www.slimframework.com/), 
[Guzzle](http://guzzlephp.org/), 
[Aura](https://github.com/auraphp/Aura.Router/tree/3.x#aurarouter) or 
[Zend](https://github.com/zendframework/zend-diactoros).

We use [Semantic Versioning](http://semver.org/). See our [releases](https://github.com/thiagodp/httpwrapper/releases).

Classes:

* [phputil\HttpResponseWrapper](https://github.com/thiagodp/httpwrapper/blob/master/lib/HttpResponseWrapper.php)

Dependencies (installed automatically by `composer`):

* [phputil/http](https://github.com/thiagodp/http)
* [phputil/json](https://github.com/thiagodp/json)

### Installation

```command
composer require phputil/httpwrapper
```
