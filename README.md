<p align="center">
  <img src="logo-no-bg.png" alt="Dirthara" width="480">
</p>

# Dirthara Collection

Collections for the Dirthara framework. This repository is the initial package
scaffold; no collection API or release is available yet. Usage documentation
lives in [`docs`](docs), prepared for the shared Docusaurus documentation site.

## Installation

Requires PHP 8.5. Install it with:

```sh
composer require dirthara/collection
```

## Docker development environment

Requires Docker with Docker Compose. The development image provides PHP 8.5 CLI,
Composer 2.10.3, Mago 1.47.3, and Xdebug. No database services are needed.

```sh
git clone git@github.com:dirthara/collection.git
cd collection
LOCAL_UID=$(id -u) LOCAL_GID=$(id -g) docker compose up -d --build php
docker compose exec php composer install
```

The container runs as the non-root `developer` user. The build arguments
`LOCAL_UID` and `LOCAL_GID` default to 1000; the command above uses your host IDs
so generated files remain editable. Set `PHP_VERSION` to override the default
8.5 image. Rebuild when the Dockerfile or build arguments change.

Open a shell or stop the environment with:

```sh
docker compose exec php bash
docker compose down
```

## Tests

```sh
docker compose exec php composer test
```

Tests belong in `tests`, under `Dirthara\Collection\Tests`. Source belongs in
`src`, under `Dirthara\Collection`.

The initial scaffold has no PHP source or tests. Test and coverage commands
explicitly report that checks are not applicable while both directories contain
no PHP files. As soon as either contains PHP files, PHPUnit and the coverage
gate run normally; an empty test suite fails.

Xdebug is inactive by default. The coverage command enables it for that run:

```sh
docker compose exec php composer test-coverage
docker compose exec php composer coverage
```

The report is written to `build/coverage/clover.xml`. The gate requires 100%
line coverage of `src` and lists uncovered lines.

## Code quality

Run the same checks as CI:

```sh
docker compose exec php composer ci
```

Run individual checks or apply formatting and lint fixes:

```sh
docker compose exec php composer mago
docker compose exec php composer fmt-check
docker compose exec php composer lint
docker compose exec php composer analyze
docker compose exec php composer guard
docker compose exec php composer cs
```

`composer mago` runs every Mago check even if one fails. `composer cs` modifies
files, including potentially unsafe lint fixes; review its changes.
`mago.toml` requires strict types and sorts imports by length within each type.

The separate Mago service can also run without starting PHP:

```sh
LOCAL_UID=$(id -u) LOCAL_GID=$(id -g) docker compose run --rm mago fmt --check
```

## Contributing

Each supported version has its own branch, beginning with `0.1`; there is no
`main`. See [CONTRIBUTING.md](CONTRIBUTING.md) for branching, release, and pull
request requirements, and [AGENTS.md](AGENTS.md) for agent instructions.

## Security

Report vulnerabilities through GitHub's private advisory form. See
[SECURITY.md](SECURITY.md) for the reporting process and scope.

## License

Copyright (c) 2026 Dirthara. Released under the [MIT License](LICENSE).
