<?php

declare(strict_types=1);

namespace App\requests;

class UserMeasure
{
    private const ACTION = "getmeas";

    public function __construct(
        private int $measureType,
        private string $measureTypes = "",
        private int $category = 1,
        private int $startDate = 0,
        private int $endDate = 0,
        private int $lastUpdate = 0,
        private int $offset = 0,
    ) {}

    public function asParams(): array
    {

        return [
            'action' => $this::ACTION,
            'meastype' => $this->measureType,
            'meastypes' => '1,4,12',
            'category' => $this->category,
            'startdate' => $this->startDate,
            'enddate' => $this->endDate,
            'lastupdate' => $this->lastUpdate,
            'offset' => $this->offset,
        ];
    }
}
