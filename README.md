# Instalação

# Instalação via composer

```bash
$ composer require jeidison/named-query
```

# Publicando as configurações

```bash
$ php artisan vendor:publish --provider="Jeidison\NamedQuery\Providers\NamedQueryServiceProvider"
```
Esse comando publicará a configuração ``` config/named-query.php ``` do pacote para ser customizado.

```php
return [
    'path-sql'  => database_path('named-query/queries'),
    'type'      => Jeidison\NamedQuery\Enums\TypeFile::XML,
    'type-bind' => Jeidison\NamedQuery\Enums\TypeBind::TWO_POINTS,
];
```

# Adicionando uma SQL em arquivo XML

```xml
<query name="find_tab1">
    SELECT * FROM TAB1 WHERE PAR1 = ?1
</query>
```
# Adicionando uma SQL em arquivo PHP

```php
CONST find_tab1 = "
SELECT * FROM TAB1 WHERE PAR1 = :PAR1
";
```

# Atenção
* Se você configurar o type no arquivo  ``` config/named-query.php ``` como ```Jeidison\NamedQuery\Enums\TypeFile::XML``` todas SQLs devem estar em arquivo XML. 
* Se você configurar o type no arquivo  ``` config/named-query.php ``` como ```Jeidison\NamedQuery\Enums\TypeFile::PHP``` todas SQLs devem estar em arquivo PHP.

# Executando uma SQL

```php
NamedQuery::executeNamedQuery('find_tab1', 'nfe/named-querys', [
    'PAR1' => $numero,
]);
```

Desta forma a SQL será executada e o resultado da consulta será do tipo stdClass. Caso queira um tipo diferente, execute da sequinte forma:


# Executando uma SQL com ResultClass

```php
NamedQuery::executeNamedQuery('find_nfe_by_key', 'nfe/named-querys', [
    'numero'        => $numero,
    'cnpj_emitente' => $cnpjEmitente,
    'serie'         => $serie,
    'tpamb'         => $tpAmb,
    'mod'           => $mod,
], NFe::class);
```

# Debugando a SQL construída 

```php
NamedQuery::executeNamedQuery('find_nfe_by_key', 'nfe/named-querys', [
    'numero'        => $numero,
    'cnpj_emitente' => $cnpjEmitente,
    'serie'         => $serie,
    'tpamb'         => $tpAmb,
    'mod'           => $mod,
], NFe::class, true);
```
