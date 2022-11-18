<?php

namespace App\Services;

use App\Models\Inventory;

class InventoryService
{

    public function __construct()
    {
        $this->Inventory = new Inventory();
    }

    public function request($RequestedUnit){
        //get the Inventory with type = "Purchase"
        $Inventory = $this->getPurchaseInv();
        //get the Total application (consumed) quantity (type = "Application")
        $TotalApp = $this->getApplicationQty();
        //get the Total purchase quantity (type = "Purchase")
        $TotalPurchase = $this->getPurchaseQty();
        //get the Total available units
        $TotalAvailableUnits = abs($TotalPurchase - $TotalApp);

        $result = $this->InventoryService->processRequest($RequestedUnit, $Inventory, $TotalApp, $TotalPurchase, $TotalAvailableUnits);

        //if success save the Requested quantity
        if(isset($result['success'])){
            $this->InventoryService->saveAppliedQty($RequestedUnit);
        }

        return $result;
    }

    public function processRequest($RequestedUnit, $Inventory, $TotalApp, $TotalPurchase, $TotalAvailableUnits)
    {
        //quantity on each loop will be withdrawn; Used to checked if all RequestedUnit is already applied
        $CurrentAppInput = $RequestedUnit;

        $TotalConsumedUnits = 0; //total used units on each loop
        $CurrentPrice = 0; //total price of requested quantity will be saved

        //check if the request is valid
        if ($RequestedUnit <= 0) {
            return [
                'error' => 'Invalid input',
                'quantity' => $RequestedUnit,
                'available' => $TotalAvailableUnits
            ];
        }

        if (($TotalApp >= $TotalPurchase)
            || ($RequestedUnit > $TotalPurchase)
        ) {
            //quantity to be applied exceeds the quantity on hand
            return [
                'error' => 'Invalid request',
                'quantity' => $RequestedUnit,
                'available' => $TotalAvailableUnits
            ];
        } else {

            foreach ($Inventory as $data) { //foreach of all Purchased Units

                $CurrentUnits = $data->quantity;
                //first check if there are available consumed units.
                if ($TotalApp > 0) {
                    //determine if the CurrentUnits(Purchase) is still not yet consumed
                    if (($TotalConsumedUnits + $CurrentUnits) >= $TotalApp) {
                        //determine the available units on CurrentUnits after subsctracting the consumed units
                        $CurrentUnits = ($TotalConsumedUnits + $CurrentUnits) - $TotalApp;
                        //all consumed is already checked
                        $TotalApp = 0;
                        //currentloop cannot does not any available Units to consume
                        $TotalConsumedUnits = 0;
                    } else {
                        //store the currentloop available CurrentUnits
                        $TotalConsumedUnits = $TotalConsumedUnits += $CurrentUnits;
                        //currentloop Currentunits is already consumed
                        $CurrentUnits = 0;
                    }
                }

                //check if the CurrentUnits still have available units to consume
                if ($CurrentUnits > 0) {
                    //check if the currentloop currentUnits and TotalConsumedUnits can already supply the RequestedUnit
                    if (($TotalConsumedUnits + $CurrentUnits) >= $RequestedUnit) {
                        //store the unit price of the currentloop
                        $CurrentPrice = $CurrentPrice + ($CurrentAppInput * $data->unit_price);
                        //all RequestedUnit is already consumed on available units
                        $CurrentAppInput = 0;
                        break;
                    } else {
                        //if the currentloop cannot supply the total RequestedUnit then store all units its price that can be consumed on the currentloop
                        $TotalConsumedUnits = $TotalConsumedUnits + $CurrentUnits;
                        //store the unit price of the currentloop
                        $CurrentPrice = $CurrentPrice + ($CurrentUnits * $data->unit_price);
                        //update the RequestedUnit. substract the units thats is already consumed
                        $CurrentAppInput = $CurrentAppInput - $CurrentUnits;
                    }
                }
            } //end Foreach

            //check if the RequestedUnit is already consumed
            if ($CurrentAppInput == 0) {
                //return the Total price of RequestedUnit
                return [
                    'success' => 'Successfully updated inventory',
                    'quantity' => $RequestedUnit,
                    'price' => '$' . $CurrentPrice,
                    'available' => abs($TotalAvailableUnits - $RequestedUnit)
                ];
            } else {
                //quantity to be applied exceeds the quantity on hand
                return [
                    'error' => 'Invalid request',
                    'quantity' => $RequestedUnit,
                    'available' => $TotalAvailableUnits
                ];
            }
        }
    }

    public function getPurchaseInv()
    {
        return $this->Inventory->where('type', 'Purchase')->orderBy('id', 'asc')->get();
    }

    public function getPurchaseQty()
    {
        //abs to not return negative
        return abs($this->Inventory->where('type', 'Purchase')->sum('quantity'));
    }

    public function getApplicationQty()
    {
        //abs to not return negative
        return abs($this->Inventory->where('type', 'Application')->sum('quantity'));
    }

    public function saveAppliedQty($RequestedUnit)
    {
        return $this->Inventory->create([
            'type' => Inventory::TYPE_APPLICATION,
            'quantity' => -1 * abs($RequestedUnit)
        ]);
    }
}
