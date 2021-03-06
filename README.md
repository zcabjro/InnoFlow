# Overview
### InnoFlow is a VSTS based platform dedicated to:
* Project transparency
* Feedback included
* Innovation showcased

This repository holds the implementation of the RESTful API as well the VSTS extension (web app). InnoFlow's other two components can be found via the following GitHub repositories:

* IDE Extension: https://github.com/zcabjro/InnoFlow_VSCode#usage
* Chrome Extension: https://github.com/zcabjro/InnoFlowChrome


# RESTful API



## 1. Authentication

### Register User:

**Route**

`POST` api/register

| Parameter   | Type      | Notes     |
| ------------|-----------|-----------|
| email       | string    | Must be a valid and unique email address |
| password    | string    | A strong password (min 10 characters) |
| username    | string    | A unique username (min 5 characters) |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful registration |
| 422 | <ul><li>email, password or username parameters are missing</li><li>email, password or username parameters have incorrect format</li><li>email or username parameters already taken</li></ul> |

**Sample Request**

`POST` http://innoflow.app/api/register

```json
{
  "email" : "andreas@gmail.com",
  "password" : "1234567890",
  "username" : "SickAustrian"
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
| 401 | email or password parameters are incorrect and do not match with any registered user |
| 422 | <ul><li>email or password parameters are missing</li><li>email or password parameters have incorrect format</li></ul> |

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



### Check if user is logged in:

**Route**

`GET` api/innoflow

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful fetch |

**Sample Request**

`GET` http://innoflow.app/api/innoflow

**Sample Response**
```json
{
  "isLoggedIn": false
}
```
<br><br><br>



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
  "isAuthorized": false,
  "url": "https://app.vssps.visualstudio.com/oauth2/authorize?client_id=5AE68C15-26B5-4A46-A272-737ADE6BE888&response_type=Assertion&state=101&scope=vso.code vso.project_manage&redirect_uri=https://innoflow.herokuapp.com/vsts/authorize"
}
```
or
```json
{
  "isAuthorized": true
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
| 401 | email or password parameters are incorrect and do not match with any registered user |
| 422 | <ul><li>email, password or code parameters are missing</li><li>code parameter is not properly encoded</li></ul> |


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



### Get a list of innovations:

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

### Create a class:

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
| 422 | <ul><li>name, description, code or key paramters are missing</li><li>name, description, code, key or admins parameters have incorrect formats</li><li>code paramter is already taken</li></ul> |

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

**Sample Response**

```json
{
  "id": 2,
  "name": "Software Abstractions and Systems Integration",
  "description": "This is a MEng software engineering class",
  "code": "COMPGS02",
  "admins": [
    {
      "userId": 12,
      "email": "justina71@yahoo.com",
      "username": "alana.bartell"
    },
    {
      "userId": 52,
      "email": "waelchi.cristal@kessler.com",
      "username": "vivian.boehm"
    },
    {
      "userId": 73,
      "email": "betsy.dare@mcglynn.com",
      "username": "florine.gutmann"
    },
    {
      "userId": 101,
      "email": "andreas@gmail.com",
      "username": "SickAustrian"
    }
  ],
  "projects": []
}
```
<br>



### Get a list of classes:

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
| 401 | User is not an admin of the module |
| 404  | Invalid class id |

**Sample Request**

`GET` http://innoflow.app/api/classes/1

```json
{
  "id": 1,
  "name": "Software Abstractions and Systems Integration",
  "description": "This is a MEng software engineering class",
  "code": "COMPGS02",
  "admins": [
    {
      "userId": 12,
      "email": "davis.hilton@yahoo.com",
      "username": "tobin.roob"
    },
    {
      "userId": 52,
      "email": "otho.turcotte@abbott.com",
      "username": "juston45"
    },
    {
      "userId": 73,
      "email": "gpaucek@nicolas.com",
      "username": "dennis36"
    },
    {
      "userId": 101,
      "email": "jack@gmail.com",
      "username": "Crocodile Killer"
    }
  ],
  "projects": [
    {
      "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd61",
      "name": "MyFirstProject",
      "classId": 1
    }
  ]
}
```
<br>



### Search for a user to add as admin:

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


### Search for a class:

**Route**

`GET` api/classes/search
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

`GET` http://innoflow.app/api/classes/admins/search?string=COMPGS

```json
[
  {
    "id": 1,
    "name": "Software Abstractions and Systems Integration",
    "description": "This is a MEng software engineering class",
    "code": "COMPGS02"
  }
]
```
<br>



### Get list of class metrics:

**Route**

`GET` api/classes/{classId}/metrics
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| classId     | int          | A valid class id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200  | Successful fetch |
| 401  | User is not an admin of the module |
| 404  | Invalid class id |

**Sample Request**

`GET` http://innoflow.app/api/classes/1/metrics

```json
{
  "codeReviewMetric": {
    "averageValidCodeReviews": 1.5,
    "projectLevel": [
      {
        "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd61",
        "name": "Freshly",
        "contribution": 3
      },
      {
        "id": "f2df9ad9-2281-4265-bda3-ded1da89fb2d",
        "name": "SnapPro",
        "contribution": 0
      }
    ]
  },
  "commitBalanceMetric": {
    "averageCommitBalance": 0,
    "projectLevel": [
      {
        "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd61",
        "name": "Freshly",
        "contribution": 0
      },
      {
        "id": "f2df9ad9-2281-4265-bda3-ded1da89fb2d",
        "name": "SnapPro",
        "contribution": 0
      }
    ]
  },
  "feedbackMetric": {
    "totalFeedback": 1.5,
    "projectLevel": [
      {
        "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd61",
        "name": "Freshly",
        "contribution": 3
      },
      {
        "id": "f2df9ad9-2281-4265-bda3-ded1da89fb2d",
        "name": "SnapPro",
        "contribution": 0
      }
    ]
  }
}
```
<br>



## 4. Projects

### Get a list of projects:

**Route**

`GET` api/projects
> This is a JWT token protected route

> This is a VSTS token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| refresh     | boolean      | OPTIONAL Indicates whether the projects should be brought up to date with VSTS  |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful fetch |

**Sample Request**

`GET` http://innoflow.app/api/projects

```json
[
  {
    "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd55",
    "name": "Freshly",
    "class": {
      "id": 1,
      "name": "Systems Engineering"
    },
    "isOwner": true
  },
  {
    "id": "8cc6621f-6d02-4de2-8a2c-ef1765ff2288",
    "name": "WordSheriff",
    "isOwner": true
  },
  {
    "id": "a2271f0f-c6f6-4c64-aaea-d993a3e1d324",
    "name": "InnoFlow v2",
    "description": "CS Education Tool.",
    "isOwner": false
  },
  {
    "id": "f2df9ad9-2281-4265-bda3-ded1da89fabt",
    "name": "SnapPro",
    "class": {
      "id": 1,
      "name": "Systems Engineering"
    },
    "isOwner": true
  }
]
```
<br>



### Get project details:

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
| 401 | User is not a member of the project |
| 404 | Invalid projectId parameter |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd55

```json
{
  "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd61",
  "name": "Freshly",
  "class": {
    "id": 1,
    "name": "Systems Engineering"
  },
  "members": [
    {
      "userId": 101,
      "email": "andreas@gmail.com",
      "username": "SickAustrian"
    }
  ]
}
```
<br>



### Enroll project into class:

**Route**

`POST` api/projects/{projectId}/enrol
> This is a JWT token protected route

> This is a VSTS token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |
| code        | string       | A class code |
| key         | string       | The enrolment key of the class |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful enrolment |
| 401 | <ul><li>The user is not the owner of the project</li><li>code or key are incorrect and do not match any registered class</li><li>User is not a member of the project</li></ul> |
| 404 | Invalid projectId parameter |
| 422 | The project is already enrolled |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/enrol

```json
{
  "code" : "COMPGS02",
  "key" : "AwesomeClass2017"
}
```
<br>



### Get a list of project metrics:

**Route**

`GET` api/projects/{projectId}/metrics
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200  | Successful fetch |
| 401  | User is not a member of the project |
| 404  | Invalid projectId parameter |

**Sample Request**

`GET` http://innoflow.app/api/projects/a2288f0f-c6f6-4c64-aaea-d993a3e1d333

**Sample Response**

```json
{
  "codeReviewMetric": {
    "totalValidCodeReviews": 12,
    "individualLevel": [
      {
        "username": "AndreasLukas",
        "id": 102,
        "contribution": 0.8
      },
      {
        "username": "JediJack",
        "id": 102,
        "contribution": 0.2
      }
    ]
  },
  "commitBalanceMetric": {
    "averageCommitBalance": 1,
    "individualLevel": [
      {
        "username": "AndreasLukas",
        "id": 102,
        "contribution": 1
      },
      {
        "username": "JediJack",
        "id": 102,
        "contribution": 1
      }
    ]
  },
  "feedbackMetric": {
    "totalFeedback": 30,
    "individualLevel": [
      {
        "username": "AndreasLukas",
        "id": 102,
        "contribution": 0.3
      },
      {
        "username": "JediJack",
        "id": 102,
        "contribution": 0.7
      }
    ]
  }
}
```
<br>



## 5. Commits
### Get a list of commits:

**Route**

`GET` api/projects/{projectId}/commits
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful fetch |
| 401 | User is not a member of the project |
| 404 | Invalid projectId parameter |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/commits

```json
{
  "id": "1b37c498-0c27-42e2-ba44-c3a90e86cd61",
  "name": "Freshly",
  "classId": 1,
  "commits": [
    {
      "id": "3f1d3a2bbbc31424f0f758858bf51a11e22a718f",
      "comment": "add text2.txt",
      "date": "2017-03-04 15:21:29+00",
      "commit_url": "https://andreas.visualstudio.com/_git/ProjectA/commit/3e1d3a2bbbc31424f0f758858bf51a11e22a718e",
      "changes": {
        "adds": 1,
        "edits": 0,
        "deletes": 0
      },
      "commiter": {
        "id": 102,
        "username": "SickAustrian"
      }
    },
    {
      "id": "ff95c78b8d22cd321ad8fb31ef5ad2dd18e54b82",
      "comment": "delete text2.txt",
      "date": "2017-03-04 15:20:44+00",
      "commit_url": "https://andreas.visualstudio.com/_git/ProjectA/commit/fe95c78b8d22cd321ad8fb31ef5ad2dd18e54b83",
      "changes": {
        "adds": 0,
        "edits": 0,
        "deletes": 1
      },
      "commiter": {
        "id": 102,
        "username": "SickAustrian"
      }
    }
  ]
}
```
<br>



### Get a commit:

**Route**

`GET` api/projects/{projectId}/commits/{commitId}
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |
| commitId    | string       | A valid commit id  |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful fetch |
| 401 | User is not a member of the project |
| 404 | Invalid projectId or commitId parameter |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/commits/3f1d3a2bbbc31424f0f758858bf51a11e22a718f

```json
{
  "id": "3f1d3a2bbbc31424f0f758858bf51a11e22a718f",
  "comment": "add text2.txt",
  "date": "2017-03-04 15:21:29+00",
  "commit_url": "https://andreas.visualstudio.com/_git/ProjectA/commit/3e1d3a2bbbc31424f0f758858bf51a11e22a718e",
  "changes": {
    "adds": 1,
    "edits": 0,
    "deletes": 0
  },
  "commiter": {
    "id": 102,
    "username": "SickAustrian"
  }
}
```
<br>



## 6. Code review

### Create a code review discussion:

**Route**

`POST` api/projects/{projectId}/codereviews
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |
| commitIds   | list of strings  | A list of valid commit ids |
| title   | string       | A suitable title (min 10 chars) |
| description   | string       | A suitable description (min 20 chars) |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful creation |
| 401 | User is not a member of the project |
| 404 | Invalid projectId parameter |
| 422  | <ul><li>commitIds, title or description parameter are missing</li><li>commitIds, title or description parameter have incorrect format</li></ul> |

**Sample Request**

`POST` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/codereviews

```json
{
  "commitIds" : "e93a59aaa7d627174aa78c686cd13eaaa9e7e7d5",
  "title" : "This is the title of a code review discussion",
  "description" : "This is the description of a code review discussion"
}
```

**Sample Response**
```json
{
  "id": 3,
  "date": "2017-03-08 18:14:42",
  "title": "This is the title of a code review discussion",
  "description": "This is the description of a code review discussion",
  "owner": {
    "id": 101,
    "username": "SickAustrian"
  }
}
```
<br>



### Get a list of code review discussions:

**Route**

`GET` api/projects/{projectId}/codereviews
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful creation |
| 401 | User is not a member of the project |
| 404 | Invalid projectId parameter |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/codereviews

```json
[
  {
    "id": 2,
    "date": "2017-03-06 21:53:32",
    "title": "This is the title of a code review discussion",
    "description": "This is the description of a code review discussion",
    "owner": {
      "id": 101,
      "username": "SickAustrian"
    }
  },
  {
    "id": 1,
    "date": "2017-03-06 21:51:32",
    "title": "This is the title of a code review discussion",
    "description": "This is the description of a code review discussion",
    "owner": {
      "id": 101,
      "username": "SickAustrian"
    }
  }
]
```
<br>



### Get a code review discussion:

**Route**

`GET` api/projects/{projectId}/codereviews/{codeReviewId}
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |
| codeReviewId   | int       | A valid code review id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful creation |
| 401 | User is not a member of the project |
| 404 | Invalid projectId or codeReviewId parameter |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/codereviews/1

```json
{
  "id": 1,
  "date": "2017-03-04 16:33:38",
  "title": "This is the title of a code review discussion",
  "description": "This is the description of a code review discussion",
  "owner": {
    "id": 101,
    "username": "SickAustrian"
  },
  "commits": [
    {
      "id": "e93a59aaa7d627174aa78c686cd13eaaa9e7e7d5",
      "comment": "refresh folder",
      "date": "2017-03-04 14:44:25",
      "commit_url": "https://andreasrauter.visualstudio.com/_git/Freshly/commit/e93a59aaa7d627174aa78c686cd13eaaa9e7e7d5",
      "changes": {
        "adds": 0,
        "edits": 0,
        "deletes": 0
      },
      "commiter": {
        "id": 101,
        "username": "SickAustrian"
      }
    }
  ],
  "comments": [
    {
      "id": 1,
      "date": "2017-03-04 17:25:44",
      "text": "This is a comment by JackRoper",
      "owner": {
        "id": 3,
        "username": "JackRoper"
      }
    },
    {
      "id": 2,
      "date": "2017-03-04 17:25:56",
      "text": "This is a comment by DavidWhite",
      "owner": {
        "id": 66,
        "username": "DavidWhite"
      }
    },
    {
      "id": 3,
      "date": "2017-03-04 17:26:39",
      "text": "This is a comment by Dean",
      "owner": {
        "id": 24,
        "username": "Dean"
      }
    }
  ]
}
```
<br>



### Create a comment

**Route**

`POST` api/projects/{projectId}/codereviews/{codeReviewId}/comments
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |
| codeReviewId   | int       | A valid code review id |
| message   | string         | A useful comment (min 20 chars) |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful creation |
| 401 | User is not a member of the project |
| 404 | Invalid projectId or codeReviewId parameter |
| 422  | <ul><li>message parameter is missing</li><li>message parameter has incorrect format</li></ul> |

**Sample Request**

`POST` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/codereviews/1/comments

```json
{
  "message" : "This is a comment. Always make sure a comment is useful."
}
```

**Sample Response**

```json
{
  "id": 3,
  "date": "2017-03-08 18:16:27",
  "text": "This is a comment. Always make sure a comment is useful.",
  "owner": {
    "id": 101,
    "username": "SickAustrian"
  }
}
```
<br>



### Get a list of comments

**Route**

`GET` api/projects/{projectId}/codereviews/{codeReviewId}/comments
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |
| codeReviewId   | int       | A valid code review id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful creation |
| 401 | User is not a member of the project |
| 404 | Invalid projectId or codeReviewId parameter |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/codereviews/1/comments

```json
[
  {
    "id": 4,
    "date": "2017-03-04 18:13:18",
    "text": "This is a comment. Always make sure a comment is useful.",
    "owner": {
      "id": 101,
      "username": "SickAustrian"
    }
  },
  {
    "id": 3,
    "date": "2017-03-04 17:26:39",
    "text": "This is a comment. Always make sure a comment is useful.",
    "owner": {
      "id": 3,
      "username": "JackRoper"
    }
  },
]
```
<br>



### Get a comment

**Route**

`GET` api/projects/{projectId}/codereviews/{codeReviewId}/comments/{commentId}
> This is a JWT token protected route

| Parameter   | Type         | Notes     |
| ------------|--------------|-----------|
| projectId   | string       | A valid project id |
| codeReviewId   | int       | A valid code review id |
| commentId   | int          | A valid comment id |

 **Response Codes**
 
| Code | Notes |
| -----|-------|
| 200 | Successful creation |
| 401 | User is not a member of the project |
| 404 | Invalid projectId, codeReviewId or commentId parameter |

**Sample Request**

`GET` http://innoflow.app/api/projects/1b37c498-0c27-42e2-ba44-c3a90e86cd61/codereviews/1/comments/4

```json
{
  "id": 4,
  "date": "2017-03-04 18:13:18",
  "text": "This is a comment. Always make sure a comment is useful.",
  "owner": {
    "id": 101,
    "username": "SickAustrian"
  }
}
```
<br>
