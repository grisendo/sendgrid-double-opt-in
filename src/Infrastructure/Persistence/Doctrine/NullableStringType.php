<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

abstract class NullableStringType extends StringType
{
    abstract protected function typeClassName(): string;

    abstract protected function customTypeName(): string;

    public function protected(): ?int
    {
        return null;
    }

    public function getName(): string
    {
        return self::customTypeName();
    }

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform
    ): string {
        if (null !== $this->getLength()) {
            $column['length'] = $this->getLength();
        }

        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): mixed {
        $className = $this->typeClassName();

        return $value ? new $className($value) : null;
    }

    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform
    ): ?string {
        return $value ? $value->getValue() : null;
    }
}
