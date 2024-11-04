#!/bin/bash
# Base URL of the API (adjust as necessary)
BASE_URL="http://localhost/backend/repas.php"

echo "Testing GET (All repas):"
curl -X GET "${BASE_URL}/id"
echo -e "\n"

echo "Testing GET (Single repas by ID):"
curl -X GET "${BASE_URL}/id/1" # Replace 1 with a valid repas ID
echo -e "\n"

echo "Testing GET (Single repas by ID):"
curl -X GET "${BASE_URL}/login/teddyRider" # Replace teddyRider with a valid user login
echo -e "\n"

echo "Testing POST (Create repas):"
curl -X POST "${BASE_URL}/teddyRider/2024-03-16%2016%3A30%3A54" # Replace teddyRider and timestamp with valid values, (space) => %20 : (colon) => %3A
echo -e "\n"

echo "Testing PUT (Update repas):"
curl -X PUT "${BASE_URL}/9/teddyRider/2023-03-16%2016%3A30%3A54" # Replace 9 (ID), teddyRider and timestamp with valid values, (space) => %20 : (colon) => %3A
echo -e "\n"

echo "Testing DELETE (Delete repas):"
curl -X DELETE "${BASE_URL}/9" # Replace 9 with a valid repas ID
echo -e "\n"
