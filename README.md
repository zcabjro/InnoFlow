# Overview
### A VSTS Extension dedicated to:
* Project transparency
* Feedback included
* Innovation showcased


# RESTful API



## 1. Authentication

### Register User:

**Route**

`POST` api/register

| Parameter   | Type      | Notes     |
| ------------|-----------|-----------|
| email       | string    | Must be a valid email address |
| password    | string    | Must be string consisting of at least 10 characters |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful registration |
| 422 | <ul><li>Email or password parameter are missing</li><li>Email or password parameter have incorrect format</li><li>Email is already taken</li></ul> |

**Example Request**

http://innoflow.app/api/register?email=andreas@gmail.com&password=123456789Hello!
<br><br>


### Login User:

**Route**

`POST` api/login

| Parameter   | Type      | Notes     |
| ------------|-----------|-----------|
| email       | string    | An email of a registered user |
| password    | string    | The password of the registered user |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful Login |
| 401 | Email or password parameter are incorrect and do not match with any registered user |
| 422 | <ul><li>Email or password parameter are missing</li><li>Email or password parameter have incorrect format</li></ul> |

**Example Request**

http://innoflow.app/api/login?email=andreas@gmail.com&password=123456789Hello!
<br><br>



### Logout User:

**Route**

`GET` api/logout

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful Logout |
| 404 | Cookie token is missing. No user was logged in to begin with. |

**Example Request**

http://innoflow.app/api/logout
<br><br>



## 2. Innovations

### Store an innovation:

**Route**

`POST` api/innovations

| Parameter   | Type      | Notes     |
| ------------|-----------|-----------|
| email       | string    | An email of a registered user |
| password    | string    | The password of the registered user |
| code        | text      | A code snippet encoded in base 64 |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful storage |
| 401 | Email or password parameter are incorrect and do not match with any registered user |
| 422 | <ul><li>Email, password or code parameter paramter are missing</li><li>Code parameter is not properly encoded</li></ul> |


**Example Request**

http://innoflow.app/api/logout
<br><br>
