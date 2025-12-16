<?php

namespace App\Services;

use PhpParser\Parser;
use PhpParser\ParserFactory;

/**
 * Class to parse PHP files and extract class methods information.
 * @package App\Services
 */
class ClassMethodParser
{
    public Parser $parser;



    /**
     * Remove a base path from a full path to get a relative path.
     *
     * @return string Relative path
     */
    private function normalisePath(
        string $fullPath,
        string $basePath

    ): string {
        return ltrim(str_replace($basePath, '', $fullPath), '/\\');
    }


    /**
     * Get all files in a directory with given extensions.
     */
    private function getAllFiles(
        string $basePath,
        array $extensions = ['php'],
        array $withoutDirs = [
            'vendor',
            'composer',
        ]
    ): array {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($basePath));
        $files = [];
        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (in_array($file->getExtension(), $extensions)) {
                $files[] = $file->getPathname();
            }

            // Skip files in excluded directories
            foreach ($withoutDirs as $excludedDir) {
                if (strpos($file->getPathname(), DIRECTORY_SEPARATOR.$excludedDir.DIRECTORY_SEPARATOR) !== false) {
                    continue 2; // Skip to the next file
                }
            }
        }

        return $files;

    }

    public function __construct()
    {
        $this->parser = (new ParserFactory)->createForHostVersion();

    }


    public function parseFiles(
        string $basePath,
    ): array
    {
        $files = $this->getAllFiles($basePath);
        $parsedFiles = [];

        foreach ($files as $filePath) {
            $code = file_get_contents($filePath);
            try {
                $ast = $this->parser->parse($code);

                $classes = [];
                $methods = [];
                foreach ($ast as $class) {
                    if ($class instanceof \PhpParser\Node\Stmt\Class_) {

                        $methods = [];
                        foreach ($class->getMethods() as $method) {


                            $visibility = 'public';
                            if($method->isProtected()) {
                                $visibility = 'protected';
                            } elseif ($method->isPrivate()) {
                                $visibility = 'private';
                            }

                            $methods[] = [
                                'class' => $class->name->toString(),
                                'name' => $method->name->toString(),
                                'visibility' => $visibility,
                                'start_line' => $method->getStartLine(),
                                'end_line' => $method->getEndLine(),
                                'parameters' => array_map(function ($param) {
                                    try {
                                        $name =  $param->var->name;
                                        $type = $param->type ? $param->type->toString() . ' ' : '';
                                        return $type . '$' . $name;
                                    } catch (\Throwable $t) {
                                        return 'unknown';
                                    }
                                }, $method->getParams()),
                                'phpdoc' => $method->getDocComment() ? $method->getDocComment()->getText() : null,
                            ];
                        }



                        $classes[] = [
                            'name' => $class->name->toString(),
                            'start_line' => $class->getStartLine(),
                            'end_line' => $class->getEndLine(),
                            'phpdoc' => $class->getDocComment() ? $class->getDocComment()->getText() : null,
                            'methods' => $methods,
                        ];
                    }
                }



                $parsedFiles[] = [
                    'file' => $this->normalisePath($filePath, $basePath),
                    'content' => $code,
                    'classes' => $classes,
                ];
            } catch (\PhpParser\Error $e) {
                // Handle parse error (e.g., log it)
                continue;
            }
        }

        return $parsedFiles;

    }

}
