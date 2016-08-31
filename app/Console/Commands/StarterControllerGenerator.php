<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class StarterControllerGenerator extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('stubs/controller.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers\API';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
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
            ->replaceTransformerName($stub, $name)
            ->replaceRequestName($stub, $name)
            ->replaceRepositoryName($stub, $name)
            ->replaceRepositoryInstance($stub, $name);

        return $this->replaceClass($stub, $name);
    }

    protected function getBaseName($name)
    {
        $name = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('Controller', '', $name);
    }

    protected function replaceTransformerName(&$stub, $name)
    {
        $stub = str_replace(
            '{{ transformerName }}', $this->getBaseName($name).'Transformer', $stub
        );

        return $this;
    }

    protected function replaceRequestName(&$stub, $name)
    {
        $stub = str_replace(
            '{{ requestName }}', $this->getBaseName($name).'Request', $stub
        );

        return $this;
    }

    protected function replaceRepositoryName(&$stub, $name)
    {
        $stub = str_replace(
            '{{ repositoryName }}', $this->getBaseName($name).'Repository', $stub
        );

        return $this;
    }

    protected function replaceRepositoryInstance(&$stub, $name)
    {
        $stub = str_replace(
            '{{ repositoryInstance }}', camel_case($this->getBaseName($name)).'Repository', $stub
        );

        return $this;
    }
}
