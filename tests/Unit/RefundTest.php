<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Refund;

class RefundTest extends TestCase
{
    /**
     * Trying to change the refund's value after approve.
     *
     * @return void
     */
    public function test_update_value_after_approved()
    {
        $refund = factory(Refund::class)->make();

    	$value = 200;

        $refund->approve();

        $refund->rectify($value);

        $this->assertEquals($refund->approved, true);
        $this->assertNotEquals($refund->value, $value);
    }
}
