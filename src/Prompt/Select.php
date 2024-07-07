<?php

namespace Scheel\Inquirer\Prompt;

class Select extends Prompt
{
    /** @var string[] */
    private array $options = [];

    public function __construct()
    {
        $this->subCommand = 'select';
    }

    public function option(string $option): self
    {
        $this->options[] = $option;

        return $this;
    }

    /** @param string[] $options */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    protected function getOptions(): array
    {
        return array_map(fn (string $option) => '--options '.escapeshellarg($option), $this->options);
    }

    public function run(): ?string
    {
        return $this->getResult()->output[0] ?? null;
    }
}
