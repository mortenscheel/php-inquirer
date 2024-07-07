<?php

namespace Scheel\Inquirer\Prompt;

class Confirm extends Prompt
{
    private ?bool $default = null;

    public function __construct()
    {
        $this->subCommand = 'confirm';
    }

    public function default(bool $default): self
    {
        $this->default = $default;

        return $this;
    }

    protected function getOptions(): array
    {
        $options = [];
        if ($this->default) {
            $options[] = '--default-true';
        }

        return $options;
    }

    public function run(): bool
    {
        return $this->getResult()->exitCode === 0;
    }
}
