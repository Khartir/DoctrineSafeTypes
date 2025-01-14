<?php

declare(strict_types=1);

namespace DobryProgramator\DoctrineSafeTypes\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TimeType;
use Safe\DateTime as SafeDateTime;
use Safe\Exceptions\DatetimeException;

class SafeTimeType extends TimeType
{
    public function getName(): string
    {
        return Types::SAFE_TIME_MUTABLE;
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
            $dateTime = SafeDateTime::createFromFormat('!' . $platform->getTimeFormatString(), $value);
        } catch (DatetimeException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getTimeFormatString()
            );
        }

        return $dateTime;
    }
}
