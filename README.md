# Simple User Management System

This is a Simple User Management System implemented in RAW PHP and REST API. It provides user authentication and management functionalities with MySQL as the database. The system supports two types of roles: Admin and User.

## Features

### Roles and Permissions

- **Admin**
  - Create new users
  - Delete users
  - Update user information
  - View list of all users
- **User**
  - Register an account
  - Login to the system
  - Update their own profile (username and password)
  - Delete their own account

Only admin users can assign roles to other users. Regular users do not have permissions to change their own or other users' roles.

### Endpoints

#### Authentication

1. **Login**
   - URL: `/login.php`
   - Method: `POST`
   - Request Body:
     ```json
     {
       "username": "your_username",
       "password": "your_password"
     }
     ```
   - Response:
     ```json
     {
       "status": 200,
       "message": "Login Successful",
       "user": {
         "id": 1,
         "username": "your_username",
         "role_id": 1
       }
     }
     ```

2. **Register**
   - URL: `/register.php`
   - Method: `POST`
   - Request Body:
     ```json
     {
       "username": "new_username",
       "password": "new_password",
       "role_id": 2  // Optional, default is 2 (User)
     }
     ```
   - Response:
     ```json
     {
       "status": 201,
       "message": "User Registered Successfully",
       "user": {
         "id": 2,
         "username": "new_username",
         "role_id": 2
       }
     }
     ```

#### User Management (Admin Only)

1. **Create User**
   - URL: `/add.php`
   - Method: `POST`
   - Request Body:
     ```json
     {
       "username": "new_username",
       "password": "new_password",
       "role_id": 2
     }
     ```
   - Response:
     ```json
     {
       "status": 201,
       "message": "User Created Successfully"
     }
     ```

2. **Update User**
   - URL: `/update.php?id={user_id}`
   - Method: `PUT`
   - Request Body:
     ```json
     {
       "username": "updated_username",
       "password": "updated_password",
       "role_id": 1  // Optional, only Admin can update role
     }
     ```
   - Response:
     ```json
     {
       "status": 200,
       "message": "User Updated Successfully"
     }
     ```

3. **Delete User**
   - URL: `/delete.php?id={user_id}`
   - Method: `DELETE`
   - Response:
     ```json
     {
       "status": 200,
       "message": "User Deleted Successfully"
     }
     ```

4. **List Users**
   - URL: `/index.php`
   - Method: `GET`
   - Response:
     ```json
     {
       "status": 200,
       "message": "User List Fetched Successfully",
       "data": [
         {
           "id": 1,
           "username": "user1",
           "role_id": 1
         },
         {
           "id": 2,
           "username": "user2",
           "role_id": 2
         }
       ]
     }
     ```

#### User Management (Users)

1. **Update Profile**
   - URL: `/update.php?id={user_id}`
   - Method: `PUT`
   - Request Body:
     ```json
     {
       "username": "updated_username",
       "password": "updated_password"
     }
     ```
   - Response:
     ```json
     {
       "status": 200,
       "message": "User Updated Successfully"
     }
     ```

2. **Delete Profile**
   - URL: `/delete.php?id={user_id}`
   - Method: `DELETE`
   - Response:
     ```json
     {
       "status": 200,
       "message": "User Deleted Successfully"
     }
     ```

### Database Schema

The database for this system contains a single table `users` with the following structure:

```sql
CREATE DATABASE user_management;
USE user_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL
);
```                    
                                                                                                                            
  

### Setup

- Clone the repository
- Create the db table
- Update the database configuration in includes/db.php:                                         
```php
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}                                                                                
```                    
                                          
  
  

Author: Anam Ibn Jafar  
Email: [anamibnjafar@gmail.com](mailto:anamibnjafar@gmail.com)                                        
  
  

