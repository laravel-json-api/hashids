# Change Log

All notable changes to this project will be documented in this file. This project adheres to
[Semantic Versioning](http://semver.org/) and [this changelog format](http://keepachangelog.com/).

## Unreleased

### Added

- Added support for PHP 8.1.

## [1.0.0] - 2021-07-31

Initial stable release, with no changes from `1.0.0-beta.3`.

## [1.0.0-beta.3] - 2021-07-10

### Added

- New `withLength()` method allows the developer to easily set the length for the default `ID` pattern. For example,
  calling this method with `10` would change the pattern from `[a-zA-Z0-9]+` to `[a-zA-Z0-9]{10,}`.

## [1.0.0-beta.2] - 2021-04-20

### Changed

- **BREAKING** Updated the method signature of `HashId::fill()` to add the validated data as the third argument. This
  change was required as the Eloquent `Fillable` interface has been modified.

## [1.0.0-beta.1] - 2021-03-30

Initial release.
