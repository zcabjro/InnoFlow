# InnoFlow
### A VSTS Extension dedicated to:
* Project transparency
* Feedback included
* Innovation showcased

# InnoFlow - RESTful API


## 1. Authentication


### Register User

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



### Register User

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
