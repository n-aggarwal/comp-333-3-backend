# Music Rating Website Backend

 The code for the frontend is in the [Frontend](https://github.com/ananafrida/comp-333-3-frontend). 

## Overview

The project structure is as follows:

- Controller
  - Api
    - BaseController.php
    - MusicController.php
    - UserController.php
- inc
  - bootstrap.php
  - config.php
- Model
  - Database.php
  - MusicModel.php
  - UserModel.php
- index.php
- README.md

The project follows the MVC design pattern, as is evident by the project structure above. There are two different controllers and two different models- one for ratings table and one for user table. The database structure will be explained more in the section below.

## Development Enviornment

To run the app locally, you need to have XAMPP downloaded and start the servers. Note there may be a couple of places where you may have to make changes in the code. We have hardcoded the user directory because for some reason the `__DIR__` was giving errors. Namely you may have to change directory according to your system in 7 different places:

1. index.php, line 5
2. bootstrap.php, line 3
3. bootstrap.php, line 5
4. Database.php, line 3
5. Database.php, line 15
6. MusicModel.php, line 3
7. UserModel.php, line 3

You also have to have the rating and user tables under music_db database in your phpmyadmin locally as follows:

**Ratings Table Structure:**

![image](https://github.com/n-aggarwal/comp-333-2/assets/58756224/b9a8b364-56c1-4f16-ae13-442211c166cc)

**Users Table Structure:**

![image](https://github.com/n-aggarwal/comp-333-2/assets/58756224/1c47a09e-373c-417f-b1b7-97f57d8e9bc9)

Once everything is setup, you should be able to test the endpoints listed below using Postman.

## API Endpoints

Note because of `_SESSION_ID`, you need to login or register before using the music API's.

### User Endpoints

- **/user/login**
  - Type: POST
  - Parameters: `{username: string, password: string}`
  - Response: `{success: bool, message: string, username (if success): string}`
- **/user/register**
  - Type: POST
  - Parameters: `{username: string, password: string, confirm_password: string}`
  - Response: `{success: bool, message: string, username (if success): string}`
- **/user/logout**
  - Type: POST
  - Parameters: `{}`
  - Response:  {success: bool}

### Music Endpoints

- **/music/list**
  - Type: GET
  - Parameters: Not Applicable
  - Response: `{[{id: int, artist: string, song: string, username: string, rating: int},...]}`
- **/music/create**
  - Type: POST
  - Parameters:`{artist: string, song: string, rating: string}`
  - Response: `{artist: string, song: string, rating: int}`
- **/music/read**
  - Type: GET
  - Parameters: Not applicable
  - Response: `{id: int, artist: string, song: string, username: string, rating: int}`
- **/music/update**
  - Type: POST
  - Parameters: `{id: int, artist: string, song: string, rating: string}`
  - Response: `{success: bool}`
- **/music/delete**
  - Type: POST
  - Parameters: `{id: int}`
  - Response: `{success: bool}`

## Conclusion

This project was built by [@n-aggarwal](https://github.com/n-aggarwal) and [@ananafrida](https://github.com/ananafrida). Please feel free to reach out to us if you have any questions or concerns.
