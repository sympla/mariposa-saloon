# sympla/mariposa-saloon

> Mariposa Saloon is the local saloon and brothel in Sweetwater, Westworld.
It is mainly run by madam Maeve Millay, with her assistant and fellow 
courtesan, Clementine Pennyfeather. 

This library is a middleware that integrates the [mesa-gold-bar](https://github.com/sympla/mesa-gold-bar)
component into Slim, Laravel and other frameworks.

## Installations

Install the package using composer:

    $ composer require sympla/mariposa-saloon 2.0
    
That's it.

## Usage

Add the middleware to the application:

```php
<?php
#middleware.php
$app->add(new \Sympla\RemoteAuthenticationMiddleware\OAuth2RemoteAuthenticationMiddleware(
    new GuzzleHttp\Client,
    $app->getContainer(),
    [
        // This protects everything under /api. Use a list of regexes here.
        'protected' => ['/api\//'], // 
        // Loads the remote authentication server from the environment
        'authentication_server' => getenv('AUTHENTICATION_SERVER') 
    ])
);
```

Now, in the routes defined in the `protected` key, you can invoke
the `user` key, from the container:

```php
<?php
#routes.php
$app->post('/api/get', function (
    \Psr\Http\Message\RequestInterface $request,
    \Psr\Http\Message\ResponseInterface $response,
    $args
) {

    $user = $this->get('user');
    
    //...
});
```

## Author

Pedro Cordeiro <pedro.cordeiro@sympla.com.br>