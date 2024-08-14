# Vue items list

This is a test Drupal module that adds a `Vue items list` block which uses compiled Vue 3 SFC / Typescript component
to render its content.
The Vue component fetches and lists data from a predefined API endpoint (configurable).

## Installation

1. Use composer to install the module
   ```shell
   composer require epik-dev/vue-items-list
   ```

2. Once installed, to enable the module, go to the `Extend` page (`Manage->Extend`, `{drupal_site}/admin/modules`), find
   `Vue items list (Test Module)`, tick a checkbox in front of it and click `Install` button


3. Place the block via at Block layout page:

    1. Log in as an administrator on your Drupal site
    2. Navigate to `Block layout` page (`Manage->Structure->Block layout`, `{drupal_site}/admin/structure/block`)
    3. Select a block region where you want to place the block, e.g. Header (block region names are specific to themes)
       and click `Place block` button
    4. In the block list that opens, find the `Vue items list` block or type it in the filter textfield
    5. Once found, click `Place block` button next to it, configure options if necessary

## Configuration options

When placing or configuring a block, two configuration options are available:

- **API endpoint**: The URL of the API endpoint to retrieve data
- **Maximum items to show in the list**: Maximum number of data items displayed in the block

## Technical info

Vue component is build as native ES module employing Vite, which compiles Vue SFC component into separate index.js and
index.css files, which are loaded by the module to render its content.

Component source code folder is `/vite/src`
Component build code folder `/component`

However, for this build to work, this module loads a separate Vue esm version from CDN, its url is specified under
`vue.cdn.esm` key in `vue_items_list.libraries.yml`.

Modern Vue development best practices include using Vue SFCs (Single File Components), TypeScript, and Vite
tooling.

Vue SFCs are files that can contain a block of code for scripts, templates, and styles that combine and encapsulate
all aspects of a component. To use Typescript for Vue, a transpilation step is required, converting Typescript -> JS.
Vite handles all of the above, moreover, it accelerates development with HMR and provides rich build options.

## Requirements

* Drupal 9.x, 10.x.
* PHP >= 8.0
