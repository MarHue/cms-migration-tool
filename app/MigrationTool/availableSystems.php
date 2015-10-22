<?php
/**
 * Created by PhpStorm.
 * User: Marcel
 * Date: 08.10.2015
 * Time: 15:02
 */
namespace MarHue\CMSMigrations\MigrationTool;

class availableSystems {
    /**
     * Returns array with available Input Systems
     * @return array
     */
    public function getAvailableInputSystems(){
        return config('cms-migrations.formats.input');
    }//function

    /**
     * Returns array with available Output Systems
     * @return array
     */
    public function getAvailableOutputSystems(){
        return config('cms-migrations.formats.output');
    }//function
}