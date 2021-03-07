<?php declare(strict_types=1);

namespace App\SuperMetrics;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerBuilder
{
    public static function build(): SerializerInterface
    {
        $classMetadataFactory = new ClassMetadataFactory(new YamlFileLoader(
            dirname(__DIR__, 2) . '/config/mapping/supermetrics.yaml'
        ));

        return new Serializer(
            [
                new GetSetMethodNormalizer(
                    $classMetadataFactory,
                    new MetadataAwareNameConverter($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter())
                ),
            ],
            [new JsonEncoder()]
        );
    }
}
