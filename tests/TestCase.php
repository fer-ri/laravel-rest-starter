<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use DatabaseTransactions;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        if (file_exists(__DIR__.'/../.env.testing')) {
            (new \Dotenv\Dotenv(__DIR__.'/..', '.env.testing'))->load();
        }

        // quick hack for avoid
        // // No supported encrypter found. The cipher and / or key length are invalid.
        // when using 'AES-256-CBC'
        $app['config']->set('app.cipher', MCRYPT_RIJNDAEL_128);

        return $app;
    }

    protected function asUser()
    {
        return factory(App\Models\User::class)
            ->create([
                'password' => bcrypt('secret'),
                'activated_at' => new DateTime,
            ]);
    }

    /**
     * Return request headers needed to interact with the API.
     *
     * @return Array array of headers.
     */
    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if ($user instanceof User) {
            $token = JWTAuth::fromUser($user);

            JWTAuth::setToken($token);

            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }

    protected function migrate()
    {
        Artisan::call('migrate');
    }
}
