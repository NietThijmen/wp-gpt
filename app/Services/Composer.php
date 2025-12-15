<?php

namespace App\Services;

use App\Models\ComposerRegistry;
use Composer\Config;
use Composer\Downloader\DownloadManager;
use Composer\Factory;
use Composer\IO\ConsoleIO;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class Composer
{

    private array $registries = [];
    private \Composer\Composer $composer;
    private ConsoleIO $io;


    private function generateAuth(): array
    {
        $httpBasic = [];
        foreach ($this->registries as $registry) {
            if (!empty($registry['username']) && !empty($registry['password'])) {
                $httpBasic[$registry['domain']] = [
                    'username' => $registry['username'],
                    'password' => $registry['password'],
                ];
            }
            if (!empty($registry['access_token'])) {
                $httpBasic[$registry['domain']] = [
                    'username' => $registry['access_token'],
                    'password' => '',
                ];
            }
        }
        return $httpBasic;
    }

    public function __construct()
    {
        ComposerRegistry::all()->each(function (ComposerRegistry $composerRegistry) {
            $this->registries[] = $composerRegistry->toArray();
        });


        $input = new ArrayInput([]);
        $output = new ConsoleOutput();
        $helper = new HelperSet();
        $io = new ConsoleIO($input, $output, $helper);

        $this->io = $io;


        $composer = Factory::create($io, [], false);
        $repositoryManager = $composer->getRepositoryManager();

        foreach ($this->registries as $registry) {
            $data = $this->generateAuth();
            foreach ($data as $datum) {

                $repository = $repositoryManager->createRepository(
                    'composer',
                    $datum
                );

                $repositoryManager->addRepository(
                    $repository
                );
            }



        }

        $composer->setRepositoryManager(
            $repositoryManager
        );

        $this->composer = $composer;

    }

    public function getRegistries(): array
    {
        return $this->registries;
    }


    public function search(
        string $query,
        ?string $constraint = null,
    ): array
    {
        return $this->composer->getRepositoryManager()->findPackages(
            $query,
            $constraint
        );
    }

    public function getComposer(): Composer {
        return $this;
    }


    private function rrmdir(
        string $dir,
    )
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object) && !is_link($dir . "/" . $object))
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            rmdir($dir);
        }

    }
    private function generateRepositoriesArray(): array
    {
        $repositories = [];
        foreach ($this->registries as $registry) {
            $repository = [
                'type' => 'composer',
                'url' => $registry['domain'],
            ];

            if($registry['username'] && $registry['password']) {
                $repository['url'] = str_replace(
                    'https://',
                    'https://' . urlencode($registry['username']) . ':' . urlencode($registry['password']) . '@',
                    $registry['domain']
                );
            } elseif($registry['access_token']) {
                $repository['url'] = str_replace(
                    'https://',
                    'https://' . urlencode($registry['access_token']) . ':@',
                    $registry['domain']
                );
            }
            $repositories[] = $repository;
        }

        return $repositories;

    }

    public function generateInstallComposer(
        string $path,
        string $packageName,
        string $packageVersion,
    )
    {


        if(!is_dir($path)){
            mkdir($path, 0777, true);
        } else {
            // remopve composer.lock and composer.json if they exist
            if(file_exists($path . '/composer.lock')) {
                unlink($path . '/composer.lock');
            }

            if(file_exists($path . '/composer.json')) {
                unlink($path . '/composer.json');
            }

            if(is_dir($path . '/vendor')) {
                // remove vendor directory
                rmdir($path . '/vendor');
            }

            if(is_dir($path . '/data')) {
                // force remove data directory
                $this->rrmdir($path . '/data');
            }



        }

        $projectName = str_replace('/', '-', $packageName);

        $data = [
            'name' => 'project/' . $projectName,
            'version' => '1.0.0',
            'require' => [
                $packageName => $packageVersion,
            ],

            'config' => [
                  'allow-plugins' => true,
            ],

            'extra' => [
                'installer-paths' => [
                    'data/{$name}' => true,
                ],
            ],

            'minimum-stability' => 'dev',
            'repositories' => $this->generateRepositoriesArray(),

        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($path . '/composer.json', $json);
    }

    public function install(
        string $path,
    )
    {
        $cmd = "composer install --prefer-dist --working-dir={$path}";
        exec($cmd, $output, $return);
        return [
            'output' => $output,
            'return' => $return,
        ];
    }

}
