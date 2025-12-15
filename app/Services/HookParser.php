<?php

namespace App\Services;

use PhpParser\Parser;
use PhpParser\ParserFactory;

class HookParser
{

    public Parser $parser;

    /**
     * @var array|string[] Functions to look for
     */
    protected array $lookFor = [
        // Action
        'add_action',
        'do_action',

        // Filter
        'add_filter',
        'apply_filters',
    ];

    /**
     * HookParser constructor.
     */
    public function __construct()
    {
        $this->parser = (new ParserFactory())->createForHostVersion();
    }

    /**
     * Remove a base path from a full path to get a relative path.
     * @param string $fullPath
     * @param string $basePath
     * @return string Relative path
     */
    private function normalisePath(
        string $fullPath,
        string $basePath

    ): string
    {
        return ltrim(str_replace($basePath, '', $fullPath), '/\\');
    }


    /**
     * Get surrounding lines from a file for context.
     *
     * @param string $file the file path
     * @param int $lineNumber the line number to center around
     * @param int $context number of lines of context to include before and after
     * @return false|array
     */
    private function getSurroundingLines(
        string $file,
        int    $lineNumber,
        int    $context = 5
    ): false|array
    {
        $lines = file($file);
        $start = max(0, $lineNumber - $context - 1);
        $end = min(count($lines) - 1, $lineNumber + $context - 1);
        return array_slice($lines, $start, $end - $start + 1);
    }

    /**
     * Get all files in a directory with given extensions.
     * @param string $basePath
     * @param array $extensions
     * @return array
     */
    private function getAllFiles(
        string $basePath,
        array  $extensions = ['php']
    ): array
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($basePath));
        $files = [];
        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (in_array($file->getExtension(), $extensions)) {
                $files[] = $file->getPathname();
            }
        }
        return $files;

    }

    /**
     * Parse PHP files in the given directory to find hooks.
     * Will return an array of found hooks. Each hook will have:
     * - type (action/filter)
     * - name
     * - file
     * - function (the function used, e.g., add_action)
     * - line (line number)
     * - args (array of arguments passed to the hook)
     * - context some context around the hook call
     *
     * @param string $basePath
     * @return array
     */
    public function parseFiles(
        string $basePath
    ): array
    {
        $files = $this->getAllFiles($basePath);
        $hooks = [];
        foreach ($files as $file) {
            $code = file_get_contents($file);
            try {
                $ast = $this->parser->parse($code);
                $nodeFinder = new \PhpParser\NodeFinder();
                $calls = $nodeFinder->findInstanceOf($ast, \PhpParser\Node\Expr\FuncCall::class);


                $namespace = $ast ? $nodeFinder->findFirstInstanceOf($ast, \PhpParser\Node\Stmt\Namespace_::class) : null;
                // we can kinda guess there's only one class per file
                // if that's not the case, we can improve later
                $class = $ast ? $nodeFinder->findFirstInstanceOf($ast, \PhpParser\Node\Stmt\Class_::class) : null;
                $class_phpdoc = null;
                if($class) {
                    $class_phpdoc = $class->getDocComment() ? $class->getDocComment()->getText() : null;
                }


                foreach ($calls as $call) {
                    if (!$call->name instanceof \PhpParser\Node\Name) {
                        continue;
                    }

                    $functionName = $call->name->toString();


                    if (!in_array($functionName, $this->lookFor)) {
                        continue;
                    }

                    if (!isset($call->args[0])) {
                        continue;
                    }


                    if (!$call->args[0]->value instanceof \PhpParser\Node\Scalar\String_) {
                        continue;
                    }


                    $hookName = $call->args[0]->value->value;

                    $comments = $call->getComments();
                    $comment = end($comments);



                    $parentData = [
                        'code' => implode('\n', $this->getSurroundingLines(
                            $file,
                            $call->getStartLine(),
                            5
                        ))
                    ];

                    if($namespace) {
                        $parentData['namespace'] = $namespace->name ? $namespace->name->toString() : null;
                    }

                    if($class) {
                        $parentData['class'] = $class->name ? $class->name->toString() : null;
                    }

                    if($class_phpdoc) {
                        $parentData['class_phpdoc'] = $class_phpdoc;
                    }

                    if($comment) {
                        $parentData['comment'] = $comment->getText();
                    }


                    // Try to find preceding docblock
                    $comments = $nodeFinder->findInstanceOf($ast, \PhpParser\Node\Stmt\ClassMethod::class);
                    foreach ($comments as $comment) {
                        if ($comment->getEndLine() === $call->getStartLine() - 1) {
                            $parentData['phpdoc'] = $comment->getDocComment() ? $comment->getDocComment()->getText() : null;
                            break;
                        }
                    }


                    $hooks[] = [
                        'type' => in_array($functionName, ['add_action', 'do_action']) ? 'action' : 'filter',
                        'name' => $hookName,
                        'file' => $this->normalisePath(
                            $file,
                            $basePath
                        ),
                        'function' => $functionName,
                        'line' => $call->getStartLine(),
                        'args' => array_map(function ($arg) {
                            if ($arg->value instanceof \PhpParser\Node\Scalar\String_) {
                                return $arg->value->value;
                            } elseif ($arg->value instanceof \PhpParser\Node\Scalar\LNumber) {
                                return $arg->value->value;
                            } elseif ($arg->value instanceof \PhpParser\Node\Expr\Variable) {
                                return '$' . $arg->value->name;
                            } else {
                                return 'complex_expression';
                            }
                        }, $call->args),
                        'context' => $parentData


                    ];


                }
            } catch (\PhpParser\Error $e) {
                // Handle parse error (optional)
                continue;
            }
        }

        return $hooks;
    }
}
