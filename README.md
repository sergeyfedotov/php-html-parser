Experimental HTML Parser
===============

Example
---------------
```php
<?php
// model.php
use Fsv\XModel\Filter;
use Fsv\XModel\Mapping as Xml;
use Fsv\XModel\Transformer;

/**
 * @Xml\Root
 * @Filter\CssSelector(".repository-content")
 */
class Repository
{
    /**
     * @var array
     * @Xml\Node
     * @Xml\Children("Issue")
     * @Filter\CssSelector(".table-list-issues")
     */
    public $issues = [];
}

/**
 * @Xml\Root
 * @Filter\CssSelector(".table-list-item")
 */
class Issue
{
    /**
     * @var string
     * @Xml\Node
     * @Filter\CssSelector(".issue-title > a")
     * @Transformer\Trim
     */
    public $title;

    /**
     * @var string
     * @Xml\Node
     * @Filter\CssSelector(".issue-title > a")
     * @Filter\XPath("@href")
     */
    public $url;

    /**
     * @var string
     * @Xml\Node
     * @Filter\CssSelector(".issue-meta-section > a")
     */
    public $author;

    /**
     * @var \DateTime
     * @Xml\Node
     * @Filter\CssSelector(".issue-meta-section > time")
     * @Filter\XPath("@datetime")
     * @Transformer\DateTime
     */
    public $createdAt;

    /**
     * @var float
     * @Xml\Node
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
use Fsv\XModel\Model;
use Fsv\XModel\Reader;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/model.php';

AnnotationRegistry::registerAutoloadNamespaces([
    'Fsv\XModel\Mapping'        => __DIR__ . '/../src',
    'Fsv\XModel\Filter'         => __DIR__ . '/../src',
    'Fsv\XModel\Transformer'    => __DIR__ . '/../src'
]);

$model = new Model('Repository');
$reader = new Reader($model, new AnnotationReader());
print_r($reader->readHtmlFile('https://github.com/symfony/symfony/issues')[0]);
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
