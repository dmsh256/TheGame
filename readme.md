# Installation (Ubuntu, PHPStorm)

- Add `127.0.0.1 localhost.test` to your hosts file (/etc/hosts).
- Run `docker compose up -d` or `>>` symbol in `compose.yml` file when in PHPStorm IDE.
- Use built in PHPStorm HTTP client to simulate requests. Open `.http` file in the root directory, choose environment (local) and then run
  - or you can use any HTTP client, just take the parameters from `.http` file.
- To run tests you need to add configuration to your PHPStorm, here are the [docs](https://www.jetbrains.com/help/phpstorm/using-phpunit-framework.html) that will help you 