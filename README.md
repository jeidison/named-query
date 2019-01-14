# Instalação

# Instalação via composer

```bash
$ composer require jeidison/named-query
```

# Publicando as configurações

```bash
$ php artisan vendor:publish --provider="Jeidison\NamedQuery\NamedQueryServiceProvider"
```
Esse comando publicará a configuração ``` config/named-query.php ``` do pacote para ser customizado.

```php
return [
    'path-sql'  => database_path('named-query/queries'),
    'type'      => Jeidison\NamedQuery\TypeFile::XML,
    'type-bind' => Jeidison\NamedQuery\TypeBind::TWO_POINTS,
];
```

# Adicionando uma SQL em arquivo XML

```xml
<query name="find_nfe_by_key">
    SELECT *
        FROM nfe
        WHERE numero          = :numero
            AND cnpj_emitente = :cnpj_emitente
            AND serie         = :serie
            AND tpamb         = :tpamb
            AND mod           = :mod
</query>
```
# Adicionando uma SQL em arquivo PHP

```php
CONST find_nfe_by_key = "
SELECT *
FROM nfe
WHERE numero        = :numero
  AND cnpj_emitente = :cnpj_emitente
  AND serie         = :serie
  AND tpamb         = :tpamb
  AND mod           = :mod
";
```

# Atenção
* Se você configurar o type no arquivo  ``` config/named-query.php ``` como ```Jeidison\NamedQuery\TypeFile::XML``` todas SQLs devem estar em arquivo XML. 
* Se você configurar o type no arquivo  ``` config/named-query.php ``` como ```Jeidison\NamedQuery\TypeFile::PHP``` todas SQLs devem estar em arquivo PHP.

# Executando uma SQL

```php
NamedQuery::executeNamedQuery('nfe/named-querys', 'find_nfe_by_key', [
    'numero'        => $numero,
    'cnpj_emitente' => $cnpjEmitente,
    'serie'         => $serie,
    'tpamb'         => $tpAmb,
    'mod'           => $mod,
]);
```

Desta forma a SQL será executada e o resultado da consulta será do tipo stdClass. Caso queira um tipo diferente, execute da sequinte forma:


# Executando uma SQL com ResultClass

```php
NamedQuery::executeNamedQuery('nfe/named-querys', 'find_nfe_by_key', [
    'numero'        => $numero,
    'cnpj_emitente' => $cnpjEmitente,
    'serie'         => $serie,
    'tpamb'         => $tpAmb,
    'mod'           => $mod,
], NFe::class);
```

# Debugando a SQL construída 

```php
NamedQuery::executeNamedQuery('nfe/named-querys', 'find_nfe_by_key', [
    'numero'        => $numero,
    'cnpj_emitente' => $cnpjEmitente,
    'serie'         => $serie,
    'tpamb'         => $tpAmb,
    'mod'           => $mod,
], NFe::class, true);
```
