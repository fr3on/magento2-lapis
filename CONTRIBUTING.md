# Contributing to Magento 2 LAPIS

Thank you for your interest in contributing to LAPIS! We welcome all contributions that help improve the readability and accessibility of Magento 2 state machines.

## How to Contribute

1.  **Fork the repository.**
2.  **Create a feature branch:** `git checkout -b feat/my-new-feature`.
3.  **Run tests & mess detector:** Ensure `vendor/bin/phpunit` and `vendor/bin/phpmd` pass without errors.
4.  **Commit your changes:** Follow standard git commit conventions.
5.  **Push to the branch:** `git push origin feat/my-new-feature`.
6.  **Create a Pull Request.**

## Coding Standards

- Follow [Magento 2 Coding Standards](https://devdocs.magento.com/guides/v2.4/coding-standards/bk-coding-standards.html).
- Every new reader should implement `StateReaderInterface`.
- Use the `lapis_cache` type for any heavy introspection.

## Reporting Bugs

Please use the GitHub Issue tracker to report bugs. Include:
- Your Magento version.
- Step-by-step instructions to reproduce the issue.
- Expected vs. actual behavior.

Happy coding!
