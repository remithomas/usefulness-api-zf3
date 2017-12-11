# Usefulness API ZF3

[![Build Status](https://travis-ci.org/remithomas/usefulness-api-zf3.svg?branch=master)](https://travis-ci.org/remithomas/usefulness-api-zf3)
[![Coverage Status](https://coveralls.io/repos/github/remithomas/usefulness-api-zf3/badge.svg?branch=master)](https://coveralls.io/github/remithomas/usefulness-api-zf3)

This is a [Usefulness API](https://github.com/remithomas/usefulness-apis), using :

- [Zend Framework 3](https://framework.zend.com/learn)

## Requirements

- Php 7.1

## Environements variables

```yml
# todo
```

## Starting App

```bash
composer serve
```

## Code coverage

How to config. Using [coveralls.io](https://coveralls.io), create a file named .coveralls.yml with your repo_token

```yml
service_name: travis-ci
coverage_clover: clover.xml
json_path: coveralls-upload.json
```

How to execute

```bash
composer cover
```

## Urls

- `/` basic page
- `/me` myself page

## Contributions

Don't hesitate to submit issues, comments and pull request. This has been developped in TDD.

## ToDO / Plans

- [ ] Add protected (user: me) page
- [ ] Add error (default, 404) as a JSON view