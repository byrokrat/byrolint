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
     * @Then there is no error
     */
    public function thereIsNoError(): void
    {
        if ($this->result->isError()) {
            throw new \Exception("Error: {$this->result->getErrorOutput()}");
        }
    }

    /**
     * @Then I get an error
     */
    public function iGetAnError(): void
    {
        if (!$this->result->isError()) {
            throw new \Exception('App invocation should result in an error');
        }
    }

    /**
     * @Then I get an error like :regexp
     */
    public function iGetAnErrorLike($regexp): void
    {
        $this->iGetAnError();
        if (!preg_match($regexp, $this->result->getErrorOutput())) {
            throw new \Exception("Unable to find $regexp in error {$this->result->getErrorOutput()}");
        }
    }

    /**
     * @Then the output contains :expectedCount lines like :regexp
     */
    public function theOutputContainsLinesLike($expectedCount, $regexp): void
    {
        $output = explode("\n", $this->result->getOutput());

        $iCount = 0;

        foreach ($output as $line) {
            if (preg_match($regexp, $line)) {
                $iCount++;
            }
        }

        if ($iCount !== (int)$expectedCount) {
            throw new \Exception(
                "Invalid count ($iCount) of $regexp (expected $expectedCount) in {$this->result->getOutput()}"
            );
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

    /**
     * @Then the output does not contain :string
     */
    public function theOutputDoesNotContain($string): void
    {
        if (preg_match("/$string/i", $this->result->getOutput())) {
            throw new \Exception("$string should not be in output");
        }
    }
}
