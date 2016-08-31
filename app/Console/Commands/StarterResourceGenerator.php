<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StarterResourceGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter:resource {name : The name of the Crud.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all needed resource for basic Crud';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = studly_case($this->argument('name'));

        $this->call('starter:model', ['name' => $name]);
        $this->call('starter:repository', ['name' => $name.'Repository']);
        $this->call('starter:request', ['name' => $name.'Request']);
        $this->call('starter:controller', ['name' => $name.'Controller']);
        $this->call('starter:transformer', ['name' => $name.'Transformer']);
        $this->call('starter:route', ['name' => $name.'Routes']);
    }
}
