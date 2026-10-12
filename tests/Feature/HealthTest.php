<?php

use Tests\TestCase;

uses(TestCase::class);

it('returns a healthy application response', function () {
    $response = $this->getJson('/api/v1/health');

    $response
        ->assertOk()
        ->assertJson([
            'data' => [
                'status' => 'OK',
                'app' => 'MovieAPI',
            ],
            'message' => 'Health check',
        ]);
});
