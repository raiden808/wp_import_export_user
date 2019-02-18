Simple Export and Import of WordPress Users
==========

A simple plugin to back up you users in a CSV file with upload and download capability.Please use it on your staging or development area first.

Usage
==========

<h4>Download Method</h4>

Using the GET method we use the variable `print` and assign the `role` you want to 
download into a **csv** format.

Sample:

```http://localhost/playground/?print=subscriber```

This will download **subscriber** role.

<img src="assets/images/Screenshot_1.png"/>

The CSV file will contain this.

<img src="assets/images/Screenshot_2.png"/>


<h4>Upload Method</h4>

We utilize Shortcode API for the upload method.

``[wpie_import_user]``

The output would be:

<img src="assets/images/Screenshot_3.png"/>

Now we're going to upload a deleted user using our csv back up.


<img src="assets/images/Screenshot_4.png"/>


Success message:

<img src="assets/images/Screenshot_6.png"/>

Verify your uploaded user in the dashboard.

<img src="assets/images/Screenshot_7.png"/>