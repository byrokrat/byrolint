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
 * Copyright 2018-20 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\byrolint;

use LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

final class Byrolint
{
    private const INPUT_ARGUMENT = 'input';
    private const ACCOUNT_OPTION = 'account';
    private const BANKGIRO_OPTION = 'bankgiro';
    private const PLUSGIRO_OPTION = 'plusgiro';
    private const PERSONAL_ID_OPTION = 'personal-id';
    private const COORDINATION_ID_OPTION = 'coordination-id';
    private const ORGANIZATION_ID_OPTION = 'organization-id';

    private const OPTION_2_LINTER = [
        self::ACCOUNT_OPTION => AccountLinter::class,
        self::BANKGIRO_OPTION => BankgiroLinter::class,
        self::PLUSGIRO_OPTION => PlusgiroLinter::class,
        self::PERSONAL_ID_OPTION => PersonalIdLinter::class,
        self::COORDINATION_ID_OPTION => CoordinationIdLinter::class,
        self::ORGANIZATION_ID_OPTION => OrganizationIdLinter::class,
    ];

    public function configure(SingleCommandApplication $app): SingleCommandApplication
    {
        return $app
            ->setName('byrolint')
            ->setVersion(Version::getVersion())
            ->setDescription('Command line utility for linting swedish bureaucratic content.')
            ->setCode([$this, 'execute'])
            ->addArgument(
                self::INPUT_ARGUMENT,
                InputArgument::REQUIRED,
                'Input to lint'
            )
            ->addOption(
                self::ACCOUNT_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Lint input as a regular bank account'
            )
            ->addOption(
                self::BANKGIRO_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Lint input as a bankgiro account'
            )
            ->addOption(
                self::PLUSGIRO_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Lint input as a plusgiro account'
            )
            ->addOption(
                self::PERSONAL_ID_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Lint input as a personal id number'
            )
            ->addOption(
                self::COORDINATION_ID_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Lint input as a coordination id number'
            )
            ->addOption(
                self::ORGANIZATION_ID_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Lint input as an organization id number'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new LogicException('Expecting a ConsoleOutputInterface');
        }

        $linters = [];

        foreach (self::OPTION_2_LINTER as $option => $classname) {
            if ($input->getOption($option)) {
                $linters[] = new $classname();
            }
        }

        if (empty($linters)) {
            foreach (self::OPTION_2_LINTER as $classname) {
                $linters[] = new $classname();
            }
        }

        if (count($linters) == 1 && !$output->isVerbose()) {
            $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        }

        /** @var string $toLint */
        $toLint = $input->getArgument(self::INPUT_ARGUMENT);

        $returnCode = 0;

        $errorOutput = $output->getErrorOutput();

        foreach ($linters as $linter) {
            $currentOutput = $output;

            $result = $linter->lint($toLint);

            if (!$result->isValid()) {
                $returnCode = 1;
                $currentOutput = $errorOutput;
            }

            $currentOutput->writeln(
                $currentOutput->isVerbose() ? $result->getLongDesc() : $result->getShortDesc()
            );
        }

        return $returnCode;
    }
}
