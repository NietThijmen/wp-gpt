<?php

use Laravel\Mcp\Facades\Mcp;

Mcp::web(
    '/mcp/wordpress-hooks',
    \App\Mcp\Servers\WordpressHookServer::class
);

Mcp::web(
    '/mcp/administration',
    \App\Mcp\Servers\AdministrationServer::class
);
