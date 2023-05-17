<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Artisan;
use Mail;
use App\Models\User;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Default preparation for each test
     */
    public function setUp()
    {
        parent::setUp();
 
        $this->prepareForTests();
    }
 
    // /**
    //  * Creates the application.
    //  *
    //  * @return Symfony\Component\HttpKernel\HttpKernelInterface
    //  */
    // public function createApplication()
    // {
    //     $unitTesting = true;
 
    //     $testEnvironment = 'testing';
 
    //     return require __DIR__.'/../../start.php';
    // }
 
    /**
     * Migrates the database and set the mailer to 'pretend'.
     * This will cause the tests to run quickly.
     */
    private function prepareForTests()
    {
        Artisan::call('migrate');
      //  Mail::pretend(true);
    }

    /**
     * @param array $permissions
     * @return User
     */
    public function user(array $permissions = []): User
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        auth()->login($user);

        return $user;
    }
}
