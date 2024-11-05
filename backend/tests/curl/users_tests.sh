#!/bin/bash
# Base URL of the API (adjust as necessary)
BASE_URL="http://localhost/backend/users.php"
HEADER="Content-Type: application/json"

echo "Testing GET (All users):"
curl -X GET "${BASE_URL}"
echo -e "\n"

echo "Testing POST (Create user):"
curl -X POST -H "${HEADER}" -d '{
  "login": "test",
  "id_niveau": 1,
  "id_sexe": 1,
  "password": "pssw",
  "annee_naissance": 2000,
  "pseudo": "test",
  "email": "test@test.com"
}' "${BASE_URL}"
echo -e "\n"

echo "Testing GET (Single user by ID):"
curl -X GET "${BASE_URL}/test"
echo -e "\n"

echo "Testing PUT (Update user):"
curl -X PUT -H "${HEADER}" -d '{
  "login": "test",
  "id_niveau": 1,
  "password": "password",
  "pseudo": "test2",
  "email": "test2@test.com"
}' "${BASE_URL}"
echo -e "\n"

echo "Testing DELETE (Delete user):"
curl -X DELETE "${BASE_URL}/test"
echo -e "\n"
