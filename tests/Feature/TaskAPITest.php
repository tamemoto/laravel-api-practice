<?php
namespace Tests\Feature;
use Tests\TestCase;

class TaskAPITest extends TestCase
{
    public function testTaskListApi()
    {
        $response = $this->json('get', '/api/tasks');
        $response->assertStatus(200);
    }
}
