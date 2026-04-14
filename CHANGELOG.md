# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-04-14

### Added
- **Core Readers:** Introspection for Order, Invoice, and Credit Memo database tables.
- **Inferred Readers:** Logical inference for Shipment and Cart lifecycles.
- **RLD Generator:** YAML and JSON generation for Resource Lifecycle Declarations.
- **REST Headers:** Automatic injection of `X-LAPIS-State` and `X-LAPIS-Transitions` in REST API responses.
- **Admin visualizer:** SVG-based state machine dashboard at `System > LAPIS`.
- **CLI Commands:** `lapis:generate` and `lapis:validate`.
- **Extensibility:** `lapis.xml` system for custom transition declarations.
- **Caching:** Dedicated `lapis_cache` type for high-performance introspection.
- **Validation:** JSON Schema for RLD v0.1.
