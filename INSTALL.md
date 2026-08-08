# Installing exp_enhanced_link

## Requirements

- Exponential 6.x / eZ Publish 4.5+
- PHP 8.1+
- PHP `json` extension

## Installation

### Manual install

1. Copy or checkout the extension into `extension/exp_enhanced_link`.

2. Activate the extension in `settings/override/site.ini.append.php`:

```ini
[ExtensionSettings]
ActiveExtensions[]=exp_enhanced_link
```

3. Register the datatype in `settings/override/content.ini.append.php` (or rely
   on the extension's own `settings/content.ini.append.php` if your active
   extension INIs are merged):

```ini
[DataTypeSettings]
ExtensionDirectories[]=exp_enhanced_link
AvailableDataTypes[]=ngenhancedlink
```

4. Regenerate autoloads and clear caches:

```bash
php bin/php/ezpgenerateautoloads.php -e
php bin/php/ezcache.php --clear-all --allow-root-user
```

### Composer install

```bash
composer require se7enxweb/exp_enhanced_link
```

Then activate the extension and regenerate autoloads as described above.

## Usage

### Content classes

After activation, `ngenhancedlink` appears in the eZ admin datatype list when
editing or creating content classes. Content classes can define allowed link
types, targets, and other settings through the class attribute `data_text5` field
in the same JSON format used by the Netgen Ibexa field type.

### Object data format

The object attribute stores the link value in `data_text` as JSON:

```json
{
    "id": 409,
    "label": "Read more",
    "type": "internal",
    "target": "link_new_tab",
    "suffix": "#details"
}
```

For external links:

```json
{
    "id": 362,
    "label": "Visit site",
    "type": "external",
    "target": "link"
}
```

The actual external URL is read from the `sort_key_string` column (or from the
`url` key in the JSON, if present). The stored `id` for an external link may be
an `ezurl` row id; the datatype falls back to the `sort_key_string` when the
`ezurl` lookup cannot resolve it.

### Rendering

In templates the value is available through `attribute.content` as an
`ngEnhancedLinkValue` instance:

```tpl
{def $link = $attribute.content}
{if $link.isTypeInternal}
    <a href={concat('content/view/full/', $link.reference)|ezurl}>{$link.label}</a>
{elseif $link.isTypeExternal}
    <a href="{$link.reference|wash( xhtml )}">{$link.label|wash( xhtml )}</a>
{/if}
{undef $link}
```

The bundled view template `design/standard/templates/content/datatype/view/ngenhancedlink.tpl`
provides a default rendering for front-end use.

## License

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later
version.

See http://www.gnu.org/licenses/ for the full license text.
