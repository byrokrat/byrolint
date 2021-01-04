<?php

/**
 * This file is part of byrolint.
 *
 * byrolint is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * byrolint is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrolint. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2020-21 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\byrolint;

final class Version
{
    private const VERSION_FILE = __DIR__ . '/../VERSION';

    public static function getVersion(): string
    {
        if (is_readable(self::VERSION_FILE)) {
            return trim((string)file_get_contents(self::VERSION_FILE));
        }

        return 'dev-master';
    }
}
