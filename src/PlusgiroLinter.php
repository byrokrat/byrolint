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
 * Copyright 2020 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\byrolint;

use byrokrat\banking\PlusgiroFactory;
use byrokrat\banking\Exception as BankingException;

final class PlusgiroLinter implements LinterInterface
{
    /** @var PlusgiroFactory */
    private $plusgiroFactory;

    public function __construct()
    {
        $this->plusgiroFactory = new PlusgiroFactory();
    }

    public function lint(string $input): LintInterface
    {
        try {
            $account = $this->plusgiroFactory->createAccount($input);

            return new LintPassed(
                "$input is a valid plusgiro account number",
                sprintf(
                    "%s is a valid plusgiro account number\n> formatted: %s\n> canonical: %s",
                    $input,
                    $account->prettyprint(),
                    $account->get16()
                )
            );
        } catch (BankingException $exception) {
            return new LintFailed(
                "$input is not a plusgiro account number",
                sprintf(
                    "Faild parsing %s as a plusgiro account number\n%s",
                    $input,
                    $exception->getMessage()
                )
            );
        }
    }
}
