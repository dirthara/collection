# Security Policy

## Supported versions

| Version | Status |
| --- | --- |
| 0.1.x | Active development; unreleased |
| Older | Unsupported |

While the package is pre-1.0, only the latest release line receives fixes.

## Reporting a vulnerability

Report vulnerabilities privately using GitHub's
[Report a vulnerability](https://github.com/dirthara/collection/security/advisories/new)
form. Do not disclose vulnerabilities in public issues or pull requests.

Include the affected version or commit, PHP version, a minimal reproduction,
and the impact and conditions needed to trigger the issue. Maintainers will
acknowledge and assess the report. Confirmed fixes are published with an
advisory crediting the reporter unless they prefer otherwise.

## Scope

Report security issues in collection operations, exception handling, or the
development configuration. Collection values are not included in missing-key
exception messages or context; the missing key is included for diagnosis.

Immutable collections protect their entries from replacement or removal through
the API. They do not freeze stored objects or deep-copy nested references.
Collections do not sanitize values, enforce authorization, or validate entity
types. Applications remain responsible for those boundaries.

Bugs in PHP or third-party dependencies should also be reported upstream.
Application code and the sensitivity of data an application chooses to store
are the application's responsibility.
