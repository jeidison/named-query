# Instalação

Instalação via composer

```bash
$ composer require jeidison/named-query
```

```bash
$ php artisan vendor:publish
```

Escolha a opção: NamedQuery

* Adicione suas SQL's no arquivo "database/queries/named-query.xml"

Executando uma SQL:

```bash
NamedQuery::executeNamedQuery('nomedaquery',['par1' => $par1, 'par2' => $par2]);
```

