# WP hook GPT

A MCP for your IDE which can search through WordPress hooks from the core and even plugins.

## Features
- Full index of WordPress hooks.
- An administrative MCP to search through composer and index new plugins/composer packages.
- Search by hook name, description, or source file.
- Integration with popular IDEs. (Fully compatible with all MCP-compatible IDEs)
- Fully made with MeiliSearch for lightning-fast searches.
- A full on "documentation" search experience for WordPress plugins/core and it's classes.

## Installation

### Using DevContainer (Recommended)
The easiest way to get started is using the provided DevContainer configuration:

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop) and [VS Code](https://code.visualstudio.com/)
2. Install the [Dev Containers extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)
3. Clone the repository
4. Open the project in VS Code
5. When prompted, click "Reopen in Container" (or press F1 and select "Dev Containers: Reopen in Container")
6. Wait for the container to build and initialize
7. Run `composer dev` to start all services

See [.devcontainer/README.md](.devcontainer/README.md) for detailed documentation.

### Manual Installation
1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Set up MeiliSearch and configure the connection in the MCP settings.
4. Use the admin MCP to index WordPress core hooks and any additional plugins or packages.
5. Integrate the MCP with your IDE of choice.
6. Start searching through WordPress hooks directly from your IDE!


## Contributing
Contributions are welcome! Please fork the repository and submit a pull request.
