<?php

namespace Library\InstallManager;

use Illuminate\Database\Capsule\Manager as Capsule;
use Library\Support\Blitz;

abstract class Manager
{
    private $messages = array();
    protected $module;


    protected function install($module)
    {
        $this->module = ucfirst($module);
        $this->checkRequirements();
        $this->checkTables();




        if ($this->hasMessage()) {
            $this->error();
        } else {
            $this->installTables();
            $this->copySources();
        }
    }




    private function checkTables()
    {
        if (empty($this->tables)) {
            return false;
        }

        foreach ($this->tables as $table) {
            if (Capsule::schema()->hasTable($table)) {
                $this->addMessage("<strong>$table</strong> tablosu mevcut.");
            }
        }

    }


    private function installTables()
    {
        if (empty($this->tables)) {
            return false;
        }

        foreach ($this->tables as $table) {
            Capsule::schema()->create($table, function($schema) use ($table) {
                $table = ucfirst($table);
                $this->{"table$table"}($schema);
            });
        }
    }




    private function checkRequirements()
    {
        if (empty($this->requirements)) {
            return false;
        }

        foreach ($this->requirements as $path => $version)
        {
            if (! file_exists('library/' . $path . '.php')) {
                $this->addMessage("<strong>$path</strong> kütüphanesi bulunamadı.");
            } else {
                $class = '\\Library\\' . str_replace('/', '\\', $path);
                if ((float) $class::version() < (float) $version) {
                    $this->addMessage("<strong>$path</strong> kütüphane versiyonu uyuşmuyor. <span class=\"label label-info\">$version / {$class::version()}</span>");
                }
            }
        }
    }



    private function copySources()
    {
        $source = 'app/Modules/'. $this->module . '/Installer/Source';
        if (file_exists($source)) {
            $this->createBackup($source, './');
        }
    }



    private function addMessage($message)
    {
        $this->messages[] = $message;
    }



    private function hasMessage()
    {
        return count($this->messages) > 0 ? true : false;
    }



    private function error()
    {
        $this->view('error');
    }


    private function view($file, array $data = array())
    {
        $base = Blitz::getInstance()->request->getRootUri();
        $current = Blitz::getInstance()->request->getResourceUri();
        $this->messages = array_merge($this->messages, $data);


        ob_start();
        include('library/InstallManager/Template/' . $file . '.php');
        $buffer = ob_get_contents();
        @ob_end_clean();

        echo $buffer;
    }


    private function createBackup($source, $target)
    {
        $backup = 'backup';
        $backupTime = 'backup/' . time();

        if (! is_dir($backup)) {
            mkdir($backup);
            chmod($backup, 0777);
        }

        $this->copyFiles($source, $target, $backupTime);

        @rmdir($backupTime);
    }


    private function copyFiles($source, $target, $backup)
    {
        if (is_file($source)) {
            if (is_file($target)) {
                copy($target, $backup);
            }
            return copy($source, $target);
        }

        if (is_dir($source) && is_dir($target)) {
            mkdir($backup);
            chmod($backup, 0777);
        }

        if (! is_dir($target)) {
            mkdir($target);
            chmod($target, 0777);
        }

        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            if ($target !== "$source/$entry") {
                $this->copyFiles("$source/$entry", "$target/$entry", "$backup/$entry");
            }
        }
        $dir->close();
    }







} 