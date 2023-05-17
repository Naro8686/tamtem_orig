<?php

namespace Tests\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Org\Organization;

class ExampleTest extends TestCase
{
   
    use DatabaseTransactions, WithFaker;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        
        $this->assertTrue(true);
    }
     
    // public function test_create_user_and_organization()
    // {
    //     /** @var User $user */
    //     $user = factory(User::class)->create();

    //     /** @var Organization $organization */
    //     $organization = factory(Organization::class)->create(['owner_id' => $user->id]);
    //   //  dd( $organization->toArray());
    //     $user->organization_id = $organization->id;
    //     $user->is_org_created = true;
    //     $user->save();
    //   //  dd($user->with('organization')->get()->toArray());
    //     $this->assertInstanceOf(User::class, $user);
    // }
}
