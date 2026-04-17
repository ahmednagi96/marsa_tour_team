<?php

namespace App\VoltTests;

use VoltTest\Laravel\Contracts\VoltTestCase;
use VoltTest\Laravel\VoltTestManager;

class TourTest implements VoltTestCase
{
    public function define(VoltTestManager $manager): void
    {
        $scenario = $manager->scenario('UserTest');

        // Step 1: Home Page
        $scenario->step('Tour Page')
            ->get('/http://127.0.0.1:8000/en/api/v1/tours')
            ->expectStatus(200);
            
      
    }
}