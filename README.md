# TextExtendAI

Extend the text in your WordPress editor using Mistral AI.

Required:

1. WordPress
2. Mistral AI API Key

# Installation

1. Download this repository as a ZIP file.
2. On your WP blog to go Admin / Plugins / Add New Plugin and choose UPload Plugin
3. Select the zip file you just downloaded
4. Click Install Now
5. Activate the Plugin

## Upgrade

Repeat the installation instructions.  WP will notice you are upgrading and will prompt you to confirm


# Set UP

The first time you try to use the plugin it wil ask for an API key, that will be one from Mistral platform.

It costs money, but not that much so make sure you set a suitable budget (like $1 / month) and you will be fine.

# Usage

In the editor put your cursor at the end of, or after, any block where you want to write more.

Press Ctrl-Shift-E for "Extend".

As noted the first time it will ask for a key, that key will be saved locally on your browser in localstorage and will not be saved anywhere else. So make a record of it.

Any subsequent times you press Ctrl-Shift-E then the text you have written so far is sent to Mistral and the response is inserted.

It takes about 2 seconds.


# Changelog

1.3 - Handle multiple paragraphs and or headers being returned by the API
