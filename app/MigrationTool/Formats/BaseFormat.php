<?php
namespace CMSMigrationTool\MigrationTool\Formats;

use Illuminate\Filesystem\FilesystemAdapter;

abstract class BaseFormat
{
    /**
     * The used disk for this format.
     * @var void|FilesystemAdapter
     */
    protected $disk = null;

    /**
     * The ID of this format.
     * @var string
     */
    protected $formatID = '';

    /**
     * The other format. If this is an output format, this object contains the input format and vice versa.
     * @var BaseFormat|void
     */
    protected $otherFormat = null;

    /**
     * Returns the used disk for this format.
     * @return void|FilesystemAdapter
     */
    public function getDisk()
    {
        return $this->disk;
    } // function

    /**
     * Returns the id of this format.
     * @return string
     */
    public function getFormatID()
    {
        return $this->formatID;
    } // function

    /**
     * Returns the other format.
     * @return BaseFormat|void
     */
    public function getOtherFormat()
    {
        return $this->otherFormat;
    } // function

    /**
     * Sets the used disk for this format.
     * @param FilesystemAdapter $disk
     * @return BaseFormat
     */
    public function setDisk(FilesystemAdapter $disk)
    {
        $this->disk = $disk;

        return $this;
    } // function

    /**
     * Sets the other format.
     * @param BaseFormat $otherFormat
     * @return BaseFormat
     */
    public function setOtherFormat(BaseFormat $otherFormat)
    {
        $this->otherFormat = $otherFormat;

        return $this;
    } // function
} // class