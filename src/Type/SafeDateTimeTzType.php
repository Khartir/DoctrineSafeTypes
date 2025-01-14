<?php

declare(strict_types=1);

namespace DobryProgramator\DoctrineSafeTypes\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeTzType;
use Safe\DateTime as SafeDateTime;
use Safe\Exceptions\DatetimeException;

class SafeDateTimeTzType extends DateTimeTzType
{
    public function getName(): string
    {
        return Types::SAFE_DATETIMETZ_MUTABLE;
    }

    /**
     * @param mixed $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?SafeDateTime
    {
        if ($value === null || $value instanceof SafeDateTime) {
            return $value;
        }

        try {
            $dateTime = SafeDateTime::createFromFormat($platform->getDateTimeTzFormatString(), $value);
        } catch (DatetimeException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeTzFormatString()
            );
        }

        return $dateTime;
    }
}
