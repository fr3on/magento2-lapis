# Magento 2 LAPIS

![Build Status](https://github.com/fr3on/magento2-lapis/actions/workflows/tests.yml/badge.svg)
![License](https://img.shields.io/github/license/fr3on/magento2-lapis)
![Packagist Downloads](https://img.shields.io/packagist/dt/fr3on/magento2-lapis)

Automatic LAPIS (Lifecycle & Protocol Intent Specification) declarations for Magento 2 REST APIs.

## Mission

This composer package automatically generates LAPIS declarations from a live Magento 2 installation. It introspects what Magento already knows—Order statuses, Transitions, State-to-Status mappings—and surfaces it in a form every system can consume.

**Read-only.** This module never writes to Magento's own tables. It only reads.

## Installation

```bash
composer require fr3on/magento2-lapis
bin/magento module:enable Fr3on_Lapis
bin/magento setup:upgrade
```

## Features

- **RLD Generator:** Produces valid YAML/JSON for Order, Invoice, Credit Memo, Shipment, and Cart.
- **REST Response Headers:** Every API response includes `X-LAPIS-State` and `X-LAPIS-Transitions`.
- **Admin Visualizer:** Interactive state machine graphs at `System > LAPIS`.
- **CLI Tools:** Generate and validate declarations from the terminal.

## Usage

### CLI Commands

```bash
bin/magento lapis:generate    # Generates YAML files in var/lapis/
bin/magento lapis:validate    # Validates generated files against schema
```

### Extending via `lapis.xml`

You can declare custom transitions or override heuristics by creating a `lapis.xml` in your module's `etc` directory:

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <resource id="order">
        <transition from="processing" to="custom_state" via="myCustomMethod()" actor="admin"/>
    </resource>
</config>
```

## Dashboard

Navigate to **System > LAPIS** in the Magento Admin to see the live state machine of your store.

## License

MIT
