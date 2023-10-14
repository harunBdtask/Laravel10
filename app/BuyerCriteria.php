<?php


namespace App;

class BuyerCriteria implements Criteria
{
    private $buyerId;

    public function __construct($buyerId)
    {
        $this->buyerId = $buyerId;
    }

    public function execute()
    {
        return ['buyer_id' => $this->buyerId];
    }
}
