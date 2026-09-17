<?php

namespace Tests\Unit;

use App\Services\DocumentCalculator;
use PHPUnit\Framework\TestCase;

class DocumentCalculatorTest extends TestCase
{
    private DocumentCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new DocumentCalculator;
    }

    public function test_calculates_decimal_line_totals_without_trusting_subtotal(): void
    {
        $this->assertSame('30.00', $this->calculator->lineSubtotal([
            'quantity' => '1.5',
            'unit_price' => '20.00',
            'subtotal' => '0.01',
        ]));
    }

    public function test_calculates_simple_line_totals(): void
    {
        $this->assertSame('100.00', $this->calculator->lineSubtotal([
            'quantity' => '2',
            'unit_price' => '50.00',
        ]));
    }

    public function test_sums_multiple_line_subtotals(): void
    {
        $items = [
            ['quantity' => '1', 'unit_price' => '100.00'],
            ['quantity' => '2', 'unit_price' => '50.00'],
            ['quantity' => '0.5', 'unit_price' => '200.00'],
        ];

        $this->assertSame('300.00', $this->calculator->subtotal($items));
    }

    public function test_returns_zero_for_empty_items(): void
    {
        $this->assertSame('0.00', $this->calculator->subtotal([]));
    }
}
