# comp-333-3-backend

**NOTE** Testing instructions are at the bottom of the Readme. 

This repository contains the backend code for COMP333 Homework 3 (Wesleyan University). The code for the frontend is in the [comp-333-3-frontend](https://github.com/ananafrida/comp-333-3-frontend) owned by [@ananafrida](https://github.com/ananafrida). 

The project/homework will be complete by Friday 10am. We plan to utilize 2 of our 5 late days for this submission.<br />

## Overview

As stated before the given repository contains code for the backend. The project structure is as follows:

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


## Testing

For testing this backend API, we used PHPUnitTest as per the homework 5 requirements. Before you run the test, please at make sure your database meets the given requirements. This are essential for some of the tests.: <br />
<br />
1. In the users table add the following user:
```json
{
  "username": "test1",
  "password": "1234567890",
}
```
2. Do not have a user in the `users` table with the username `test2`. We will use this username to test registration. <br />
3. Have the following songs in the `ratings` table of the database
```json
{
  'id' : 55,
  'username' : 'test1'
  'artist' : 'artist_1',
  'song' : 'song_1',
  'rating' : '1',
}

{
  'id' : 56,
  'username' : 'test1'
  'artist' : 'artist_2',
  'song' : 'song_2',
  'rating' : '2',
}
```
4. Do not have a song with the following info `{ 'artist' => 'test_artist_4', 'song' => 'test_song_4', 'rating' => '4',}`.  We will create this in the test.

That's it! Now run the commands: 
```bash
composer install
./vendor/bin/phpunit tests
``` 
in the root directory
and the test should run. Note that you should have the composer in path for this command to work.

**NOTE**: All of my functions send a 200 OK request whenever a request is successful. Thus, I won't be checking for 201 but for 200. Also we didn't have a list users function in our backend, because it is not appropriate for the application. Instead we have a list function for music which I test instead.

## Conclusion

This project was built by [@n-aggarwal](https://github.com/n-aggarwal) and [@ananafrida](https://github.com/ananafrida). Please feel free to reach out to us if you have any questions or concerns.
