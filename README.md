# GameQuest
---
This project is an on-going development for a game recommendation system utilizing user's online game platform account information such as Steam. As long as your profile is public, our website will utilize your game library information and recent playtime to recommend you games perfectly tailoroed to your tastes.
In case you are looking for something more specific, use our genre tags in order to control exactly what you want to see and play.

## Features

- [Steam API](https://developer.valvesoftware.com/wiki/Steam_Web_API#GetPlayerSummaries_.28v0001.29) integration for retrieving user's Steam profile.
- [IGDB](https://api-docs.igdb.com/) Integration using [PHP Wrapper](http://enisz.github.io/igdb). This is specifically used for locating games users may be interested in.
- If you find a game you are interested in, feel free to wishlist it and you will always be able to come back to it. Be sure to remember your login information!

## Screenshots

![GameQuest](images/screenshots/frontPage.png)
![GameQuest](images/screenshots/register.png)
![GameQuest](images/screenshots/gamePage.png)

## Tech Stack

**Client:** HTML/CSS, JavaScript
**Extras:** JQuery, Steam API, IGDB
**Server:** PHP

## Run Locally

Clone the project

```bash
  git clone https://github.com/j-muell/game-recommendation-app.git
```

If you plan to run this project locally, there are a few things to note:

1. You will need to run the SQL Scripts in the extras contect.

2. In any location which there is a connection to the localhost database, there is a password involved for the root user. If you are using xampp to run this project, you will need to configure your root user to have the password rootPassword. In order to do this, follow the steps below.
   2.1 Inside of xampp, click config under Apache and then 'phpMyAdmin (config.inc.php)'
   2.2 In this file, change the line as follows `$cfg['Servers'][$i]['password'] = 'rootPassword';`
   2.3 After this, navigate to the phpMyAdmin panel. From here, select 'User accounts' and 'root', 'localhost' with Global Privileges set to 'ALL PRIVILEGES'.
   2.4 Select the change password tab and follow the directions.

3. You should now be able to run the project.

4. Do not change the directory of any files, especially anything server-side as the API's require access to specfic config.ini files which must stay where they are.

## Project Structure

The root folder of GameQuest holds 7 main directories

- Components
  - This directory is designed to have every visual element you can see on our website.
  - Whether it is from the gameSite or the landing page, it exists within this directory and potential subdirectories.
- Extras
  - Anything extra that is unrelated to the main app, such as SQL Queries for our database.
- Functions
  - This directory is designed to have any files that are purely including for function purposes.
  - For example, our SteamAPI Wrapper that was overhauled by our team, or the IGDB PHP Wrapper. Both are linked above in the readme.
- Images
- Includes
  - Includes is dedicated to specifically backend/server php files, and generally anything to do with databse work. It also utilizing the functions directory files.
- Scripts
  - This directory is for most of the javascript in our application, aside from some minor in-line javascript.
- Styles
  - Any stylesheets in our application.

 If you have any questions please contact me.

 @2024
