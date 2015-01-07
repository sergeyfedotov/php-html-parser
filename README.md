Experimental HTML Parser
===============

Example
---------------
```php
<?php
use Fsv\XModel\Filter;
use Fsv\XModel\Mapping;
use Fsv\XModel\Transformer;

/**
 * @Mapping\Root
 * @Filter\CssSelector(".repository-content")
 */
class Repository
{
    /**
     * @var array
     * @Mapping\Element(className="Issue", hasMany=true)
     * @Filter\CssSelector(".table-list-issues")
     */
    public $issues = [];
}

/**
 * @Mapping\Root
 * @Filter\CssSelector(".table-list-item")
 */
class Issue
{
    /**
     * @var string
     * @Mapping\Element
     * @Filter\CssSelector(".issue-title > a")
     * @Transformer\Trim
     */
    public $title;

    /**
     * @var string
     * @Mapping\Attribute("href")
     * @Filter\CssSelector(".issue-title > a")
     */
    public $url;

    /**
     * @var Author
     * @Mapping\Element(className="Author")
     * @Filter\CssSelector(".issue-meta-section > a")
     */
    public $author;

    /**
     * @var \DateTime
     * @Mapping\Attribute("datetime")
     * @Filter\CssSelector(".issue-meta-section > time")
     * @Transformer\DateTime
     */
    public $createdAt;

    /**
     * @var float
     * @Mapping\Element
     * @Filter\CssSelector(".issue-comments")
     * @Transformer\Numeric
     */
    public $commentCount;
}

/**
 * @Mapping\Root
 * @Filter\XPath(".")
 */
class Author
{
    /**
     * @var string
     * @Mapping\Element
     * @Filter\XPath("text()")
     */
    public $name;
}
```

```php
<?php
// test.php
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Fsv\XModel\Reader;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/model.php';

AnnotationRegistry::registerAutoloadNamespaces([
    'Fsv\XModel\Mapping'        => __DIR__ . '/../src',
    'Fsv\XModel\Filter'         => __DIR__ . '/../src',
    'Fsv\XModel\Transformer'    => __DIR__ . '/../src'
]);

$reader = new Reader(new AnnotationReader());
print_r($reader->readHtmlFile('Repository', 'https://github.com/symfony/symfony/issues')[0]);
```

```
$ php test.php
Repository Object
(
    [issues] => Array
        (
            [0] => Issue Object
                (
                    [title] => Form using Validator Constraint File - Max File Size not validating correctly, element returns NULL
                    [url] => /symfony/symfony/issues/13291
                    [author] => Author Object
                        (
                            [name] => humanoyd
                        )

                    [createdAt] => DateTime Object
                        (
                            [date] => 2015-01-06 18:58:58.000000
                            [timezone_type] => 2
                            [timezone] => Z
                        )

                    [commentCount] => 2
                )

            [1] => Issue Object
                (
                    [title] => Symfony crash on automatic PHP enviroment switching from HHVM to PHP5-FPM.
                    [url] => /symfony/symfony/issues/13288
                    [author] => Author Object
                        (
                            [name] => damiencal
                        )

                    [createdAt] => DateTime Object
                        (
                            [date] => 2015-01-06 15:43:07.000000
                            [timezone_type] => 2
                            [timezone] => Z
                        )

                    [commentCount] => 1
                )
...
```
