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

use byrokrat\id\IdInterface;
use byrokrat\id\PersonalIdFactory;
use byrokrat\id\Exception as IdException;

final class PersonalIdLinter implements LinterInterface
{
    /** @var PersonalIdFactory */
    private $personalIdFactory;

    public function __construct()
    {
        $this->personalIdFactory = new PersonalIdFactory();
    }

    public function lint(string $input): LintInterface
    {
        try {
            $id = $this->personalIdFactory->createId($input);

            return new LintPassed(
                "$input is a valid personal id number",
                sprintf(
                    "%s is a valid personal id number\n> formatted: %s\n> canonical: %s",
                    $input,
                    $id->format(IdInterface::FORMAT_10_DIGITS),
                    $id->format(IdInterface::FORMAT_12_DIGITS),
                )
            );
        } catch (IdException $exception) {
            return new LintFailed(
                "$input is not a valid personal id number",
                sprintf(
                    "Faild parsing %s as a personal id number\n%s",
                    $input,
                    $exception->getMessage()
                )
            );
        }
    }
}
