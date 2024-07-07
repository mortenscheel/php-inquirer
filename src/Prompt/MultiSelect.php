<?php

namespace Scheel\Inquirer\Prompt;

class MultiSelect extends Prompt
{
    /** @var string[] */
    private array $options = [];

    /** @var string[] */
    private array $initial = [];

    public function __construct()
    {
        $this->subCommand = 'multi-select';
    }

    public function option(string $option): self
    {
        $this->options[] = $option;

        return $this;
    }

    /** @param  string[]  $options */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /** @param  string[]  $initial */
    public function initial(array $initial): self
    {
        $this->initial = $initial;

        return $this;
    }

    protected function getOptions(): array
    {
        $options = array_map(fn (string $option) => '--options '.escapeshellarg($option), $this->options);
        $initial = array_map(fn (string $initial) => '--initial '.escapeshellarg($initial), $this->initial);

        return [...$options, ...$initial];
    }

    /** @return string[] */
    public function run(): array
    {
        return $this->getResult()->output;
    }
}
