<?php
namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class StarterRepositoryGenerator extends GeneratorCommand
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'starter:repository';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new repository.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('stubs/repository.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }

    protected function getBaseName($name)
    {
        $name = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('Repository', '', $name);
    }

    protected function replaceModelName(&$stub, $name)
    {
        $stub = str_replace(
            '{{ modelName }}',
            $this->getBaseName($name),
            $stub
        );

        return $this;
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $namespace = $this->getNamespace($name);

        $stub = $this->files->get($this->getStub());

        $this->replaceNamespace($stub, $name)
            ->replaceModelName($stub, $name);

        return $this->replaceClass($stub, $name);
    }
}
