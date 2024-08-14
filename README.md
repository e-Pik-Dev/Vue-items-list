# Vue items list

This is a test Drupal module that adds a block (named Vue items list) which renders Vue 3 / Typescript component
compiled by Vite.
The Vue component fetches and lists data from a predefined API endpoint (configurable).

## Installation

1. Use composer to install the module
   ```shell
   composer require epik-dev/vue-items-list
   ```

2. Once installed, to enable the module, go to the `Extend` page (`Manage->Extend`), find
   `Vue items list (Test Module)`, tick a checkbox in front of it and click `Install` button


3. Place the block via at Block layout page:

    1. Log in as an administrator on your Drupal site
    2. Navigate to `Manage->Structure->Block layout`, or try the
       link `{drupal_site_basepath}/admin/structure/block`
    3. Select a block region where you want to place the block, e.g. Header (block region names are specific to themes)
       and click `Place block` button
    4. In the block list that opens, find the `Vue items list` block or type it in the filter textfield
    5. Once found, click `Place block` button next to it, configure options if necessary

## Configuration options

When placing or configuring a block, two configuration options are available:

- **endpoint**: API url to make a GET request
- **maxItemsNo**: Maximum number of items to display

## Technical info

Vue component is build as native ES module employing Vite, which compiles Vue SFC component into separate index.js and
index.css are files placed in `/component`
folder loaded, which are loaded by the module to render its content.   
Component source code folder is `/vite/src`

However, for this build to work, this module loads a separate Vue esm version from CDN.

Modern Vue development best practices include using Vue SFCs (Single File Components), TypeScript, and Vite
tooling.

Vue SFCs are files that can contain a block of code for scripts, templates, and styles that combine and encapsulate
all aspects of a component.

To use Typescript for Vue, a transpilation step is required, converting Typescript -> JS.

Vite handles all of the above. Moreover, it accelerates development with HMR and provides rich build options.

## Requirements

* Drupal 8.x, 9.x, 10.x.

