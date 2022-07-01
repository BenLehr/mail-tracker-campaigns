<?php

namespace benlehr\MailTracker\Console;


use Illuminate\Console\GeneratorCommand;

class MakeTrackableMail extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:trackable-mail';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Trackable Mailable';
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'TrackableMail';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/trackable_mail.stub';
    }
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Mail';
    }
}
