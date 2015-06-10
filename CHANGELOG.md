# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased] - (unreleased)
### Added
- [apigen](http://www.apigen.org) docs
- [PHPUnit](https://phpunit.de) test coverage
- [Travis CI](https://travis-ci.org) integration
- README meta and instructions
- CHANGELOG.md

### Changed
- Ensure compliance with [PSR-2](http://www.php-fig.org/psr/psr-2/)

### Removed
- phpDocumentor docs

### Fixed
- Added missing invocation parameters on call to `Block::getLastAddress` from `Block::getBroadcastAddress`


## [1.1.0] - 2015-03-30
### Changed
- Accept dot-notated string IP address with optional CIDR slash prefix as first parameter to JAAulde\IP\V4\Block constructor.
- Updated PHP Docs
- Regenerated PHP Documentor docs


## 1.0.0 - 2015-03-27
### Added
- Everything

[unreleased]: https://github.com/JAAulde/php-ipv4/compare/v1.1.0...master
[1.1.0]: https://github.com/JAAulde/php-ipv4/compare/v1.0.0...v1.1.0
