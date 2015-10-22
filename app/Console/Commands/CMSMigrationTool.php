<?php

namespace MarHue\CMSMigrations\Console\Commands;

// TODO
use MarHue\CMSMigrations\MigrationTool\Formats\BaseFormat;
use Illuminate\Console\Command;
use MarHue\CMSMigrations\MigrationTool;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Helper\ProgressBar;

class CMSMigrationTool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:CMS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates an exisiting CM-System to another CM-System.';

    /**
     * Starts Console Menu, Asks User to choose Input and Output CMS,
     * returns array with the two integer
     * @return array
     */
    protected function chooseCMS($availableInputSystems, $availableOutputSystems)
    {

        $this->info("\n************************************************");
        $this->info("\n*********   CMS Migration Manager   ************");
        $this->info("\n************************************************");

        $in = $this->choice('Which input format?', array_keys($availableInputSystems));
        $out = $this->choice('Which output format?', array_keys($availableOutputSystems));
        return array($in, $out);

    }//end function

    /**
     * Asks User to enter Root-Path of Input and Output CMS
     * @return array with the two strings
     */
    protected function getInputAndOutputDisk()
    {
        $validInputPath = FALSE;
        $validOutputPath = FALSE;

        //runs as long as user enters valid input root path
        while(!$validInputPath){
            $goBack = FALSE;
            $rootInput = $this->choice('Which input disk?', array_keys(config('filesystems.disks.cms.migrations')), 0);
            if ($rootInput == 'custom_input') {
                while(!$validInputPath && !$goBack){
                    $rootInput = $this->ask('Please enter root-Path for Input-CMS');
                    if ($rootInput === 'x'){
                        $goBack = TRUE;
                    }
                    if(!file_exists($rootInput)){
                        $this->info('Invalid Path! Please enter valid Root-Path');
                        $this->info("\n" . '[x] -> Go back');
                    }
                    else {
                        $validInputPath = TRUE;
                    }
                }
            } else{
                //default-input-path
                $validInputPath = TRUE;
            }
        }
        //runs as long as user enters valid output root path
        while(!$validOutputPath){
            $goBack = FALSE;
            $rootOutput = $this->choice('Which output disk?', array_keys(config('filesystems.disks.cms.migrations')), 1);
            if ($rootOutput == 'custom_output') {
                while(!$validOutputPath && !$goBack){
                    $rootOutput = $this->ask('Please enter root-Path for Output-CMS');
                    if ($rootOutput === 'x'){
                        $goBack = TRUE;
                    }
                    if(!file_exists($rootOutput)){
                        $this->info('Invalid Path! Please enter valid Root-Path');
                        $this->info("\n" . '[x] -> Go back');
                    }
                    else {
                        $validOutputPath = TRUE;
                    }
                }
            } else{
                //default-output-path
                $validOutputPath = TRUE;
            }
        }
        return array($rootInput, $rootOutput);
    }

    /**
     * starts with entering php artisan commando "migrate:CMS"
     */
    public function handle()
    {
        $systems = new MigrationTool\availableSystems();
        $availableInputSystems = $systems->getAvailableInputSystems();
        $availableOutputSystems = $systems->getAvailableOutputSystems();

        //start menu to aks for In- and Output CMS-Format
        list ($inputCMS, $outputCMS) = $this->chooseCMS($availableInputSystems, $availableOutputSystems);

        //start menu to ask for In- and Output Root-Paths
        list ($rootPathInputCMS, $rootPathOutputCMS) = $this->getInputAndOutputDisk();

        //Initiale Input disk
        if ($rootPathInputCMS == 'input') {
            /** @var FileSystem $inputDisk */
            $inputDisk = Storage::disk('cms.migrations.input');
        } else {
            /** @var FileSystem $inputDisk */
            Config::set('filesystems.disks.cms.migrations.custom_input.root', $rootPathInputCMS);
            $inputDisk = Storage::disk('cms.migrations.custom_input');
        }

        //Initiale Output disk
        if ($rootPathOutputCMS == 'output') {
            /** @var FileSystem $outputDisk */
            $outputDisk = Storage::disk('cms.migrations.output');
        } else {
            /** @var FileSystem $outputDisk */
            Config::set('filesystems.disks.cms.migrations.custom_output.root', $rootPathOutputCMS);
            $outputDisk = Storage::disk('cms.migrations.custom_output');
        }

        // TODO Kontrollieren ob richtiger Typ!
        /** @var BaseFormat $inputFormat */
        $inputFormat = new $availableInputSystems[$inputCMS]($inputDisk);

        /** @var BaseFormat $outputClass */
        $outputClass = new $availableOutputSystems[$outputCMS]($inputFormat, $outputDisk);

        $inputFormat->setOtherFormat($outputClass);

//        $this->output->progressStart(10);
        $wrongFiles = $outputClass->startMigration();
//        for ($i = 0; $i < 10; $i++) {
//            sleep(1);
//
//            $this->output->progressAdvance();
//        }
//        $this->output->progressFinish();

        if (is_array($wrongFiles)) {
            $this->table(['problematic file'], $wrongFiles);
        } // if
    }
}