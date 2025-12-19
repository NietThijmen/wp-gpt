# WP hook GPT

A MCP for your IDE which can search through WordPress hooks from the core and even plugins.

## Features
- Full index of WordPress hooks.
- An administrative MCP to search through composer and index new plugins/composer packages.
- Search by hook name, description, or source file.
- Integration with popular IDEs. (Fully compatible with all MCP-compatible IDEs)
- Fully made with MeiliSearch for lightning-fast searches.

## Installation
1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Set up MeiliSearch and configure the connection in the MCP settings.
4. Use the admin MCP to index WordPress core hooks and any additional plugins or packages.
5. Integrate the MCP with your IDE of choice.
6. Start searching through WordPress hooks directly from your IDE!


## TODO
- [ ] Implement a user-friendly chatGPT style interface for asking questions from the browser.
- [x] Add a "documentation" like view for each hook with examples and usage.
- [x] Add support for fully indexing classes, methods, and functions in addition to hooks. (e.g. `WP_Query::get_posts()`, `wp_enqueue_script()`, etc.)
- [ ] Add a global search to the frontend for searching through hooks without needing an IDE.
- [ ] Create a cool landing page for the project.
- [ ] Create a cool logo for the project.

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request.
