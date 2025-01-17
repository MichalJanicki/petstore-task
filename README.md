#### Install and run
1. git clone [git@github.com:MichalJanicki/petstore-task.git](https://github.com/MichalJanicki/petstore-task.git)

2. `composer install`

3. `cp .env.example .env`

4. `composer run post-create-project-cmd`

5. `php artisan serve`

#### Tests

`composer run petstore-run-test`

#### Code

The entire code is here `modules/Petstore`

#### Endpoints

GET

- `/` - get pets by status
- `/create` - create new pet
- `/{id}/edit - edit pet
- `/{id}/editPhoto` - upload photo
- `/{id}` - show info

POST

- `/{id}/update` - update info
- `/store` - create pet
- `/{id}/updatePhoto` - upload photo

DELETE

- `/{id}/remove` - remove pet
