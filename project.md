### Steps we took to create this project

# 1- Creating OCR Micro Service Using Lumen

- In this step we initialized a project using Lumen (A microservice version or laravel)
- We added only 1 route and 1 function that will accept a PDF file and return the text in that file
    The function works by first converting the PDF file to Image and then using the Tesseret Library
    we process the image and extract the text inside it

    * The function saves the pdf file , converts it to image and once the text process is done those files
    are deleted to save space

# 2- Creating an API to handle the users requests Using Laravel
- In this project we created multiple functions that do different operations

## Register Function
- This function collects the user data and check if user already exist in the database
    - if the data are validated and user do not exist, it will register new user and generate token
    - if the data is incorrect or user already exist, it will return an error message

## Login Function
- This function accepts a user email and password, and compares them to the users data in the database
    - if the data is correct, it will generate a bearertoken and send it back to the user
    - if the data is incorrect, it will return an error message

## BearerAuth Middleware
- in this step we created a middleware to process all API calls and verify that the user is authenticated
    - First we created the middleware class in app/Http/Middleware/BearerAuth.php
        This middleware will process any request made to the API endpoint and check if the token is valid
    - Second we defined the route in our app/Http/Kernel.php

## File Upload
- For this we only needed to create one route and one controller

# Route is a post route called upload-file
# Controller (app/Http/Controllers/UploadController.php)
    This controller only has one function that will accept the uploaded file and validate it
    if the file passes validation it will be sent to the OCR Microservice and once data is returned
    from the Microservice it will be saved in the database and also returned to the user/client

## API Endpoints

# Registration
- /api/register
    Params: name | email | password

# Login 
- /api/login
    Params: email | password

# API File Upload
- /api/upload-file
    Authorisation: Bearer Token
    - Body 
        Key: File[] (type: file)
