<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class StarterDestroyGenerator extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter:destroy {name : The name of the Crud.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroy all resource of basic Crud';

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = studly_case($this->argument('name'));

        $this->deleteFile(app_path('Models/'.$name.'.php'));
        $this->deleteFile(app_path('Repositories/'.$name.'Repository.php'));
        $this->deleteFile(app_path('Transformers/'.$name.'Transformer.php'));
        $this->deleteFile(app_path('Http/Requests/'.$name.'Request.php'));
        $this->deleteFile(app_path('Http/Controllers/API/'.$name.'Controller.php'));
        $this->deleteFile(app_path('Http/Routes/API/'.$name.'Routes.php'));
    }

    protected function deleteFile($path)
    {
        $basename = str_replace(app_path(), '', $path);
        
        if ($this->files->exists($path)) {
            $this->files->delete($path);

            $this->info($basename.' has deleted');
        } else {
            $this->error($basename.' is not exists, skipped!');
        }
    }
}
