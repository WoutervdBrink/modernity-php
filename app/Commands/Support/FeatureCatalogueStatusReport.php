<?php

namespace App\Commands\Support;

readonly class FeatureCatalogueStatusReport
{
    public function __construct(
        /** @var class-string $class */
        public string $class,
        public FeatureCatalogueStatus $status,
        /** @var class-string[]|null $superclasses */
        public ?array $superclasses = null,
    ) {}

    /**
     * @param  class-string  $class
     */
    public static function isAbstract(string $class): self
    {
        return new self($class, FeatureCatalogueStatus::IS_ABSTRACT);
    }

    /**
     * @param  class-string  $class
     */
    public static function notImplemented(string $class): self
    {
        return new self($class, FeatureCatalogueStatus::NOT_IMPLEMENTED);
    }

    /**
     * @param  class-string  $class
     */
    public static function implemented(string $class): self
    {
        return new self($class, FeatureCatalogueStatus::IMPLEMENTED);
    }

    /**
     * @param  class-string  $class
     * @param  class-string[]  $superclasses
     */
    public static function implementedBySuperclass(string $class, array $superclasses): self
    {
        return new self($class, FeatureCatalogueStatus::SUPERCLASS_IMPLEMENTED, $superclasses);
    }
}
