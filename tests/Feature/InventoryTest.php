<?php

namespace Tests\Feature;

use stdClass;
use Tests\TestCase;
use App\Models\Inventory;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Artisan;

class InventoryTest extends TestCase
{

    protected $InventoryService;

    protected array $sampleData = [
        [
            'type' => Inventory::TYPE_PURCHASE,
            'quantity' => 1,
            'unit_price' => 10
        ], [
            'type' => Inventory::TYPE_PURCHASE,
            'quantity' => 2,
            'unit_price' => 20
        ], [
            'type' => Inventory::TYPE_PURCHASE,
            'quantity' => 2,
            'unit_price' => 15
        ]
    ];

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');
        $this->seed();
        $this->model = new Inventory();
        Inventory::boot();

        $this->InventoryService = new InventoryService();
    }

    public function setUpdata()
    {

        $item = $this->sampleData;

        $array = [];

        foreach ($item as $key => $value) {
            $object = new stdClass();
            $object->type = $value['type'];
            $object->quantity = $value['quantity'];
            $object->unit_price = $value['unit_price'];
            $array[] = $object;
        }
        return $array;
    }

    public function test_request_processing_is_true()
    {
        $array = $this->setUpdata();

        //RequestedUnit : sample request unit is = 1
        //Inventory : hardcoded sampleData
        //TotalApp : total applied units is = 0
        //TotalPurchase : total purchase units is = 5
        //TotalAvailableUnits : total available units is = 5
        $result = $this->InventoryService->processRequest(1, $array, 0, 5, 5);
        $this->assertEquals("$10", $result['price']);

        //RequestedUnit : sample request unit is = 2
        $result = $this->InventoryService->processRequest(2, $array, 0, 5, 5);
        $this->assertEquals("$30", $result['price']);

        //RequestedUnit : sample request unit is = 5
        $result = $this->InventoryService->processRequest(5, $array, 0, 5, 5);
        $this->assertEquals("$80", $result['price']);
    }

    public function test_request_can_return_not_enough_stock()
    {
        $array = $this->setUpdata();

        //RequestedUnit : sample request unit is = 6
        //Inventory : hardcoded sampleData
        //TotalApp : total applied units is = 0
        //TotalPurchase : total purchase units is = 5
        //TotalAvailableUnits : total available units is = 5
        $result = $this->InventoryService->processRequest(6, $array, 0, 5, 5);
        $this->assertEquals("Invalid request", $result['error']);
    }

    public function test_request_can_return_no_stock()
    {
        $array = $this->setUpdata();

        //RequestedUnit : sample request unit is = 6
        //Inventory : hardcoded sampleData
        //TotalApp : total applied units is = 0
        //TotalPurchase : total purchase units is = 0
        //TotalAvailableUnits : total available units is = 0
        $result = $this->InventoryService->processRequest(6, $array, 0, 0, 0);
        $this->assertEquals("Invalid request", $result['error']);
    }

    public function test_request_can_return_invalid_input()
    {
        $array = $this->setUpdata();

        //RequestedUnit : sample request unit is = -6
        //Inventory : hardcoded sampleData
        //TotalApp : total applied units is = 0
        //TotalPurchase : total purchase units is = 0
        //TotalAvailableUnits : total available units is = 0
        $result = $this->InventoryService->processRequest(-6, $array, 0, 0, 0);
        $this->assertEquals("Invalid input", $result['msg']);

        $result = $this->InventoryService->processRequest('', $array, 0, 0, 0);
        $this->assertEquals("Invalid input", $result['msg']);
    }

    public function test_can_get_inventory()
    {
        $result = $this->InventoryService->getPurchaseInv();
        $this->assertNotEmpty($result, "data have value");
    }

    public function test_request_can_be_saved()
    {
        $result = $this->InventoryService->saveAppliedQty(1);
        $this->assertNotEmpty($result, "data have value");
    }

    public function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
