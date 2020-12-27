<?php

declare(strict_types=1);

/**
 * Wrapper around an application setup
 */
class ApplicationWrapper
{
    /**
     * @var string Full path to directory where test data is stored
     */
    private $directory;

    /**
     * @var string Path to executable
     */
    private $executable;

    public function __construct(string $executable)
    {
        $this->directory = sys_get_temp_dir() . '/byrolint_test_' . microtime();
        mkdir($this->directory);
        $this->executable = realpath(getcwd() . '/' . $executable);
    }

    public function __destruct()
    {
        if (is_dir($this->directory)) {
            exec("rm -rf {$this->directory}");
        }
    }

    public function execute(string $command): Result
    {
        $process = proc_open(
            str_replace('byrolint', $this->executable, $command),
            [
                1 => ["pipe", "w"],
                2 => ["pipe", "w"]
            ],
            $pipes,
            $this->directory
        );

        $output = stream_get_contents($pipes[1]);
        $errorOutput = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $returnCode = proc_close($process);

        return new Result($returnCode, $output, $errorOutput);
    }

    public function createFile(string $name, string $content): void
    {
        file_put_contents("{$this->directory}/$name", $content);
    }
}
