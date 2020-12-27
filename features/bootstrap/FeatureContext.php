<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

class FeatureContext implements Context
{
    /**
     * @var ApplicationWrapper
     */
    private $app;

    /**
     * @var string
     */
    private $executable;

    /**
     * @var Result Result from the last app invocation
     */
    private $result;

    public function __construct(string $executable)
    {
        $this->executable = $executable;
    }

    /**
     * @Given a fresh installation
     */
    public function aFreshInstallation(): void
    {
        $this->app = new ApplicationWrapper($this->executable);
    }

    /**
     * @When I run :command
     */
    public function iRun($command): void
    {
        $this->result = $this->app->execute($command);
    }

    /**
     * @Then the return code is :code
     */
    public function theReturnCodeIs($code)
    {
        if ($this->result->getReturnCode() != $code) {
            throw new \Exception("Expecting return code: $code, found:  {$this->result->getReturnCode()}");
        }
    }

    /**
     * @Then the output contains :string
     */
    public function theOutputContains($string): void
    {
        if (!preg_match("/$string/i", $this->result->getOutput())) {
            throw new \Exception("Unable to find $string in output {$this->result->getOutput()}");
        }
    }
}
