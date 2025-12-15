<?php

namespace App\Actions;

use App\Models\Hook;
use App\Models\HookOccurance;
use App\Models\Plugin;
use App\Services\Composer;
use App\Services\HookParser;

class ParsePlugin
{
    public static function execute(
        Composer   $composer,
        HookParser $hookParser,

        string     $package_name,
        string     $package_version = 'dev-trunk',

    )
    {
        $composer->generateInstallComposer(
            public_path('packages/' . $package_name),
            $package_name,
            $package_version
        );

        $composer->install(
            public_path('packages/' . $package_name)
        );


        $pluginName = explode('/', $package_name)[1];

        $actions = $hookParser->parseFiles(
            public_path(
                "packages/{$package_name}/data/{$pluginName}"
            ),
        );


        \DB::transaction(function () use ($package_name, $package_version, $pluginName, $actions) {
            $plugin = Plugin::create([
                'name' => $pluginName,
                'composer_registry_id' => 1,
                'description' => 'A parsed plugin',
                'version' => $package_version,
                'author' => 'Unknown',
                'slug' => $package_name,
            ]);

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
                if (!isset($hook_ids[$filter['name']])) {

                    \Log::warning("Filter without action: " . $filter['name']);
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
                    'class_phpdoc' => $filter['context']['class_phpdoc'] ?? null
                ]);
            }
        });


        return true;
    }

}
