<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Grisendo\DDD\Uuid;

abstract class UuidType extends StringType
{
    abstract protected function typeClassName(): string;

    abstract protected function customTypeName(): string;

    public function getName(): string
    {
        return self::customTypeName();
    }

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform
    ): string {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        $className = $this->typeClassName();

        return new $className(new Uuid($value));
    }

    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform
    ): mixed {
        if (null === $value) {
            return null;
        }

        return $value->getValue()->getValue();
    }
}
