# Change Log

All notable changes to this project will be documented in this file. This project adheres to
[Semantic Versioning](http://semver.org/) and [this changelog format](http://keepachangelog.com/).

## [1.0.0-beta.2] - 2021-04-20

### Changed

- **BREAKING** Updated the method signature of `HashId::fill()` to add the validated data as the third argument. This
  change was required as the Eloquent `Fillable` interface has been modified.

## [1.0.0-beta.1] - 2021-03-30

Initial release.
