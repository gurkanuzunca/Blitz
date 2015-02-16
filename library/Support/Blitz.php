<?php

namespace Library\Support;


use Slim\Slim;
use Illuminate\Database\Capsule\Manager;
use Twig_Loader_Filesystem;
use Twig_Environment;

class Blitz extends Slim
{

    // Sistemde saptanan modullerin verileri
    public $modules = array();


    /**
     * Blitz oluşturulur.
     */
    public function __construct()
    {
        $mainConfig = 'app/config.php';

        if (! file_exists($mainConfig)) {
            throw new \Exception('Config dosyası bulunamadı.');
        }

        $config = require_once $mainConfig;

        if (isset($config['timezone'])) {
            date_default_timezone_set($config['timezone']);
        }

        parent::__construct($config);

        $this->detectModules();
        $this->initEloquent();
        $this->initTwig();

    }


    /**
     * Eloquent nesnesi oluşturulur veyapılandırılır.
     *
     * @throws \Exception
     */
    private function initEloquent()
    {
        if (! isset($this->container['settings']['database'])) {
            throw new \Exception('Database ayarları yapılammış.');
        }

        $capsule = new Manager;
        $capsule->addConnection($this->container['settings']['database']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }


    /**
     * Twig template motoru yapılandırılması.
     *
     * @throws \Exception
     */
    private function initTwig()
    {
        // Twig config kontrolü yapılır.
        if (! isset($this->container['settings']['twig']['path']) || ! isset($this->container['settings']['twig']['cache.path'])){
            throw new \Exception('Twig yapılandırması hatalı.');
        }

        // Dizinlerin varlığı kontrol edilir.
        if (! file_exists($this->container['settings']['twig']['path']) || ! file_exists($this->container['settings']['twig']['cache.path'])) {
            throw new \Exception('Twig dizinleri bulunamadı.');
        }

        // Ana view ve module view tanımlamaları yapılır.
        $twigLoader = new Twig_Loader_Filesystem();
        $twigLoader->addPath($this->container['settings']['twig']['path']);

        foreach ($this->modules as $module) {
            if (isset($module['view'])) {
                $twigLoader->addPath($module['view'], $module['name']);
            }
        }

        // Twig nesnesi oluşturulur ve tanımlanan eklentiler yüklenir.
        $twig = new Twig_Environment($twigLoader, array($this->container['settings']['twig']['cache.path']));

        if (isset($this->container['settings']['twig']['extensions'])) {
            foreach ($this->container['settings']['twig']['extensions'] as $extension) {
                $twig->addExtension(new $extension());
            }
        }

        // Twig nesnesi uygulamaya yüklenir.
        $this->container->singleton('twig', function() use ($twig) {
            return $twig;
        });

    }


    /**
     * Oluşturulan module kaynaklarını saptar.
     *
     * @throws \Exception
     */
    private function detectModules()
    {
        // Module dizin kontrolü yapılır.
        if (! isset($this->container['settings']['module']['path'])){
            throw new \Exception('Module dizin yapılandırması hatalı.');
        }

        $moduleIterator = new \DirectoryIterator($this->container['settings']['module']['path']);

        foreach ($moduleIterator as $iteratorFile) {

            // Dizin elemanlarının klasör olup olmadığı kontrol edilir.
            if ($iteratorFile->isDir() && ! $iteratorFile->isDot()) {

                // Dizin ismini döndürür.
                $moduleName = $iteratorFile->getFilename();
                // Dizin yolunu döndürür.
                $modulePath = $iteratorFile->getPathname();

                $this->modules[$moduleName] = array(
                    'name' => $moduleName
                );

                $viewPath = $modulePath . '/Views';

                if (file_exists($viewPath)) {
                    $this->modules[$moduleName]['view'] = $viewPath;
                }

                $routeFile = $modulePath . '/route.php';

                if (file_exists($routeFile)) {
                    $this->modules[$moduleName]['route'] = $routeFile;
                }
            }
        }
    }


} 