# Computer Parts E-commerce Website

Website bán linh kiện máy tính được xây dựng bằng PHP MVC framework.

## Tính năng

- ✅ Quản lý sản phẩm linh kiện máy tính
- ✅ Quản lý danh mục sản phẩm  
- ✅ Giỏ hàng (shopping cart)
- ✅ Đặt hàng và thanh toán
- ✅ Đăng nhập/đăng ký người dùng
- ✅ Admin panel quản lý

## Cấu trúc dự án

```
myphp/
├── docker/              # Docker configuration
├── public/              # Public files (entry point, assets)
├── src/
│   ├── Controllers/     # Controllers
│   ├── Core/            # Core framework
│   ├── Database/        # Database connection & schema
│   ├── Models/          # Models
│   ├── Repositories/    # Repositories (CRUD operations)
│   └── Views/           # View templates
├── vendor/              # Composer dependencies
├── .env                 # Environment variables
├── composer.json        # Composer configuration
└── docker-compose.yml   # Docker Compose configuration
```

## Cài đặt

### Sử dụng Docker (khuyến nghị)

1. Cài đặt Docker và Docker Compose
2. Clone dự án
3. Chạy lệnh:

```bash
cd myphp
docker-compose up -d
```

4. Import database schema:

```bash
docker exec -i computer_shop_mysql mysql -uroot -proot computer_shop < src/Database/schema.sql
```

5. Cài đặt Composer dependencies:

```bash
docker exec computer_shop_php composer install
```

6. Truy cập: http://localhost:8080

### Tài khoản mặc định

**Admin:**
- Email: admin@computershop.com
- Password: admin123

**Customer:**
- Email: customer@email.com
- Password: customer123

## Công nghệ sử dụng

- PHP 8.1
- MySQL 8.0
- Nginx
- Docker & Docker Compose
- Composer (PSR-4 autoloading)

## Cấu trúc MVC

- **Models**: Product, Category, User, Order, Cart
- **Views**: Home, Products, Cart, Checkout, Admin Panel
- **Controllers**: Home, Product, Category, Cart, Order, User, Admin
- **Repositories**: ProductRepository, CategoryRepository, UserRepository, OrderRepository, CartRepository

## License

MIT License
