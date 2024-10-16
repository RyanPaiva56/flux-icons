# Flux Icons

This is a laravel package to customize the icons for [Livewire Flux](https://github.com/livewire/flux). It builds the icons from various vendors into a `flux:icon` component.

## Installation

Generally you want to install this package only in your local development environment and build and publish the icons you need.

```cmd
composer require --dev ympact/flux-icons
```

## Building icons

You will need to build the icons yourself once the package is installed. This can be done using the artisan command `flux-icons:build` you can optionally pass the vendor name as the first argument.
In case you did not provide this, the script will ask you.

```cmd
php artisan flux-icons:build tabler --icons=confetti,confetti-off
```

### Options

| Option          | Description                                                                                        |
|-----------------|----------------------------------------------------------------------------------------------------|
| `--icons=`      | The icons to build (single or comma separated list). Cannot be used in combination with `--all`. |
| `-m\|--merge`   | Merge the icons listed in the `--icons` options with the icons defined in the config. Cannot be used in combination with `--all`. |
| `-a\|--all`     | Build all icons from the vendor. Note: this might generate over thousands of files.                |
| `-v\|--verbose` | Show additional messages during build. |

The artisan script will try to install the icon package using `npm install`. Right after it will try to convert all icons into a flux icon blade component.

### Usage

Since this package publishes all icons to `resources/views/flux/icon/{vendor}/` you can simply use the Blade convention of referencing the icons within your flux:icon component. So for example:

```html
<flux:icon.tabler.confetti />
```

or

```html
<flux:icon name="tabler.confetti-off"/>
```

## Note on icon variants

Due to the way the flux icon component is made, it requires 4 variants: an outline and a solid of three sizes (24, 20, 16).
For the first version of this Flux Icons package the source icons are treated as follows:

- In case there is only one solid size variant in the source package, it will use the same svg for all three size variants. Generally the svg will be scaled by the browser.
- In case there is no solid variant, it will use the outline variant as the solid variant.
- In case the solid variant does not have an outline variant, the icon is not exported at all.

## Publish config

You can publish the config file to adjust settings for a specific vendor or add your own vendor. In case you add your own vendor, please share or make a PR so others can use it too!

```cmd
php artisan vendor:publish --tag=flux-icons-config
```

## Support

- Fluent UI Icons
- Google Material Design Icons
- Tabler Icons

## Advanced configuration

| Option     | Valaue     | Description                                                                 |
|------------|------------|-----------------------------------------------------------------------------|
| `icons`    |  `null` or `['vendorName' => ['icon-name', ...] ]` | A list of icons that will be build/updated by default in case no icons are passed to `flux-icons:build` command.  |
| `default_stroke_wdith` | `float` | For outline icons a default stroke width can be configured. The default Flux Heroicons uses a width of 1.5. |

### Vendor specific configuration

The vendor specific configuration sits within the `vendors` key. Each vendor should have a key. That key will be used as directory name when exporting the icons.

```php
'vendors' => [
    'tabler' => [
        'vendor_name' => 'Tabler',
        'package_name' => '@tabler/icons',
        'source_directories' => [ 
            //...
        ]
    ]
 ]
```

| Option     | Value     | Description                                                                 |
|------------|-----------|-----------------------------------------------------------------------------|
| `vendor_name`    |  `string` | Human readable name of the vendor.  |
| `namespace`      | `string`  | The namespace for the Flux icon, in case omitted, the vendor name will be used. |
| `package_name` | `string` | The npm package that should be installed to retrieve the icons. |
| `source_directories.outline` | `array\|string` | The directory in which the vendors outline icons reside. For specific options see below. |
| `source_directories.solid` | `array\|string` | The directory in which the vendors solid icons reside. For specific options see below. |
| `transform_svg_path`    |  `callable` | A callback to transform the SVG path data. Takes a single parameter: the SVG path string. |
| `change_stroke_width`   |  `callable` | A callback to determine the whether the stroke width should be changed on this icon. |

#### Source directories

In case the vendor uses a prefix or suffix for the icons, we want to configure it here to determine the basename of the icon and make them more accessible in flux.
For both source directories (outline and solid), an optional `filter` callback can be defined to indicate whether a file in the directory should be used as outline or solid respectively.

```php
[
    'dir' => 'node_modules/vendor/icons/...',
    'prefix' => null,
    'suffix' => null 
    'filter' => function(){ }
]
```

For the **solid** icons, optionally callbacks can be defined on `dir`, `prefix` and `suffix` to adjust these according to the icon size.

```php
'solid' => [ 
    [
        'dir' => 'node_modules/vendor/icons/icons/filled', 
        'prefix' => null, 
        'suffix' => fn($size) => "-{$size}", // adds either -24 -20 and -16 as suffix to the icon
    ],
],
```

#### Transform icons

The configuration file provides two callback options to allow for adjustments on the paths and stroke width of specific icons.
See the configuration for the Tabler icons as example how to use this.

```php
'vendors' => [
    'tabler' => [
        // ...

        /**
         * @param string $variant (solid, outline)
         * @param string $iconName base name of the icon
         * @param collection<SvgPath> $svgPaths
         */
        'transform_svg_path' => function($variant, $iconName, $svgPaths) {
        // Your transformation logic here
        },

        /**
         * @param string $iconName base name of the icon
         * @param float $defaultStrokeWidth 1.5 or the default set in config option `default_stroke_wdith`
         * @param collection<SvgPath> $svgPaths
         */
        'change_stroke_width' => function($iconName, $defaultStrokeWidth, $svgPaths) {
            // Your filtering logic here
        },
    ]
]
```

## Additional icons

This package also provide some custom icons that can be published:

They can be published using

```cmd
php artisan vendor:publish --tag=flux-icons-icons
```

- An empty icon, can be useful for simple logic in your blade or components:
  
  ```html
  <flux:icon name="{{ $icon ?? 'flux-icons.empty' }}" />
  ```

- A placeholder avatar icon, usin an icon or initials

  ```html
  <flux:icon.flux-icons.avatar-placeholder name="Maurits Korse" color="green" />
  <flux:icon.flux-icons.avatar-placeholder icon color="green" />
  ```

  This icon has additional properties:
  - **icon** `(void|string)`: uses the Heroicon user icon as image, optionally a custom icon can be provided.
  - **name** `(string)`: instead of an icon two initials of a name will be shown. You can pass the full name (Maurits Korse) or just the initials (MK)
  - **color** `(string)`: colorizing the icon using the same as Flux badges

## Roadmap

- Add/Improve command for updating/rebuilding icons
- Adding more vendors
- Add support for 
