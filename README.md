# Kata09: Back to the Checkout

## The Problem
http://codekata.com/kata/kata09-back-to-the-checkout/


## My Solution
To visualize the result, I have added an API endpoint that receives the skus list and returns a JSON response with the total price.

Since we are relying on interfaces, it is possible to switch or change the output format from JSON to HTML in the future.

There are also some unit and integration tests that cover the important functionalities of the application.

### Flexible Pricing Rules
Since we are in the spring, I have added a pricing pules for the current season.
However, since the design is flexible, we can switch or add other pricing rules for summer, winter, or Black Friday, for example.
We can switch the active pricing rule easily in our ```AppServiceProvider``` without the need to change the code itself.


### I. Environment
- PHP: 8.2
- Laravel: 11
- PHPUnit: 11


### II. Setup
#### II.1. installation
- ```composer install```
- Rename ```.env.example``` to ```.env```
- ```php artisan key:generate```

#### II.2. Starting the server
- ```php artisan serve```
- Visit http://localhost:8000/


### III. Testing
#### III.1. Running the tests
-  ```php artisan test```


### IV. Examples
#### IV.1. Valid input data
To check how the application handles valid skus, please test the following end point:
- http://localhost:8000/checkout/AAA
- http://localhost:8000/checkout/BB
- http://localhost:8000/checkout/C
- http://localhost:8000/checkout/D
#### IV.2. Invalid input data
To check how the application handles invalid skus, please test the following end points:
- http://localhost:8000/checkout/AAZAA
- http://localhost:8000/checkout/ZAA
- http://localhost:8000/checkout/AAZ
- http://localhost:8000/checkout/Z


### V. Possible Future Improvements
#### V.1. Using database
- Since we only have 4 skus A, B, C and D, and for simplicity sake, the allowed item skus are hardcoded in the application.
- However, when the application grows in the future, it will make sense to fetch the items from the database instead.
#### V.2. Testing coverage
- Currently, not every piece of code is covered by tests. We can add more tests to cover the rest of them.
#### V.3. Request validation
- In our controller, we can add some request validation to make sure the skus we are receiving from the user have a valid format.


### VI. Feedback
I will be looking forward to your feedback!
