
# Wallet Backend

- Clone the repo
    > git clone https://github.com/Kingsconsult/wallet_backend.git

-  Copy and rename the .env.example file to .env
- Generate the app key
    > php artisan key:generate
- Create the database
- Run the migration
    > php artisan migrate
- Create the passport encryption keys
    > php artisan passport:install
- Start the App on localhost
    > php artisan serve


## Endpoints 
- Register to the app (Post request)
    >http://127.0.0.1:8000/api/register
- Login to the app (Post request)
    > http://127.0.0.1:8000/api/login
#### Use the token generated from the login and use as the bearer token for other routes.
- Get all users in the app (Get request)
    > http://127.0.0.1:8000/api/all-users
 - Get a users details (Get request)
    >http://127.0.0.1:8000/api/user/id
- Get the total number of users of the app (Get request)
    >http://127.0.0.1:8000/api/user/id
- Create a wallet type (Post request)
    >http://127.0.0.1:8000/api/wallet-types
- Get wallet types with the minimum balance and interest rate (Post request)
    >http://127.0.0.1:8000/api/wallet-types
- Create a wallet (Post request)
    >http://127.0.0.1:8000/api/wallets
- Get all wallets in the app (Get request)
    >http://127.0.0.1:8000/api/wallets
- Get a wallet with all the details (Get request)
    >http://127.0.0.1:8000/api/wallets/id
- Delete a wallet (Delete request)
    >http://127.0.0.1:8000/api/wallets/id
- Get the number of wallets in the app (Get request)
    >http://127.0.0.1:8000/api/wallets/counts
- Fund a wallet (Post request)
    >http://127.0.0.1:8000/api/wallets/fund-wallet
- Get a wallet balance (Get request)
    >http://127.0.0.1:8000/api/wallets/balance/id
- Transfer money from one wallet to another (Post request)
    >http://127.0.0.1:8000/api/wallets/fund-transfer

- Upload an excel file (Post request)
    >http://127.0.0.1:8000/api/import-excel
