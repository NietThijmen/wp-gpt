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
        // use a recursive directory iterator to get all php files
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($basePath));
        $files = [];
        foreach ($rii as $file) {
            if ($file->isDir()){
                continue;
            }
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }




        $hooks = [];
        foreach ($files as $file) {
            $code = file_get_contents($file);
            try {
                $ast = $this->parser->parse($code);
                $nodeFinder = new \PhpParser\NodeFinder();
                $calls = $nodeFinder->findInstanceOf($ast, \PhpParser\Node\Expr\FuncCall::class);
                foreach ($calls as $call) {
                    if ($call->name instanceof \PhpParser\Node\Name) {
                        $functionName = $call->name->toString();
                        if (in_array($functionName, $this->lookFor)) {
                            if (isset($call->args[0]) && $call->args[0]->value instanceof \PhpParser\Node\Scalar\String_) {
                                $hookName = $call->args[0]->value->value;



                                // context should be the 5 lines before and after the call
                                $startLine = max(1, $call->getStartLine() - 5);
                                $endLine = $call->getEndLine() + 5;
                                $codeLines = explode("\n", $code);
                                $contextLines = array_slice($codeLines, $startLine - 1, $endLine - $startLine + 1);
                                $parentData = [
                                    'start_line' => $startLine,
                                    'end_line' => $endLine,
                                    'code' => implode("\n", $contextLines),

                                ];


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
                                    'file' => $file,
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
                        }
                    }
                }
            } catch (\PhpParser\Error $e) {
                // Handle parse error (optional)
                continue;
            }
        }

        return $hooks;
    }

}
