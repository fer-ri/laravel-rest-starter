<?php
namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class StarterTransformerGenerator extends GeneratorCommand
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'starter:transformer';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new transformer.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('stubs/transformer.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Transformers';
    }

    protected function getBaseName($name)
    {
        return str_replace($this->getNamespace($name).'\\', '', $name);
    }

    protected function replaceModelName(&$stub, $name)
    {
        $stub = str_replace(
            '{{ modelName }}',
            str_replace('Transformer', '', $this->getBaseName($name)),
            $stub
        );

        return $this;
    }

    protected function replaceModelInstance(&$stub, $name)
    {
        $stub = str_replace(
            '{{ modelInstance }}',
            camel_case(str_replace('Transformer', '', $this->getBaseName($name))),
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
            ->replaceModelName($stub, $name)
            ->replaceModelInstance($stub, $name);

        return $this->replaceClass($stub, $name);
    }
}
