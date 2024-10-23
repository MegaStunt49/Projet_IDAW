# Base URL of the API (adjust as necessary)
BASE_URL="http://localhost/backend/references.php"

echo "Testing GET (All niveau references):"
curl -X GET "${BASE_URL}/niveau"
echo -e "\n"

echo "Testing GET (niveau reference by id):"
curl -X GET "${BASE_URL}/niveau/1" # Replace 1 with a valid niveau ID
echo -e "\n"

echo "Testing GET (All sexe references):"
curl -X GET "${BASE_URL}/sexe"
echo -e "\n"

echo "Testing GET (sexe reference by id):"
curl -X GET "${BASE_URL}/sexe/1" # Replace 1 with a valid sexe ID
echo -e "\n"

echo "Testing GET (All unite references):"
curl -X GET "${BASE_URL}/unite"
echo -e "\n"

echo "Testing GET (unite reference by id):"
curl -X GET "${BASE_URL}/unite/1" # Replace 1 with a valid unite ID
echo -e "\n"

echo "Testing GET (All caracteristique references):"
curl -X GET "${BASE_URL}/caracteristique"
echo -e "\n"

echo "Testing GET (caracteristique reference by id):"
curl -X GET "${BASE_URL}/caracteristique/1" # Replace 1 with a valid caracteristique ID
echo -e "\n"

echo "Testing GET (undefined reference):"
curl -X GET "${BASE_URL}/undefined/1"
echo -e "\n"