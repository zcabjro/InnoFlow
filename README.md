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
| password    | string    | A strong password (min 10 characters) |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful registration |
| 422 | <ul><li>email or password parameter are missing</li><li>email or password parameter have incorrect format</li><li>email is already taken</li></ul> |

**Sample Request**

`POST` http://innoflow.app/api/register

```json
{
    "email" : "andreas@gmail.com",
    "password" : "1234567890"
}
```
<br>



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
| 401 | email or password parameter are incorrect and do not match with any registered user |
| 422 | <ul><li>email or password parameter are missing</li><li>email or password parameter have incorrect format</li></ul> |

**Sample Request**

`POST` http://innoflow.app/api/login

```json
{
    "email" : "andreas@gmail.com",
    "password" : "1234567890"
}
```
<br>



### Logout User:

**Route**

`GET` api/logout
> This is a JWT token protected route

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful Logout |

**Sample Request**

`GET` http://innoflow.app/api/logout

<br>



### Check if user has authorized with VSTS:

**Route**

`GET` api/vsts
> This is a JWT token protected route

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful fetch |

**Sample Request**

`GET` http://innoflow.app/api/vsts

**Sample Response**
```json
{
    "is_authorized": false
}
```
<br><br><br>



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
| 200 | Successful creation |
| 401 | email or password parameter are incorrect and do not match with any registered user |
| 422 | <ul><li>email, password or code parameter paramter are missing</li><li>code parameter is not properly encoded</li></ul> |


**Sample Request**

`POST` http://innoflow.app/api/innovations

```json
{
    "email" : "andreas@gmail.com",
    "password" : "1234567890",
    "code" : "ICAgIC8qKg0KICAgICAqIFJldHVybnMgYSBwZXJtaXNzaW9uIGRlbmllZCByZXNwb25zZSBpbiBjYXNlIGF1dGhvcml6ZSgpIHJldHVybnMgZmFsc2UuDQogICAgICoNCiAgICAgKiBAcmV0dXJuIFxJbGx1bWluYXRlXEh0dHBcSnNvblJlc3BvbnNlDQogICAgICovDQogICAgcHVibGljIGZ1bmN0aW9uIGZvcmJpZGRlblJlc3BvbnNlKCkNCiAgICB7DQogICAgICAgIHJldHVybiAkdGhpcyAtPiByZXNwb25kVW5hdXRob3JpemVkKCAnUGVybWlzc2lvbiBkZW5pZWQuIEludmFsaWQgdXNlciBjcmVkZW50aWFscy4nICk7DQogICAgfQ=="
}
```
<br>



### Get inovations:

**Route**

`GET` api/innovations

> This is a JWT token protected route

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200  | Successful fetch |

**Sample Request**

`GET` http://innoflow.app/api/innovations

**Sample Response**
```json
[
    {
        "code": "public static function uniqueRandomNumbersWithinRange( $min, $max, $quantity ){$numbers = range( $min, $max );shuffle( $numbers );return array_slice( $numbers, 0, $quantity );}",
        "created": "2017-02-21 15:35:19"
    }
    {
        "code": "void read_jump(environment &env){infinite X, Y;infinite p = env.CP + 2;cba2n(env, p, X, Y);env.CP += (env.tape[env.DP] ==env.tape[env.CP+1]) ? X : Y;}",
        "created": "2017-02-21 15:34:56"
    }
]
```
<br><br><br>



## 3. Classes

### Store a class:

**Route**

`POST` api/classes
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| name        | string       | The name of the class (min 5 characters, max 100 characters) |
| description | text         | A short description of what the class is about (min 20 characters, max 1000 characters) |
| code        | string       | The class code (min 5 characters, max 100 characters) |
| key         | string       | The enrolment key students can use to enrol their projects into a class (min 10 characters, max 100 characters) |
| admins      | int list     | OPTIONAL A list of user ids, each representing an admin of the class (the creator of the class is automatically an admin) |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful creation |
| 422 | <ul><li>name, description, code or key parameter paramter are missing</li><li>name, description, code, key or admins parameter  have incorrect formats</li><li>code paramter is already taken</li></ul> |

**Sample Request**

`POST` http://innoflow.app/api/classes

```json
{
     "name" : "Software Abstractions and Systems Integration",
     "description" : "This is a MEng software engineering class",
     "code" : "COMPGS02",
     "key": "AwesomeClass2017",
     "admins" : "12,52,73"
}
```
<br>



### Get classes:

Includes both classes created as well as those where user was assinged as admin.

**Route**

`GET` api/classes
> This is a JWT token protected route

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful fetch |

**Sample Request**

`GET` http://innoflow.app/api/classes

```json
[
  {
      "id": 1,
      "name": "Software Abstractions and Systems Integration",
      "description": "This is a MEng software engineering class",
      "code": "COMPGS02"
  },
  {
      "id": 2,
      "name": "Computer Security 1",
      "description": "This is a MEng year security engineering class",
      "code": "COMPGA01"
  }
]
```
<br>



### Get a class:

**Route**

`GET` api/classes/{classId}
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| classId     | int          | A valid class id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200  | Successful fetch |
| 404  | Invalid class id |

**Sample Request**

`GET` http://innoflow.app/api/classes/1

```json
{
    "id": 1,
    "name": "Software Abstractions and Systems Integration",
    "description": "This is a MEng software engineering class",
    "code": "COMPGS02"
}
```
<br>



### Search for an admin:

**Route**

`GET` api/classes/admins/search
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| string      | string       | A search string (min 2 characters) |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200  | Successful fetch |
| 422  | <ul><li>string parameter is missing</li><li>string parameter has incorrect format</li></ul> |

**Sample Request**

`GET` http://innoflow.app/api/classes/admins/search?string=And

```json
[
  {
      "userId": 101,
      "email": "jack@gmail.com",
      "username": "Crocodile Killer"
  },
  {
      "userId": 79,
      "email": "demetris.damore@kuhn.info",
      "username": "jared.hauck"
  }
]
```
<br>



## 4. Projects

### Get projects:

**Route**

`GET` api/projects
> This is a JWT token protected route
> This is a VSTS token protected route

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful fetch |

**Sample Request**

`GET` http://innoflow.app/api/projects

```json
[
  {
      "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd61",
      "name": "MyFirstProject",
      "isOwner": true
  },
  {
      "id": "fa856987-4cf7-4ad4-bdba-5fbd1374865d",
      "name": "Innoflow",
      "description": "A university project",
      "isOwner": true
  },
]
```
<br>



### Get a project:

**Route**

`GET` api/projects/{projectId}
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful fetch |
| 404 | Invalid project id |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61

```json
{
    "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd61",
    "name": "MyFirstProject",
    "isOwner": true
}
```
<br>



### Enrol project into class:

**Route**

`POST` api/projects/{projectId}/enrol
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |
| code        | string       | A class code |
| key         | string       | The enrolment key of the class |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful enrolment |
| 400 | <ul><li>The project is already enrolled </li><li>The user is not the owner of the project</li></ul> |
| 401 | code or key are incorrect and do not match any registered class |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/enrol

```json
{
	    "code" : "COMPGS02",
	    "key" : "AwesomeClass2017"
}
```
<br>
