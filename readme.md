# DoctrineTracabilityBundle

This bundle allows to store changes made to a doctrine entity.

Actions enabled are :

##### 1)  POST
##### 2)  PUT
##### 3)  DELETE



### 1. Installation

To install the bundle:

    $ composer require DctT\TracabilityBundle

### 2. Generate tracability entity :

Generate the tracability entity in where we store the history system :

    $  php bin/console doctrine:schema:update --force

The new tracability table will be like the following.

##### `doctrine`

| id | user     | resource  | action | done_at             |
|----|----------|-----------|--------|---------------------|
| 1  | John Doe | Product-5 | DELETE | 2020-10-31 09:30:46 |









