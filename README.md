# Cool Kids Network Assesment

## Setup

Clone the rep:
```
git clone https://github.com/hoplitfr/ckn.git
```

Launch docker:
```
docker compose up -d
```

Address (check with `docker ps` if needed):
```
0.0.0.0:8000
```

## Accounts

Login for admin:
```
admin/password
```

3 accounts already exist in the database, all with the password `test`:
```
test@test.com
test2@test.com
test3@test.com
```

## API Requests Samples

### Update user role to coolest_kid using email

```
curl -X POST http://0.0.0.0:8000/wp-json/coolkids/v1/update-role/
-H "Content-Type: application/json"
-d '{
"api_key": "123456789",
"email": "test3@test.com",
"role": "coolest_kid"
}'
```

### Update user role to cooler_kid using first name and last name

```
curl -X POST http://0.0.0.0:8000/wp-json/coolkids/v1/update-role/
-H "Content-Type: application/json"
-d '{
"api_key": "123456789",
"first_name": "Débora",
"last_name": "Mireles",
"role": "cooler_kid"
}'
```
