# Chess-Platform


Setup:
1) Clone repository
2) "composer install"
3) "npm install"
4) "php artisan migrate"
5) create .env file by copying .env.example
6) "php artisan key:generate" (if it gives error that encryption key not set)
7) Place compiled "stockfish" executable into /storage/app/private folder as stockfish.exe (necessary only if playing against engine)

For running:
1) "php artisan serve"
2) "npm run dev"
3) "php artisan reverb:start" (not necessary for initial app to work)

For running test cases: "php artisan test tests/CustomTests/UniversityTest.php"