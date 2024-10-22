# Base URL of the API (adjust as necessary)
BASE_URL="http://localhost/backend/aliments.php"

# Test GET request (get all aliments)
echo "Testing GET (All Aliments):"
curl -X GET "${BASE_URL}"
echo -e "\n"

# Test GET request (get aliment by ID)
echo "Testing GET (Single Aliment by ID):"
curl -X GET "${BASE_URL}/1" # Replace 1 with a valid aliment ID
echo -e "\n"

# Test POST request (create a new aliment)
echo "Testing POST (Create Aliment):"
curl -X POST "${BASE_URL}/NewAliment/1" # Replace 1 with a valid value
echo -e "\n"

# Test PUT request (update existing aliment)
echo "Testing PUT (Update Aliment):"
curl -X PUT "${BASE_URL}/1/2/UpdatedAliment" # Replace 1 and 2 with valid values
echo -e "\n"

# Test DELETE request (delete aliment by ID)
echo "Testing DELETE (Delete Aliment):"
curl -X DELETE "${BASE_URL}/1" # Replace 1 with a valid aliment ID
echo -e "\n"
