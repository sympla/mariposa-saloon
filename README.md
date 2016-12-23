# OAuth2 Remote Authentication Middleware

Essa biblioteca funciona como um middleware para o microframework slim.
Tem por objetivo auxiliar com a identificação de usuários através de seus access
tokens.

## Instalação

Certifique-se de que `packagist.com` esteja adicionado à lista de repositórios
do seu `composer.json`:

```json
    {
        "repositories": [
            {"type": "composer", "url": "https://repo.packagist.com/sympla/"},
            {"packagist": false}
        ]
    }
```

Então, instale o pacote:

    $ composer require sympla/oauth-remote-authentication-middlware ~1.0
    
É isso.

## Utilização

Adicione o middleware à aplicação:

```php
<?php
#middleware.php
$app->add(new \Sympla\RemoteAuthenticationMiddleware\OAuth2RemoteAuthenticationMiddleware($app->getContainer(), [
    // This protects everything under /api. Use a list of regexes here.
    'protected' => ['/api\//'], // 
    // Loads the remote authentication server from the environment
    'authentication_server' => getenv('AUTHENTICATION_SERVER') 
]));

```

Pronto. Agora, nas rotas definidas na chave `protected`, você pode invocar 
o valor de `user`, no container:

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
}
```

É isso :)

## Autor

Pedro Cordeiro <pedro.cordeiro@sympla.com.br>