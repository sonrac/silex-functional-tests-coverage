[![Build Status](https://travis-ci.org/sonrac/silex-functional-tests-coverage.svg?branch=master)](https://travis-ci.org/sonrac/silex-functional-tests-coverage) 
[![StyleCI](https://styleci.io/repos/126492798/shield?branch=master&style=flat)](https://styleci.io/repos/126492798)

[![codecov](https://codecov.io/gh/sonrac/silex-functional-tests-coverage/branch/master/graph/badge.svg)](https://codecov.io/gh/sonrac/silex-functional-tests-coverage)

![Scrutinizer Build](https://scrutinizer-ci.com/g/sonrac/silex-functional-tests-coverage/badges/build.png?b=master)
![Scrutinizer](https://scrutinizer-ci.com/g/sonrac/silex-functional-tests-coverage/badges/quality-score.png?b=master)
![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/sonrac/silex-functional-tests-coverage/badges/coverage.png?b=master)
[![codecov](https://codecov.io/gh/sonrac/silex-functional-tests-coverage/branch/master/graph/badge.svg)](https://codecov.io/gh/sonrac/silex-functional-tests-coverage)
![Packagist](https://poser.pugx.org/sonrac/silex-functional-tests-coverage/v/stable.svg)
[![Latest Unstable Version](https://poser.pugx.org/sonrac/silex-functional-tests-coverage/v/unstable)](https://packagist.org/packages/sonrac/silex-functional-tests-coverage)
![License](https://poser.pugx.org/sonrac/silex-functional-tests-coverage/license.svg)
[![Total Downloads](https://poser.pugx.org/sonrac/silex-functional-tests-coverage/downloads)](https://packagist.org/packages/sonrac/silex-functional-tests-coverage)
[![Monthly Downloads](https://poser.pugx.org/sonrac/silex-functional-tests-coverage/d/monthly)](https://packagist.org/packages/sonrac/silex-functional-tests-coverage)
[![Daily Downloads](https://poser.pugx.org/sonrac/silex-functional-tests-coverage/d/daily)](https://packagist.org/packages/sonrac/silex-functional-tests-coverage)
[![composer.lock](https://poser.pugx.org/sonrac/silex-functional-tests-coverage/composerlock)](https://packagist.org/packages/sonrac/silex-functional-tests-coverage)

# Silex coverage for functional tests

## Idea

Functional tests mechanism concatenation with emulation is called in unit tests. All function tests located in trait and include in functional and unit as trait

## Class description

| Class | Description |
| ----- | ----------- |
| BaseControllerTest | Base controllers test for unit testing |
| BaseWebTest | Base functional test controller |
| OnceMigrationUnitTest | Run functional test with running migration on test beginning and rollback after test finished |
| OnceMigrationUnitTest | Run unit test with running migration on test beginning and rollback after test finished |
| OnceMigrationWebTest | Run functional test with running migration on test beginning and rollback after test finished |
| OnceRunMigration | Class for run migration and seeds |
| TestApplication | Abstract class for init application instance |
| UnitTest | Base unit test with bases traits (BootTraits, MigrationTrait) |   

## Traits description

| Trait | Description |
| ----- | ----------- |
| BootTraits | Boot trait in class |
| InitMigrationTestTrait | Using for once run migration in unit or functional tests |
| ControllersTestTrait | Using for additional helper functions in functional tests |
| MigrationsTrait | Trait for run migrations |

## Exceptions

| Exception | Description |
| --------- | ----------- |
| MaxRedirectException | Maximum redirect execution exception |

## Class api

[Docs](https://sonrac.github.io/docs/silex-functional-tests)