<?php

namespace Scheel\Inquirer;

class Result
{
    /** @param string[] $output */
    public function __construct(
        public string $command,
        public int $exitCode,
        public array $output
    ) {}
}
