# DoctrineTracabilityBundle

This bundle allows to store changes made to a doctrine entity.

Actions enabled are :

##### 1)  POST
##### 2)  PUT
##### 3)  DELETE


### 1. Installation

Add this to your `composer.json`

    "minimum-stability": "dev"
And then install the bundle with `composer`:

    $ composer require ziiko10/doctrine-tracability-bundle

### 2. Enable the bundle

To enable the bundle add this line to `bundles.php`:

```php

\DctT\TracabilityBundle\DoctrineTracabilityBundle::class => ['all' => true],
```

### 3. Generate tracability entity :

Generate the tracability entity in where we store the history system :

    $  php bin/console doctrine:schema:update --force

The new tracability table will be like the following.

##### `doctrine`

| id | user     | resource  | action | done_at             |
|----|----------|-----------|--------|---------------------|
| 1  | John Doe | Product-5 | DELETE | 2020-10-31 09:30:46 |



### 4. Configure the bundle

Let's say you have `Post` entity and you want to trace actions done on this entity.

First you need to decorate the entity with **@Tracable** annotation like bellow.

```php
<?php
namespace YourBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use DctT\TracabilityBundle\Annotation\Tracable;

/**
 * Post
 *
 * @ORM\Table(name="Post")
 * @ORM\Entity(repositoryClass="YourBundle\Repository\PostRepository")
 * @Tracable(resourceName="Post")
 */
class Post
{
    /**  
     * @var string 
     * @ORM\Id 
     * @ORM\Column(name="id", type="string", length=255) 
     */
    private $id;
}
```

- `resourceName` : a simple name used to store the resource

Then you need to create new yaml file in **config/packages/tracability.yaml**:

```yaml
doctrine_tracability:
  user_identifier: firstName
  actions: 
    persist: true
    update: true
    remove: false
```

##### `user_identifier` : 
The user identifier field you want to store

##### `actions` : 
Actions you want to enable












