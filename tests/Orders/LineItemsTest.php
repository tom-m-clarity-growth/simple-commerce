<?php

namespace DoubleThreeDigital\SimpleCommerce\Tests\Orders;

use DoubleThreeDigital\SimpleCommerce\Facades\Order;
use DoubleThreeDigital\SimpleCommerce\Facades\Product;
use DoubleThreeDigital\SimpleCommerce\Tests\SetupCollections;
use DoubleThreeDigital\SimpleCommerce\Tests\TestCase;
use Illuminate\Support\Collection;

class LineItemsTest extends TestCase
{
    use SetupCollections;

    public function setUp(): void
    {
        parent::setUp();

        $this->useBasicTaxEngine();
    }

    /** @test */
    public function can_get_line_items()
    {
        Product::make()->id('one-two-three')->price(1000)->save();
        Product::make()->id('nine-ten-eleven')->price(1000)->save();

        $order = Order::make()->lineItems([
            [
                'id'       => 'one-two-three',
                'product'  => 'oon-doo-twa',
                'quantity' => 2,
            ],
            [
                'id'       => 'nine-ten-eleven',
                'product'  => 'noin-dois-tre',
                'quantity' => 2,
            ],
        ]);

        $order->save();

        $lineItems = $order->lineItems();

        $this->assertTrue($lineItems instanceof Collection);
        $this->assertSame($lineItems->count(), 2);
    }

    /** @test */
    public function line_items_return_empty_if_order_has_no_items()
    {
        $order = Order::make();
        $order->save();

        $lineItems = $order->lineItems();

        $this->assertTrue($lineItems instanceof Collection);
        $this->assertSame($lineItems->count(), 0);
    }

    /** @test */
    public function can_update_line_item()
    {
        $product = Product::make()
            ->price(1000)
            ->data([
                'title' => 'Four Five Six',
            ]);

        $product->save();

        $order = Order::make()->lineItems([
            [
                'id'       => 'ideeeeee-of-item',
                'product'  => $product->id,
                'quantity' => 2,
            ],
        ]);

        $order->save();

        $update = $order->updateLineItem('ideeeeee-of-item', [
            'quantity' => 3,
            'metadata' => [
                'product_key' => 'gday-mate',
            ],
        ]);

        $this->assertSame($order->lineItems()->count(), 1);

        $this->assertSame($order->lineItems()->first()->quantity(), 3);
        $this->assertArrayHasKey('metadata', $order->lineItems()->first());
    }

    /** @test */
    public function can_clear_line_items()
    {
        $product = Product::make()
            ->price(1000)
            ->data([
                'title' => 'Four Five Six',
            ]);

        $product->save();

        $order = Order::make()->lineItems([
            [
                'id'       => 'ideeeeee-of-item',
                'product'  => $product->id,
                'quantity' => 2,
            ],
        ]);

        $order->save();

        $lineItems = $order->clearlineItems();

        $this->assertTrue($lineItems instanceof Collection);
        $this->assertSame($lineItems->count(), 0);
    }
}
