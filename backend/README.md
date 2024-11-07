## API Endpoints

**prefix**: `http://path-to-project/backend` 
Where path-to-project is the path from localhost to the root of the project.

### Users Endpoints

#### GET users

Fetches user information based on the login provided. If no login is specified, it returns a list of all users.

**Request**

- **Method**: `GET`
- **Path Parameters**: Optional login identifier (e.g., `prefix/users.php/login`).

**Response**

- **Code 201**: JSON array of user data if successful.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
GET /prefix/users.php/test HTTP/1.1
```

**Example Response**

```json
[
    {
        "login": "test",
        "libelle_niveau": "Intermediate",
        "libelle_sexe": "Male",
        "annee_naissance": 2003,
        "pseudo": "tester",
        "email": "test@test.com"
    }
]
```

#### POST users

Creates a new user record in the database.

**Request**

- **Method**: `POST`
- **Headers**: `Content-Type: application/json`
- **Path**: `prefix/users.php`
- **Body Parameters**: JSON object with the following fields:
    - login: User's login (string)
    - id_niveau: User's level ID (integer)
    - id_sexe: User's gender ID (integer)
    - password: User's password (hashed)
    - annee_naissance: User's birth year (integer)
    - pseudo: User's pseudonym (string)
    - email: User's email address (string)

**Response**

- **Code 200**: JSON array of user data if successful.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
    POST /prefix/users.php HTTP/1.1
    Content-Type: application/json

    {
        "login": "test",
        "id_niveau": 2,
        "id_sexe": 1,
        "password": "azerty",
        "annee_naissance": 2003,
        "pseudo": "tester",
        "email": "test@test.com"
    }
```

**Example Response**

```json
{
    "login": "test",
    "id_niveau": 2,
    "id_sexe": 1,
    "password": "hashed_password",
    "annee_naissance": 2003,
    "pseudo": "tester",
    "email": "test@test.com"
}
```

#### PUT users

Updates an existing user record based on the provided login.

**Request**

- **Method**: `POST`
- **Headers**: `Content-Type: application/json`
- **Path**: `prefix/users.php`
- **Body Parameters**: JSON object with the following fields (all optional except login):
    - login: User's login (string)
    - id_niveau: User's level ID (integer)
    - password: User's password (hashed)
    - pseudo: User's pseudonym (string)
    - email: User's email address (string)

**Response**

- **Code 200**: JSON array of user data if successful.
- **Code 404**: Error message if user not found or no changes made.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
PUT /prefix/users.php/test HTTP/1.1
Content-Type: application/json

{
    "id_niveau": 3,
    "password": "new_password",
    "pseudo": "tester-changed",
    "email": "test@test.com"
}
```

**Example Response**

```json
{
    "login": "test",
    "id_niveau": 3,
    "password": "hashed_new_password",
    "pseudo": "tester-changed",
    "email": "test@test.com"
}
```

#### DELETE users

Deletes a user record from the database based on the provided login.

**Request**

- **Method**: `DELETE`
- **Path Parameters**: Login identifier for the user to delete (e.g., `prefix/users.php/login`).

**Response**

- **Code 200**: JSON array of user data if successful.
- **Code 404**: Error message if user not found or no changes made.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
DELETE /prefix/users.php/test HTTP/1.1
```

**Example Response**

```json
{
    "message": "User deleted successfully.",
    "login": "test"
}
```

### Aliments Endpoints

#### GET Aliments

Fetches aliment information based on the `id` provided. If no `id` is specified, it returns a list of all aliments.

**Request**

- **Method**: `GET`
- **Path Parameters**: Optional `id` identifier (e.g., `prefix/aliments.php/id`).

**Response**

- **Code 201**: JSON array of user data if successful.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
GET /prefix/aliments.php/1 HTTP/1.1
```

**Example Response**

```json
{
    "id_aliment": 1,
    "libelle": "tomato",
    "type_aliment": "Fruit",
    "id_type_aliment": 2
}
```

#### POST Aliments

Creates a new aliment record in the database.

**Request**

- **Method**: `POST`
- **Path**: prefix/aliments.php
- **Body Parameters (JSON)**:

    libelle: Name of the aliment (string).
    id_type: Aliment type ID (integer).

**Response**

- **Code 201**: JSON array of user data if successful.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
POST /prefix/aliments.php HTTP/1.1
Content-Type: application/json

{
    "libelle": "Banana",
    "id_type": 2
}
```

**Example Response**

```json
{
    "id": 5,
    "id_type_aliment": 2,
    "libelle": "Banana"
}
```

#### PUT Aliments

Updates an existing aliment record based on the provided `id`.

**Request**

