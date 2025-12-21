<?php

use Laravel\Mcp\Facades\Mcp;


// @deprecated the old route when the WordPress server was only hooks
Mcp::web(
    '/mcp/wordpress-hooks',
    \App\Mcp\Servers\WordpressServer::class
);

Mcp::web(
    '/mcp/wordpress',
    \App\Mcp\Servers\WordpressServer::class
);

Mcp::web(
    '/mcp/administration',
    \App\Mcp\Servers\AdministrationServer::class
)->middleware('auth:sanctum');
