<?php
namespace Fsv\XModel;

use DOMDocument;
use DOMNode;
use DOMXPath;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Fsv\XModel\Mapping\Attribute;
use Fsv\XModel\Mapping\Element;
use Fsv\XModel\Mapping\ModelMetadata;
use Fsv\XModel\Mapping\PropertyMetadata;
use Fsv\XModel\Mapping\Root;
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
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var LibXMLError[]
     */
    private $errors;

    /**
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader = null)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @return AnnotationReader
     */
    public function getAnnotationReader()
    {
        return $this->annotationReader;
    }

    /**
     * @param ModelMetadata $modelMetadata
     */
    public function loadModelMetadata(ModelMetadata $modelMetadata)
    {
        $reflectionClass = new ReflectionClass($modelMetadata->getClassName());

        foreach ($this->annotationReader->getClassAnnotations($reflectionClass) as $annotation) {
            if ($annotation instanceof Root) {
                $modelMetadata->setNode($annotation);
            } else if ($annotation instanceof FilterInterface) {
                $modelMetadata->addFilter($annotation);
            }
        }

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyMetadata = new PropertyMetadata($reflectionProperty->getName());

            foreach ($this->annotationReader->getPropertyAnnotations($reflectionProperty) as $annotation) {
                if ($annotation instanceof Element
                    || $annotation instanceof Attribute
                ) {
                    $propertyMetadata->setNode($annotation);

                    if (null === $annotation->name) {
                        $annotation->name = $reflectionProperty->getName();
                    }

                    if ($annotation instanceof Element
                        && null !== $annotation->className
                    ) {
                        $this->loadModelMetadata($annotation->getAssociatedModelMetadata());
                    }
                } else if ($annotation instanceof TransformerInterface) {
                    $propertyMetadata->addTransformer($annotation);
                } else if ($annotation instanceof FilterInterface) {
                    $propertyMetadata->addFilter($annotation);
                }
            }

            if (null !== $propertyMetadata->getNode()) {
                $modelMetadata->addProperty($propertyMetadata);
            }
        }
    }

    /**
     * @param string $className
     * @param string $path
     * @return object[]
     */
    public function readHtmlFile($className, $path)
    {
        $modelMetadata = new ModelMetadata($className);
        $this->loadModelMetadata($modelMetadata);

        $useInternalErrors = libxml_use_internal_errors(true);
        $document = new DOMDocument();
        $document->loadHTMLFile($path);
        $this->errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($useInternalErrors);

        return $this->createObjects($modelMetadata, new DOMXpath($document), null);
    }

    /**
     * @return LibXMLError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param ModelMetadata $modelMetadata
     * @param DOMXPath $xpath
     * @param DOMNode $contextNode
     * @return array
     */
    private function createObjects(ModelMetadata $modelMetadata, DOMXPath $xpath, DOMNode $contextNode = null)
    {
        $className = $modelMetadata->getClassName();
        $accessor = new PropertyAccessor();
        $objects = [];

        foreach ($modelMetadata->getFilterChain()->apply($xpath, [$contextNode]) as $rootNode) {
            $object = new $className();

            foreach ($modelMetadata->getProperties() as $propertyMetadata) {
                $propertyNode = $propertyMetadata->getFilterChain()->apply($xpath, [$rootNode])[0];
                $mappedNode = $propertyMetadata->getNode();

                if ($mappedNode instanceof Attribute) {
                    $propertyNode = $propertyNode->attributes[$propertyMetadata->getName()];
                }

                $propertyPath = new PropertyPath($propertyMetadata->getName());

                if ($mappedNode instanceof Element && null !== $mappedNode->className) {
                    $associatedObjects = $this->createObjects(
                        $mappedNode->getAssociatedModelMetadata(),
                        $xpath,
                        $propertyNode
                    );

                    if (isset($associatedObjects[0])) {
                        $accessor->setValue(
                            $object,
                            $propertyPath,
                            $mappedNode->hasMany ? $associatedObjects : $associatedObjects[0]
                        );
                    }
                } else {
                    if (null !== $propertyNode && $accessor->isWritable($object, $propertyPath)) {
                        $accessor->setValue(
                            $object,
                            $propertyPath,
                            $propertyMetadata->getTransformerChain()->transform($propertyNode->nodeValue)
                        );
                    }
                }
            }

            $objects[] = $object;
        }

        return $objects;
    }
}
