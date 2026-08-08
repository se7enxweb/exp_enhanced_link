# exp_enhanced_link

7x Enhanced Link eZ4 datatype for Exponential 6.x.

Copyright (C) 1998 - 2026 7x. All rights reserved.
Licensed under the GNU General Public License v2.0 (or any later version).

- Version: 1.0.0
- GitHub: https://github.com/se7enxweb/exp_enhanced_link

## About

`exp_enhanced_link` is an eZ Publish 4 datatype implementation of the
`ngenhancedlink` field type. It is ported from the Netgen
`ibexa-fieldtype-enhanced-link` package so that content classes and demo data
using `ngenhancedlink` can be installed and rendered on Exponential 6.x.

## Features

- **Internal and external links** — stores either a content/location id or an
  external URL.
- **Target modes** — `link`, `link_new_tab`, `embed` and `modal`.
- **Label and suffix support** — optional custom label and URL suffix.
- **JSON storage** — matches the Netgen enhanced-link persistence format
  (`data_text` JSON, `sort_key_string` reference).
- **Package import/export** — implements `fromString()` / `toString()` for eZ
  package handling.
- **Search indexing** — `metaData()`, `sortKey()` and `title()` are wired.
- **Admin edit/view templates** — basic templates for the eZ4 admin interface.

## Requirements

- Exponential 6.x / eZ Publish 4.5+ (tested with PHP 8.1+).
- PHP `json` extension.

## Quick start

```bash
# Activate the extension, see INSTALL.md for details
php bin/php/ezpgenerateautoloads.php -e
php bin/php/ezcache.php --clear-all --allow-root-user
```

## License

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later
version.

See http://www.gnu.org/licenses/ for the full license text.

## Author

Developed and maintained by **7x** — https://se7enx.com