- **Method**: `PUT`
- **Path**: prefix/aliments.php/id
- **Path Parameters**: id - Aliment ID (integer).
- **Body Parameters (JSON)**:

    libelle: Updated aliment name (string).
    id_type: Updated aliment type ID (integer).

**Response**

- **Code 200**: JSON array of user data if successful.
- **Code 404**: Error message if user not found or no changes made.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
PUT /prefix/aliments.php/1 HTTP/1.1
Content-Type: application/json

{
    "libelle": "potato",
    "id_type": 3
}
```

**Example Response**

```json
{
    "id": 1,
    "id_type": 3,
    "libelle": "potato"
}
```

#### DELETE Aliments

Deletes an aliment record from the database based on the provided `id`.

**Request**

- **Method**: `DELETE`
- **Path Parameters**: id: Aliment ID (e.g., `prefix/aliments.php/id`).

**Response**

- **Code 200**: JSON array of user data if successful.
- **Code 404**: Error message if user not found or no changes made.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
DELETE /prefix/aliments.php/1 HTTP/1.1
```

**Example Response**

```json
{
    "message": "Aliment deleted successfully.",
    "id": 1
}
```

### Repas Endpoints

#### GET Repas

Fetches repas information. There are two ways to retrieve data:
1. By repas ID (`/repas.php/id/{id}`) : gets the repas with corresponding id.
2. By login (`/repas.php/login/{login}`) : gets all repas from the user corresponding to login.
3. Self (`/repas.php/self`) : Same as login but automatically fetch the user's login.

**Request**

- **Method**: `GET`
- **Path Parameters**: `/repas.php/id/{id}`: the ID (or login depending on the way) of the repas to fetch.

**Response**

- **Code 201**: JSON array of user data if successful.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
GET /prefix/repas.php/id/1 HTTP/1.1
```

**Example Response**

```json
{
    "libelle": "Tomate",
    "id_repas": 1,
    "login": "test",
    "date_heure": "2023-10-01 12:00:00",
    "quantite": "20.0",
    "energie": "1.03",
    "id_aliment": 1
}
```

#### POST Repas

Creates a new repas record.

**Request**

- **Method**: `POST`
- **Path Parameters**: login: The user’s login. date_heure: Date and time of repas in the format YYYY-MM-DD HH:MM:SS. (e.g., `prefix/repas.php/login/date_heure`).

**Response**

- **Code 200**: JSON array of user data if successful.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
POST /prefix/repas.php/test/2024-03-16%2016%3A30%3A54 HTTP/1.1
```

(space) => %20 : (colon) => %3A

**Example Response**

```json
POST /aliments HTTP/1.1
{
    "id": 3,
    "login": "test",
    "date_heure": "2024-03-16 16:30:54"
}
```

#### PUT Repas

Updates an existing repas record.

**Request**

- **Method**: `PUT`
- **Path Parameters**: id: ID of repas to update, login: Updated login, date_heure: Updated date and time (e.g., `prefix/repas.php/id/login/date_heure`).

**Response**

- **Code 200**: JSON array of user data if successful.
- **Code 404**: Error message if user not found or no changes made.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
PUT /prefix/repas.php/1/test/2024-03-16%2016%3A30%3A54 HTTP/1.1
```

**Example Response**

```json
{
    "id": 1,
    "login": "test",
    "date_heure": "2024-03-16 16:30:54"
}
```

#### DELETE Repas

Deletes a repas record.

**Request**

- **Method**: `DELETE`
- **Path Parameters**: id: ID of the repas to delete. (e.g., `prefix/repas.php/id`).

**Response**

- **Code 200**: JSON array of user data if successful.
- **Code 404**: Error message if user not found or no changes made.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
DELETE /prefix/repas.php/1 HTTP/1.1
```

**Example Response**

```json
{
    "message": "repas deleted successfully.",
    "id": 1
}
```

### References Endpoints

#### GET

Fetches references information. There are 4 table in which to retrieve data:
1. niveau sportif (`/references.php/niveau/{id}`)
2. sexe (`/references.php/sexe/{id}`)
3. unité (`/references.php/unite/{id}`)
4. caractéristique (`/references.php/caracteristique/{id}`)

if id is provided gets the reference corresponding to id, if nothing is provided gets all references.

**Request**

- **Method**: `GET`
- **Path Parameters**: `/references.php/{reference}/{id}`: ID and reference to fetch.

**Response**

- **Code 201**: JSON array of user data if successful.
- **Code 500**: Error message if fetching fails.

**Example Request**

```http
GET /prefix/references.php/niveau/1 HTTP/1.1
```

**Example Response**

```json
{
    "id_niveau": 1,
    "libelle": "Amateur"
}
```