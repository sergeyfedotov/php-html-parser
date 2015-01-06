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
     * @Mapping\Element(className="Issue")
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
     * @var string
     * @Mapping\Element
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
                    [title] => [Security][Bug] urlRedirectAction suddenly triggers security (BC break?)
                    [url] => /symfony/symfony/issues/13277
                    [author] => iltar
                    [createdAt] => DateTime Object
                        (
                            [date] => 2015-01-06 09:40:47.000000
                            [timezone_type] => 2
                            [timezone] => Z
                        )

                    [commentCount] => 0
                )

            [1] => Issue Object
                (
                    [title] => [Security] login_check route throws exception on php 5.4.4: Parent session handler is not open
                    [url] => /symfony/symfony/issues/13269
                    [author] => derrabus
                    [createdAt] => DateTime Object
                        (
                            [date] => 2015-01-05 16:31:58.000000
                            [timezone_type] => 2
                            [timezone] => Z
                        )

                    [commentCount] => 3
                )
...
```
