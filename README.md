# TextExtendAI

Extend the text in your WordPress editor using Mistral AI.

Required:

1. WordPress
2. Mistral AI API Key

# Installation

1. Download the latest release version a [ZIP file](https://github.com/raymondclowe/TextExtendAI/releases/download/latest/TextExtendAI.zip).
2. On your WP blog to go Admin / Plugins / Add New Plugin and choose Upload Plugin
3. Select the zip file you just downloaded
4. Click Install Now
5. Activate the Plugin

## Upgrade

Repeat the installation instructions.  WP will notice you are upgrading and will prompt you to confirm


# Set UP

Go to the Dashboard / Settings / TextExtendAI and enter your API key and choose your model.

That will be one from Mistral platform. [Signup here](https://auth.mistral.ai/self-service/registration/browser).

It costs money, but not that much so make sure you set a suitable [monthly usage limit](https://console.mistral.ai/billing/#:~:text=next%20month%20begins.-,Usage%20limit%20(%E2%82%AC)%20*,-Update%20limit) (like €1 / month) and you will be fine.

# Usage

In the editor put your cursor at the end of, or after, any block where you want to write more.

Press Ctrl-Shift-E for "Extend".

As noted the first time it will ask for a key, that key will be saved locally on your browser in localstorage and will not be saved anywhere else. So make a record of it.

Any subsequent times you press Ctrl-Shift-E then the text you have written so far is sent to Mistral and the response is inserted.

It takes about 2 seconds.


# Changelog

1.3 - Handle multiple paragraphs and or headers being returned by the API
