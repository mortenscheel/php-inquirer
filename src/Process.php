<?php

namespace Scheel\Inquirer;

class Process
{
    public function __construct(private string $command) {}

    public function run(): Result
    {
        $output = [];
        $exitCode = 0;
        exec($this->command, $output, $exitCode);

        return new Result($this->command, $exitCode, $output);
    }
}
