1. Clone Project
```bash
git clone https://github.com/TrienHoang/SmartCareApp
```

2. Install php briary
```bash
# create vendor
composer update
```

3. Install js briary
```bash
#create node_modules
nmp i
```

4. Create .env file
```bash
Copy .env.example to .env
Config .env file
```

5. Create database
```bash
php artisan migrate
```

6. Create fake data
```bash
php artisan db:seed
```
7. Render app key
```bash
php artisan key:generate
```

8. Run project
```bash
npm run dev
php artisan serve

composer run dev
```

