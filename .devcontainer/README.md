# Development Container for Laravel WP-GPT

This devcontainer provides a complete development environment for the Laravel WP-GPT project with all required services.

## What's Included

- **PHP 8.2** with all necessary extensions for Laravel
- **Node.js 20.x** for frontend tooling
- **Meilisearch 1.11** for search functionality
- **Vite** with hot module reloading (HMR) support
- **SQLite** database (default)
- **Composer** for PHP dependency management
- **NPM** for JavaScript dependency management

## Services and Ports

The following services are available when the devcontainer is running:

- **Laravel Application**: `http://localhost:8000`
- **Vite Dev Server**: `http://localhost:5173` (with HMR)
- **Meilisearch**: `http://localhost:7700` (Master Key: `masterKey`)

## Getting Started

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop) or [Docker Engine](https://docs.docker.com/engine/install/)
- [Visual Studio Code](https://code.visualstudio.com/)
- [Dev Containers extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) for VS Code

### Opening the Project

1. Open the project folder in VS Code
2. When prompted, click "Reopen in Container" (or press `F1` and select "Dev Containers: Reopen in Container")
3. Wait for the container to build and initialize (first time may take several minutes)
4. The `post-create.sh` script will automatically:
   - Create `.env` file from `.env.example` with devcontainer settings
   - Install Composer dependencies
   - Install NPM dependencies
   - Generate application key
   - Create SQLite database
   - Run migrations

### Running the Development Server

Once the container is ready, you can start the development servers:

#### Option 1: Start All Services (Recommended)

```bash
composer dev
```

This command starts:
- Laravel development server (port 8000)
- Vite development server with HMR (port 5173)
- Laravel queue worker
- Laravel Pail (log viewer)
- Meilisearch (port 7700)

#### Option 2: Start Services Individually

```bash
# Terminal 1 - Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 - Vite (HMR)
npm run dev

# Terminal 3 - Queue Worker (optional)
php artisan queue:listen

# Meilisearch runs automatically as a Docker service
```

### Environment Configuration

The devcontainer is pre-configured with the following settings:

```env
DB_CONNECTION=sqlite
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=masterKey
```

These are automatically applied to your `.env` file during container initialization.

### Vite Hot Module Reloading (HMR)

Vite is configured to support hot reloading in the devcontainer:

- HMR is enabled by default
- File watching uses polling for container compatibility
- The Vite dev server runs on `0.0.0.0:5173` and is accessible from your host machine
- Changes to frontend files (JS, CSS, Svelte) will automatically reload in your browser

### Meilisearch Administration

Access the Meilisearch dashboard at `http://localhost:7700` with master key `masterKey`.

To index data, use Laravel Scout commands:

```bash
# Import all searchable models
php artisan scout:import

# Import specific model
php artisan scout:import "App\Models\YourModel"
```

### Running Tests

```bash
# Run all tests
php artisan test

# Or use composer script
composer test
```

### Database

By default, the devcontainer uses SQLite for simplicity. The database file is located at `database/database.sqlite`.

To use a different database:
1. Update the `DB_*` variables in `.env`
2. Add the database service to `.devcontainer/docker-compose.yml`
3. Rebuild the container

### Troubleshooting

#### Container Won't Start
- Ensure Docker is running
- Try rebuilding the container: `F1` → "Dev Containers: Rebuild Container"

#### Ports Already in Use
- Check if services are running on ports 8000, 5173, or 7700 on your host
- Stop conflicting services or change port mappings in `devcontainer.json`

#### Vite HMR Not Working
- Ensure Vite dev server is running (`npm run dev`)
- Check that port 5173 is properly forwarded
- Try refreshing your browser

#### Permissions Issues
The devcontainer runs as the `vscode` user with appropriate permissions. If you encounter permission issues:

```bash
sudo chown -R vscode:vscode /workspace
chmod -R 775 storage bootstrap/cache
```

## Extensions

The devcontainer includes recommended VS Code extensions:

- PHP Intelephense
- PHP Debug (Xdebug)
- Tailwind CSS IntelliSense
- ESLint
- Svelte for VS Code
- Laravel Extra Intellisense

## Customization

You can customize the devcontainer by editing:

- `.devcontainer/devcontainer.json` - VS Code settings and extensions
- `.devcontainer/Dockerfile` - PHP and system packages
- `.devcontainer/docker-compose.yml` - Services and ports
- `.devcontainer/post-create.sh` - Initialization scripts

After making changes, rebuild the container to apply them.
