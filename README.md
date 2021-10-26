#  Link Shortener
Currently provided as a back-end for link shortener web page.

## Installation
Copy the repository to your host with "git clone".

## Configuration
- add your host url and short uri length to /src/configs/appconfig.php
- add your database credentials to /src/configs/dbconfig.php

## Usage
### Method
POST /shorten
### Request Body Parameters
"original" -> original url to shorten
### Request example
```json
POST https:\/\/yourhost.com/shorten

{
    "original": "https://google.com"
}
```

### Response example
```json
{
    "short_link": "https://yourhost/aBc12D"
}