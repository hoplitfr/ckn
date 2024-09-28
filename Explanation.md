# Assessment explanation

* For the realization of this assessment, I first chose to create a plugin in order to have functionalities that are independent of any potential theme.
* The first thing done upon activating the plugin is the creation of the roles necessary for its operation: Cool Kid, Cooler Kid, and Coolest Kid.

## User Story 1

* The plugin generates a shortcode that can be used on a page of your choice to display the login/registration form.
* Of the 5 pieces of information to be stored for creating the fake identity, 4 are already fields provided by WordPress (First Name, Last Name, Role, Email), which is why I chose to store the remaining one in the usermeta table under the key "country."
