<?php
namespace CMSMigrationTool\MigrationTool\Formats;

trait InputTrait
{
    /**
     * Returns an array of any allowed file of the input.
     * @return array
     */
    abstract public function getAllInputFiles();
} // trait