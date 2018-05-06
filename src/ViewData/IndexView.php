<?php declare(strict_types = 1);

namespace App\ViewData;

class IndexView
{
    private $totals;

    public function __construct(array $totals)
    {
        $this->totals = [];
        foreach ($totals as $account => $total) {
            $this->totals[$account] = (int)$total;
        }
    }

    public function getTotals(): array
    {
        return $this->totals;
    }

    public function toArray()
    {
        return ['totals' => $this->totals];
    }
}