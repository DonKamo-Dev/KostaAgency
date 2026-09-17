<?php

namespace App\Services;

final class DocumentCalculator
{
    public function lineSubtotal(array $item): string
    {
        return number_format(
            (float) ($item['quantity'] ?? 1) * (float) ($item['unit_price'] ?? 0),
            2, '.', ''
        );
    }

    public function subtotal(array $items): string
    {
        return number_format(
            array_sum(array_map(
                fn (array $item) => (float) $this->lineSubtotal($item),
                $items,
            )),
            2, '.', ''
        );
    }
}
