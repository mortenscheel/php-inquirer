<?php

namespace Scheel\Inquirer\Prompt;

class Date extends Prompt
{
    private ?string $minDate = null;

    private ?string $maxDate = null;

    public function __construct()
    {
        $this->subCommand = 'date';
        $this->minDate = '2022-01-01';
        $this->maxDate = '9999-12-31';
    }

    public function minDate(string $date): self
    {
        $this->minDate = $date;

        return $this;
    }

    public function maxDate(string $date): self
    {
        $this->maxDate = $date;

        return $this;
    }

    protected function getOptions(): array
    {
        $options = [];
        if ($this->minDate) {
            $options[] = '--min-date '.escapeshellarg($this->minDate);
        }
        if ($this->maxDate) {
            $options[] = '--max-date '.escapeshellarg($this->maxDate);
        }

        return $options;
    }

    public function run(): ?string
    {
        return $this->getResult()->output[0] ?? null;
    }
}
