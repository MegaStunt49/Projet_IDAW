#!/bin/bash
# Base URL of the API (adjust as necessary)
BASE_URL="http://localhost/backend/aliments.php"

echo "Testing GET (All Aliments):"
curl -X GET "${BASE_URL}"
echo -e "\n"

echo "Testing GET (Single Aliment by ID):"
curl -X GET "${BASE_URL}/1" # Replace 1 with a valid aliment ID
echo -e "\n"

echo "Testing POST (Create Aliment):"
curl -X POST "${BASE_URL}/NewAliment/1" # Replace 1 with a valid value
echo -e "\n"

echo "Testing PUT (Update Aliment):"
curl -X PUT "${BASE_URL}/1/2/UpdatedAliment" # Replace 1 and 2 with valid values
echo -e "\n"

echo "Testing DELETE (Delete Aliment):"
curl -X DELETE "${BASE_URL}/1" # Replace 1 with a valid aliment ID
echo -e "\n"
