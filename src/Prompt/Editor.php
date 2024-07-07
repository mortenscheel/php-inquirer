<?php

namespace Scheel\Inquirer\Prompt;

class Editor extends Prompt
{
    private ?string $program = null;

    private ?string $initial = null;

    public function __construct()
    {
        $this->subCommand = 'editor';
    }

    public function program(string $program): self
    {
        $this->program = $program;

        return $this;
    }

    public function initial(string $text): self
    {
        $this->initial = $text;

        return $this;
    }

    protected function getOptions(): array
    {
        $options = [];
        if ($this->program) {
            $options[] = '--program '.escapeshellarg($this->program);
        }
        if ($this->initial) {
            $options[] = '--text '.escapeshellarg($this->initial);
        }

        return $options;
    }

    public function run(): ?string
    {
        return $this->getResult()->output[0] ?? null;
    }
}
