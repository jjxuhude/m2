<?php
namespace TemplateMonster\ThemeUpdater\Model;

use \Magento\Framework\Backup\Filesystem as BackupFilesystem;
use \Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use \Magento\Framework\Filesystem\DirectoryList;
use \TemplateMonster\ThemeUpdater\Model\ThemeData;

class Backup extends \Magento\Framework\DataObject
{
    /**
     * @var string
     */
    protected $_backupDir = 'pub/media/theme_backups';

    /**
     * @var BackupFilesystem
     */
    protected $_filesystem;

    /**
     * @var \TemplateMonster\ThemeUpdater\Model\ThemeData
     */
    protected $_themeData;

    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    public function __construct(
        BackupFilesystem $fileSystem,
        ThemeData $themeData,
        DirectoryList $directoryList,
        array $data = []
    ){
        $this->_filesystem = $fileSystem;
        $this->_themeData = $themeData;
        $this->_directoryList = $directoryList;

        parent::__construct($data);
    }

    /**
     * Create backups
     */
    public function createBackup()
    {
        set_time_limit(0);

        $this->setBackupsDir();

        try{
            $this->checkBackupsDir();
        } catch (\Magento\Framework\Backup\Exception\NotEnoughPermissions $e){
            $this->setData('status', false);
            $this->setData('message', $e->getMessage());
            return;
        }

        $zip = new \ZipArchive();
        $zipStatus = $zip->open($this->getBackupFilePath(), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            if ($zipStatus !== true) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Cannot open archive file.'));
            }
            // Get theme files
            $recursiveIterator = new \RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->getThemeAbsPath(), RecursiveDirectoryIterator::SKIP_DOTS ));

            foreach( $recursiveIterator as $file ){
                $file = $file->getPathname();
                $zip->addFile($file);
            }

            $this->setData('filepath', $this->getBackupFilePath());
            $this->setData('message', __('Backup complete.'));
            $this->setData('status', $zipStatus);

        $zip->close();
    }

    /**
     * Get theme abs path
     */
    protected function getThemeAbsPath(){
        return $this->_themeData->getAbsThemePath();
    }

    /**
     * Get theme name
     */
    protected function getThemeName(){
        return $this->_themeData->getThemeName();
    }

    /**
     * Set backup directory
     *
     * @return $this
     */
    protected function setBackupsDir()
    {
        $this->_filesystem->setBackupsDir($this->getRootDir() . DIRECTORY_SEPARATOR . $this->_backupDir);
        return $this;
    }

    /**
     * Check if backup directory exists and writable
     *
     * @throws \Magento\Framework\Backup\Exception\NotEnoughPermissions
     */
    protected function checkBackupsDir()
    {
        $backupsDir = $this->_filesystem->getBackupsDir();

        if (!is_dir($backupsDir)) {
            $backupsParentDir = dirname($backupsDir);

            if (!is_writeable($backupsParentDir)) {
                throw new \Magento\Framework\Backup\Exception\NotEnoughPermissions(
                    new \Magento\Framework\Phrase("Directory 'pub/media' is not writable.")
                );
            }

            if(!mkdir($backupsDir, 0755)){
                throw new \Magento\Framework\Backup\Exception\NotEnoughPermissions(
                    new \Magento\Framework\Phrase("Can't create backups directory.")
                );
            }
        }

        if (!is_writable($backupsDir)) {
            throw new \Magento\Framework\Backup\Exception\NotEnoughPermissions(
                new \Magento\Framework\Phrase('Backups directory is not writable.')
            );
        }
    }

    /**
     * Get root directory path
     *
     * @return string
     */
    protected function getRootDir()
    {
        return $this->_directoryList->getRoot();
    }

    /**
     * Get backup archive filename
     *
     * @return mixed
     */
    protected function getBackupFilePath()
    {
        $filename = $this->getThemeName() . '_' . microtime(true) . '.zip';
        $path = $this->_filesystem->getBackupsDir() . DIRECTORY_SEPARATOR . $filename;

        return $path;
    }
}