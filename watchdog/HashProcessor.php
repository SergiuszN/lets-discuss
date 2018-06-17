<?php

namespace Watchdog;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\Yaml\Yaml;

class HashProcessor
{
    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $folders;

    /**
     * @var string
     */
    private $savePath;

    /**
     * @var array
     */
    private $hashMap;

    /**
     * HashProcessor constructor.
     */
    public function __construct()
    {
        $this->files = [];
        $this->folders = [];
    }

    /**
     *
     */
    public function calculate()
    {
        $this->hashMap = [];

        foreach ($this->folders as $folder) {
            $this->hashMap = $this->getFolderHashes($this->hashMap, $folder);
        }

        foreach ($this->files as $file) {
            $this->hashMap = $this->getFileHash($this->hashMap, $file);
        }

        return $this->hashMap;
    }

    /**
     * @return array|bool
     */
    public function isChanged()
    {
        $oldHashMap = unserialize(file_get_contents($this->getSavePath()));
        $diff = array_diff_assoc($oldHashMap, $this->hashMap);

        if (empty($diff)) {
            return false;
        } else {
            return $diff;
        }
    }

    /**
     * @param array $changed
     */
    public function sendAlert($changed)
    {
        $config = Yaml::parse(file_get_contents(ROOT_PATH . 'app/config/parameters.yml'));

        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername($config['parameters']['mailer_user'])
            ->setPassword($config['parameters']['mailer_password']);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('File Changed Alert'))
            ->setFrom([$config['parameters']['mailer_user'] => 'Developers Alert'])
            ->setTo($config['parameters']['mailer_user'])
            ->setBody('On server changed files: ' . print_r($changed, true));

        $mailer->send($message);
    }

    /**
     * Save serialized hash map for current files
     */
    public function dump()
    {
        file_put_contents($this->getSavePath(), serialize($this->hashMap));
    }

    /**
     * @param array $hashes
     * @param string $dir
     * @return array
     */
    private function getFolderHashes($hashes, $dir)
    {
        $files = array_diff(scandir($dir), ['..', '.']);

        foreach ($files as $file) {
            $f = $dir . DIRECTORY_SEPARATOR . $file;
            $hashes = is_dir($f) ? $this->getFolderHashes($hashes, $f) : $this->getFileHash($hashes, $f);
        }

        return $hashes;
    }

    /**
     * @param array $hashes
     * @param string $file
     * @return array
     */
    private function getFileHash($hashes, $file)
    {
        $hashes[$file] = md5_file($file);
        return $hashes;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles($files)
    {
        array_map(function ($value) {
            return ROOT_PATH . $value;
        }, $files);
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * @param array $folders
     */
    public function setFolders($folders)
    {
        array_map(function ($value) {
            return ROOT_PATH . $value;
        }, $folders);
        $this->folders = $folders;
    }

    /**
     * @return string
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * @param string $savePath
     */
    public function setSavePath($savePath)
    {
        $this->savePath = AUDIT_ROOT_PATH . DIRECTORY_SEPARATOR . $savePath;
    }
}