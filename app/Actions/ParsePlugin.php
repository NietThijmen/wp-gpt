<?php

namespace App\Actions;

use App\Models\FileClass;
use App\Models\Hook;
use App\Models\HookOccurance;
use App\Models\Plugin;
use App\Models\PluginFile;
use App\Services\ClassMethodParser;
use App\Services\Composer;
use App\Services\HookParser;

class ParsePlugin
{
    public static function execute(
        Composer $composer,
        HookParser $hookParser,
        ClassMethodParser $classMethodParser,

        string $package_name,
        string $package_version = 'dev-trunk',

    ) {

        ini_set(
            'max_execution_time',
            300
        );

        if (Plugin::where('name', $package_name)->where('version', $package_version)->exists()) {
            \Log::info("Plugin {$package_name} version {$package_version} already parsed.");

            return false;
        }

        $composer->generateInstallComposer(
            public_path('packages/'.$package_name),
            $package_name,
            $package_version
        );

        $composer->install(
            public_path('packages/'.$package_name)
        );

        $pluginName = explode('/', $package_name)[1];




        $files_classes_methods = $classMethodParser->parseFiles(
            public_path(
                "packages/{$package_name}/"
            ),
        );




        $actions = $hookParser->parseFiles(
            public_path(
                "packages/{$package_name}/"
            ),
        );

        \DB::transaction(function () use ($package_name, $package_version, $pluginName, $actions, $files_classes_methods) {
            $plugin = Plugin::create([
                'name' => $pluginName,
                'description' => 'A parsed plugin',
                'version' => $package_version,
                'author' => 'Unknown',
                'slug' => $package_name,
            ]);



            foreach ($files_classes_methods as $plugin_files) {
                $classesData = $plugin_files['classes'] ?? null;

                if(! $classesData || count($classesData) == 0) {
                    continue;
                }

                $file = $plugin_files['file'];


                $fileContent = $plugin_files['content'];
                $pluginFile = PluginFile::create([
                    'plugin_id' => $plugin->id,
                    'path' => $file,
                    'content' => $fileContent
                ]);

                foreach ($classesData as $classData) {
                    $fileClass = FileClass::create([
                        'plugin_file_id' => $pluginFile->id,
                        'className' => $classData['name'],
                        'phpdoc' => $classData['phpdoc'] ?? null,
                    ]);

                    $methods = $classData['methods'] ?? [];

                    foreach ($methods as $method) {
                        $fileClass->methods()->create([
                            'name' => $method['name'],
                            'visibility' => $method['visibility'],
                            'parameters' => json_encode($method['parameters']),
                            'start_line' => $method['start_line'],
                            'end_line' => $method['end_line'],
                            'phpdoc' => $method['phpdoc'] ?? null,
                        ]);
                    }
                }



            }


            $hook_ids = [];

            foreach ($actions as $action) {

                if (isset($hook_ids[$action['name']])) {
                    continue;
                }

                $data = Hook::create([
                    'type' => 'action',
                    'name' => $action['name'],
                    'plugin_id' => $plugin->id,
                ]);

                $hook_ids[$action['name']] = $data->id;
            }

            foreach ($actions as $filter) {
                if (! isset($hook_ids[$filter['name']])) {

                    \Log::warning('Filter without action: '.$filter['name']);

                    continue;
                }

                HookOccurance::create([
                    'hook_id' => $hook_ids[$filter['name']],
                    'file_path' => $filter['file'],
                    'line' => $filter['line'],
                    'args' => $filter['args'],
                    'surroundingCode' => $filter['context']['code'],
                    'class' => $filter['context']['class'] ?? null,
                    'method' => $filter['context']['method'] ?? null,
                    'class_phpdoc' => $filter['context']['class_phpdoc'] ?? null,
                ]);
            }
        });

        return true;
    }
}
