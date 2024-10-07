# Assessment explanation

* For the realization of this assessment, I first chose to create a plugin in order to have functionalities that are independent of any potential theme.
* The first thing done upon activating the plugin is the creation of the roles necessary for its operation: Cool Kid, Cooler Kid, and Coolest Kid.

## User Story 1

* The plugin generates a shortcode that can be used on a page of your choice to display the login/registration form.
* Of the 5 pieces of information to be stored for creating the fake identity, 4 are already fields provided by WordPress (First Name, Last Name, Role, Email), which is why I chose to store the remaining one in the usermeta table under the key "country."

## User Story 2

* Once the user has logged in (defaut password is "test" for every account you create), the plugin checks his role to determine what information should be displayed. If the role is “Cool Kid”, then only his character's information will be displayed.
* For this part, I've chosen to create a new class dedicated to the user interface, in the interests of rationalization.

## User Story 3 & 4

* Once the user has logged in, two additional roles are checked (Cooler and Coolest) to determine the level of information to which the user has access.
* The method is the same for both roles, but incorporates a boolean to determine whether or not the user has access to the users' e-mail addresses.
* It also exclude from the users lists every account that are not "Cool Kid, "Cooler Kid" or "Coolest Kid" in order to prevent the display of their infos.
* A search field is added for Cooler kid and Coolest kid roles only. This field allows to search users by first name, last name and country. Coolest kids are also allowed to search by email adress.

## User Story 5

* The API is secured by a static API key defined in the class. I didn't include automatic key generation because this API is intended to be used by a maintainer or a very few users.
* I've chosen not to use WordPress native authentication because the aim is to allow access to a user who isn't logged in, delegating security to API key validation.
* The role is updated by calling set_role() on the user object (WP_User). The role is updated only if the role supplied by the API caller is one of the authorized roles, i.e. 'cool_kid', 'cooler_kid' and 'coolest_kid'. This validation protects against the assignment of unauthorized roles.
* All API inputs are validated and sanitized:

  * API key: sanitize_text_field(
  * Email: sanitize_email()
  * First name/Last name: sanitize_text_field()
  * Role: The role is validated by checking whether it is part of a predefined list of valid roles.
* Error handling:

  * If the API key is incorrect, an error message with status code 403 is returned.
  * If the user is not found (neither by email nor by first name/name), a message with status code 404 is returned.
  * If an invalid role is supplied, a message with code 400 is returned.

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