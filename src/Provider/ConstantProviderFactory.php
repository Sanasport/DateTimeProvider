<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

declare(strict_types=1);

namespace Kdyby\DateTimeProvider\Provider;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Kdyby\DateTimeProvider\NotImplementedException;
use function date_default_timezone_get;
use function get_class;
use function gettype;
use function is_numeric;
use function is_object;
use function is_string;
use function sprintf;

/**
 * Helper factory to create ConstantProvider from arbitrary input.
 */
class ConstantProviderFactory
{

    /**
     * @param string|int|\DateTimeImmutable|\DateTime $dateTime
     */
    public function create($dateTime) : ConstantProvider
    {
        if ($dateTime instanceof DateTimeImmutable) {
            return new ConstantProvider($dateTime);
        }

        if ($dateTime instanceof DateTime) {
            return new ConstantProvider(DateTimeImmutable::createFromMutable($dateTime));
        }

        if (is_numeric($dateTime)) {
            return new ConstantProvider((new DateTimeImmutable(sprintf('@%.6f', $dateTime)))->setTimezone(new DateTimeZone(date_default_timezone_get())));
        }

        if (is_string($dateTime)) {
            throw new NotImplementedException(sprintf(
                'Cannot process datetime in given format "%s"',
                $dateTime
            ));
        }

        throw new NotImplementedException(sprintf(
            'Cannot process datetime from given value %s',
            is_object($dateTime) ? get_class($dateTime) : gettype($dateTime)
        ));
    }
}
