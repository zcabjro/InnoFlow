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

| Parameter   | Notes     |
| ------------|-----------|
| email       | A valid email address |
| password    | Any string consisting of 10 characters minimum|

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful registration |
| 422 | <ul><li>Email and/or password is missing</li><li>Email and/or password has incorrect format</li><li>Email is already taken</li></ul> |

**Example Request**

http://innoflow.app/api/register?email=andreas@gmail.com&password=123456789Hello!
<br><br>



### Login User:

**Route**

`POST` api/login

| Parameter   | Notes     |
| ------------|-----------|
| email       | An email of a registered user |
| password    | The password of the registered user |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful Login |
| 401 | Email and/or password are incorrect |
| 422 | Email and/or password is missing |

**Example Request**

http://innoflow.app/api/login?email=andreas@gmail.com&password=123456789Hello!
