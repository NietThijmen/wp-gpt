<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\FileClass;
use Illuminate\Http\Request;
use Prism\Prism\Prism;

class ClassExplainer extends Controller
{
    public function explain(
        Request $request,
        FileClass $fileClass,
    )
    {
        ini_set('max_execution_time', 0); // No time limit

        $dataAsMarkdown = "# Class: {$fileClass->className}\n\n";
        $dataAsMarkdown .= "## PHPDoc\n\n";
        $dataAsMarkdown .= "```\n{$fileClass->phpdoc}\n```\n\n";
        $dataAsMarkdown .= "## Methods\n\n";
        foreach ($fileClass->methods as $method) {
            $dataAsMarkdown .= "### {$method->name}\n\n";
            $dataAsMarkdown .= "```\n{$method->phpdoc}\n```\n\n";
            $dataAsMarkdown .= "Parameters: " . $method->parameters . "\n\n";
        }
        $prompt = <<<'MARKDOWN'
        You are an expert PHP and WordPress developer.
        Given the following class information
        ```markdown
        {{data}}
        ```
        Explain in detail what this class does, its purpose, and how it might be used in
        a WordPress plugin. Provide examples where relevant.

        MARKDOWN;


        $prompt = str_replace('{{data}}', $dataAsMarkdown, $prompt);

        $response = \Prism\Prism\Facades\Prism::text()
            ->using(config('services.ai.provider'), config('services.ai.model'))
            ->withSystemPrompt($prompt)
            ->withPrompt("Provide a detailed explanation of the class above.")
            ->asText();

        return response()->json([
            'explanation' => $response,
        ]);
    }
}
