# Changelog

## 2018-02-08 1.04 Release

### Fixed
- major issue fixed (missing semicolon) in PaymentChargedBack event.
- major issue fixed (missing use statement for PaymentFailed) in DispatchPaymentStatusChangeEvent.

### Added
- tests for DispatchPaymentStatusChangeEvent

## 2017-07-14
This change substitutes the omnipay/mollie package for the official mollie/molli-api-php client.

### Fixed
- PSR-2: CreateTestModelsTable.php namespace
- PSR-2: IsPayableTrait is now in camel caps
- other automated PSR-2 fixes

### Added
- mollie/mollie-api-php package

### Removed
- omnipay/mollie package

## 2017-06-27

### Fixed
- major issue fixed (missing semicolon) in PaymentChargedBack event.
- minor fixes suggested by Scrutinizer

### Added
- Nothing

### Removed
- lower case folders src/events and src/listeners.

## 2017-06-17

### Fixed
- FakePaymentGateway only dispatches PaymentUpdated event if the payment record has actually been changed.

### Added
- Tests for PaymentUpdated event behaviour.

### Removed
- Docs folder (including roadmap.md)

## 2017-06-07

### Fixed
- Improved Readme file:
    - installation
    - usage
    - events
    - IsPayableTrait
    - eurocents
    - mollie api key for testing
    - nice title
    - removed scrutinizer links
    - updated travis link

### Added
- IsPayableTrait
- Travis config

## 2017-06-06

### Fixed
- PSR-2 code compliancy

### Added
- graham-campbell/testbench test suite
- docs folder (with Roadmap)
- FakePaymentGatewayTest
- MolliePaymentGatewayTest

## 2017-05-24

### Fixed
- Set the package variables (author name etc.) correctly.
