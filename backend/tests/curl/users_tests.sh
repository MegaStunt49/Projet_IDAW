#!/bin/bash
# Base URL of the API (adjust as necessary)
BASE_URL="http://localhost/backend/users.php"

echo "Testing GET (All users):"
curl -X GET "${BASE_URL}"
echo -e "\n"

echo "Testing POST (Create user):"
curl -X POST "${BASE_URL}/test/1/1/pssw/2000/test/test@test.com"
echo -e "\n"

echo "Testing GET (Single user by ID):"
curl -X GET "${BASE_URL}/test"
echo -e "\n"

echo "Testing PUT (Update user):"
curl -X PUT "${BASE_URL}/test/1/password/test2/test2@test.com"
echo -e "\n"

echo "Testing DELETE (Delete user):"
curl -X DELETE "${BASE_URL}/test"
echo -e "\n"
