# HTTP Wrapper

This library provides a wrapper for the 
[PSR-7](http://www.php-fig.org/psr/psr-7/)'s `ResponseInterface`.
 
You have to use it with a library/framework that offers
a [PSR-7](http://www.php-fig.org/psr/psr-7/) implementation,
such as [Slim 3](http://www.slimframework.com/), 
[Guzzle](http://guzzlephp.org/), 
[Aura](https://github.com/auraphp/Aura.Router/tree/3.x#aurarouter) or 
[Zend](https://github.com/zendframework/zend-diactoros).

We use [semantic versioning](http://semver.org/). See our [releases](https://github.com/thiagodp/httpwrapper/releases).

Classes:

* [phputil\HttpResponseWrapper](https://github.com/thiagodp/httpwrapper/blob/master/lib/HttpResponseWrapper.php)

Dependencies (installed automatically by `composer`):

* [phputil/http](https://github.com/thiagodp/http)
* [phputil/json](https://github.com/thiagodp/json)

### Installation

```command
composer require phputil/httpwrapper
```

### Example 1

Using with Slim 3:

```php
<?php
require 'vendor/autoload.php';

use \phputil\HttpResponseWrapper;
use \Slim\App;

$app = new App();
$hrw = new HttpResponseWrapper();

$app->get( '/names', function ( $request, $response, $args ) use ( $hrw ) {

	$names = array( 'Suzan', 'Mary', 'Mike', 'Bob' );

	// Will return HTTP 200 with the array as JSON encoded with UTF-8
	return $hrw->with( $response )
		->withStatusOk()
		->asJsonUtf8( $names ) // Any var type accepted
		->end()
		;
} );

$app->get( '/bad', function ( $request, $response, $args ) use ( $hrw ) {
	// Will return HTTP 400
	return $hrw->with( $response )->withStatusBadRequest->end();
} );

$app->get( '/i-am-just-curious', function ( $request, $response, $args ) use ( $hrw ) {
	// Will return HTTP 403 (Forbidden)
	return $hrw->with( $response )->withStatusForbidden->end();
} );

?>
```

### Example 2

Also with Slim 3:

```php
<?php
require 'vendor/autoload.php';

use \phputil\HttpResponseWrapper;
use \Slim\App;

$app = new App();
$hrw = new HttpResponseWrapper();

$app->get( '/names', function ( $request, $response, $args ) use ( $hrw ) {

	$names = array( 'Suzan', 'Mary', 'Mike', 'Bob' );

	// Helper method to return HTTP 200 with a JSON content encoded with UTF-8.
	return $hrw->with( $response )->ok( $names );
} );

$app->get( '/bad', function ( $request, $response, $args ) use ( $hrw ) {
	// Helper method to return HTTP 400 with a JSON content encoded with UTF-8.
	return $hrw->with( $response )->bad( array( 'Something bad happened' ) );
} );

$app->get( '/none', function ( $request, $response, $args ) use ( $hrw ) {
	// Helper method to return HTTP 204.
	return $hrw->with( $response )->noContent();
} );

?>
```
