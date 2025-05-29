
# IP Vault API

IP Address Management System that performs CRUD. The following are the supported features of this project:

1. **User Management System** -> this includes a CRUD operation on user as well as assigning their designated roles
2. **IP Address Management System** -> This lets the user (Admin and regular user) to add an ip address (IPv4/IPv6) with label, and an optional description.
3. **Audit Logs** -> A module that is only visible to super admin users which records all the activities done in users and ip addresses module. The logs are undeletable. regardless of the user's role. 

## Installation
```
cp .env.example .env
docker composet up --build
```

## Credentials

Admin User:

email: `superadmin@domain.com`
password: `password`

Regular User

email: `user@domain.com`
password: `password`

## Next step
Build/Setup the frontend [repository](https://github.com/mcdg055/practical-test-fe). Use the credentials above to gain access with the app.