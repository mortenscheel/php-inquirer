<?php

namespace Scheel\Inquirer\Prompt;

class Text extends Prompt
{
    private ?string $initial = null;

    private ?string $placeholder = null;

    public function __construct()
    {
        $this->subCommand = 'text';
    }

    public function initial(string $initial): self
    {
        $this->initial = $initial;

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    protected function getOptions(): array
    {
        $options = [];
        if ($this->initial) {
            $options[] = '--initial '.escapeshellarg($this->initial);
        }
        if ($this->placeholder) {
            $options[] = '--placeholder '.escapeshellarg($this->placeholder);
        }

        return $options;
    }

    public function run(): ?string
    {
        return $this->getResult()->output[0] ?? null;
    }
}
