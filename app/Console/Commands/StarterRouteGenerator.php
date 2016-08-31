<?php
namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class StarterRouteGenerator extends GeneratorCommand
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'starter:route';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new route.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Route';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('stubs/route.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Routes\API';
    }

    protected function getBaseName($name)
    {
        $name = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('Routes', '', $name);
    }

    protected function replaceResourceName(&$stub, $name)
    {
        $stub = str_replace(
            '{{ resourceName }}',
            str_slug($this->getBaseName($name)),
            $stub
        );

        return $this;
    }

    protected function replaceControllerName(&$stub, $name)
    {
        $stub = str_replace(
            '{{ controllerName }}',
            $this->getBaseName($name).'Controller',
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

        $this->replaceResourceName($stub, $name)
            ->replaceControllerName($stub, $name);

        return $stub;
    }
}
