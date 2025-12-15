<?php

use Laravel\Mcp\Facades\Mcp;

Mcp::web(
    '/mcp/wordpress-hooks',
    \App\Mcp\Servers\WordpressHookServer::class
);
