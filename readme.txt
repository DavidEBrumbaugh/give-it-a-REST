The give-it-a-REST project is a lightweight php code library to make it easier to add WordPress rest API to themes and plugins.

It takes an Object Oriented approach.

The steps are:

* Establish your enpoint namespace
* Establish your endpoint version
* Build a Resource Handler to match each of the resources you wish to provide via REST derived from the abstract base class WpEpResourceHanlder
* In your derived class implement, get, put, post, delete and patch methods as appropriate.
* Use the add_route method to add the routes to your resources
* Use the init_route method to initialize those routes

This is a very, very early test of the framework.  
