<?php
namespace Fsv\XModel;

use DOMDocument;
use DOMNode;
use DOMXPath;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Fsv\XModel\Mapping\Children;
use Fsv\XModel\Mapping\Node;
use Fsv\XModel\Mapping\Root;
use Fsv\XModel\Mapping\XPath;
use LibXMLError;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * Class Reader
 * @package Fsv\XModel
 */
class Reader
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var LibXMLError[]
     */
    private $errors;

    /**
     * @param Model $model
     * @param AnnotationReader $annotationReader
     */
    public function __construct(Model $model, AnnotationReader $annotationReader = null)
    {
        $this->model = $model;
        $this->annotationReader = $annotationReader;

        if (null !== $annotationReader) {
            $this->readModelAnnotation($model);
        }
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return AnnotationReader
     */
    public function getAnnotationReader()
    {
        return $this->annotationReader;
    }

    /**
     * @param $model
     */
    public function readModelAnnotation(Model $model)
    {
        $reflectionClass = new ReflectionClass($model->getClassName());

        foreach ($this->annotationReader->getClassAnnotations($reflectionClass) as $annotation) {
            if ($annotation instanceof Root) {
                $model->setRoot($annotation);
            } else if ($annotation instanceof FilterInterface) {
                $model->addFilter($annotation);
            }
        }

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $property = new Property($reflectionProperty->getName());

            foreach ($this->annotationReader->getPropertyAnnotations($reflectionProperty) as $annotation) {
                if ($annotation instanceof Node) {
                    $property->setNode($annotation);

                    if (null === $annotation->getName()) {
                        $annotation->setName($reflectionProperty->getName());
                    }
                } else if ($annotation instanceof Children) {
                    $property->setChildren($annotation);

                    $childModel = new Model($annotation->getClassName());
                    $property->setModel($childModel);
                    $this->readModelAnnotation($childModel);
                } else if ($annotation instanceof XPath) {
                    $property->setXPath($annotation);
                } else if ($annotation instanceof TransformerInterface) {
                    $property->addTranformer($annotation);
                } else if ($annotation instanceof FilterInterface) {
                    $property->addFilter($annotation);
                }
            }

            $model->addProperty($property);
        }
    }

    /**
     * @param string $path
     * @return object[]
     */
    public function readHtmlFile($path)
    {
        $useInternalErrors = libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $document->loadHTMLFile($path);
        $this->errors = libxml_get_errors();
        libxml_clear_errors();

        libxml_use_internal_errors($useInternalErrors);

        return $this->createObjects($this->model, new DOMXpath($document), null);
    }

    /**
     * @return LibXMLError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    private function createObjects(Model $model, DOMXPath $xpath, DOMNode $contextNode = null)
    {
        $className = $model->getClassName();
        $accessor = new PropertyAccessor();
        $objects = [];

        foreach ($model->getFilterChain()->apply($xpath, [$contextNode]) as $rootNode) {
            $object = new $className();

            foreach ($model->getProperties() as $property) {
                $propertyNode = $property->getFilterChain()->apply($xpath, [$rootNode])[0];
                $propertyPath = new PropertyPath($property->getName());

                if (null !== ($children = $property->getChildren())) {
                    $accessor->setValue(
                        $object,
                        $propertyPath,
                        $this->createObjects($property->getModel(), $xpath, $propertyNode)
                    );
                } else {
                    if (null !== $propertyNode && $accessor->isWritable($object, $propertyPath)) {
                        $accessor->setValue(
                            $object,
                            $propertyPath,
                            $property->getTransformerChain()->transform($propertyNode->nodeValue)
                        );
                    }
                }
            }

            $objects[] = $object;
        }

        return $objects;
    }
}
