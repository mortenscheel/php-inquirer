<?php

namespace Scheel\Inquirer\Prompt;

use Scheel\Inquirer\Process;
use Scheel\Inquirer\Result;

abstract class Prompt
{
    protected string $subCommand;

    private ?string $prompt = null;

    private ?string $hint = null;

    abstract public function __construct();

    /** @return string[] */
    abstract protected function getOptions(): array;

    abstract public function run(): mixed;

    public function prompt(string $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function hint(string $hint): static
    {
        $this->hint = $hint;

        return $this;
    }

    private function binary(): string
    {
        $os = php_uname('s');
        $arch = php_uname('m');
        $name = match ($os) {
            'Darwin' => match ($arch) {
                'arm64' => 'mac-arm',
                default => 'mac-x86_64'
            },
            'Windows NT' => match ($arch) {
                'AMD64' => 'win-x86_64.exe',
                default => throw new \RuntimeException("$arch architecture is currently not supported on Windows.")
            },
            'Linux' => match ($arch) {
                'x86_64' => 'linux-x86_64',
                default => throw new \RuntimeException("$arch architecture is currently not supported on Linux.")
            },
            default => throw new \RuntimeException("$os OS is currently not supported.")
        };

        return __DIR__."/../../lib/bin/$name";
    }

    public function getResult(): Result
    {
        $tokens = [$this->binary(), $this->subCommand, ...$this->getOptions()];
        if ($this->prompt) {
            $tokens[] = '--prompt '.escapeshellarg($this->prompt);
        }
        if ($this->hint) {
            $tokens[] = '--hint '.escapeshellarg($this->hint);
        }
        $command = implode(' ', $tokens);

        return (new Process($command))->run();
    }

    public static function make(): static
    {
        return new static();
    }
}
