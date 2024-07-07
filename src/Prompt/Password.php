<?php

namespace Scheel\Inquirer\Prompt;

class Password extends Prompt
{
    private bool $confirm = false;

    public function __construct()
    {
        $this->subCommand = 'password';
    }

    public function confirm(): self
    {
        $this->confirm = true;

        return $this;
    }

    protected function getOptions(): array
    {
        if ($this->confirm) {
            return ['--confirm'];
        }

        return [];
    }

    public function run(): ?string
    {
        return $this->getResult()->output[0] ?? null;
    }
}
