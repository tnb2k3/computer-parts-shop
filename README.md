# 🖥️ Website Bán Linh Kiện Máy Tính

> Đồ án tốt nghiệp - Hệ thống thương mại điện tử cho cửa hàng linh kiện máy tính

![PHP](https://img.shields.io/badge/PHP-75.7%25-777BB4?style=flat&logo=php&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-15.9%25-1572B6?style=flat&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-7.3%25-F7DF1E?style=flat&logo=javascript&logoColor=black)

---

## 📋 Mô tả

Hệ thống thương mại điện tử trọn gói cho cửa hàng máy tính, tập trung vào tính năng **"Xây dựng cấu hình PC"** giúp khách hàng tự chọn linh kiện tương thích.

---

## ✨ Tính năng chính

- 🔧 **Xây dựng cấu hình PC** — tự chọn linh kiện tương thích
- 🛒 **Giỏ hàng AJAX** — thêm/xóa sản phẩm không cần reload trang
- 🔍 **Bộ lọc sản phẩm nâng cao** — lọc theo loại, giá, thương hiệu
- 📊 **Admin Dashboard** — quản lý nhập/xuất kho, theo dõi đơn hàng và doanh thu
- 💳 **QR Payment** — thanh toán qua mã QR
- 👤 **Hệ thống tài khoản** — đăng ký, đăng nhập, xác minh email

---

## 🛠️ Công nghệ sử dụng

| Thành phần | Công nghệ |
|---|---|
| Backend | PHP (thuần / MVC tự xây) |
| Frontend | HTML5, CSS3, JavaScript |
| Database | MySQL |
| Server | Docker |
| Package Manager | Composer |

---

## 📁 Cấu trúc thư mục

```
computer-parts-shop/
├── src/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
│       ├── admin/
│       ├── products.php
│       ├── product-detail.php
│       ├── cart.php
│       ├── checkout.php
│       ├── order-success.php
│       ├── qr-payment.php
│       ├── login.php
│       ├── register.php
│       ├── profile.php
│       └── verify-pending.php
├── public/
├── docker/
├── docker-compose.yml
├── composer.json
└── README.md
```

---

## 🚀 Cài đặt & Chạy

### Yêu cầu
- PHP >= 8.0
- Composer
- MySQL
- Docker (tuỳ chọn)

### Cách 1 — Dùng Docker (khuyên dùng)

```bash
# 1. Clone project
git clone https://github.com/tnb2k3/computer-parts-shop.git
cd computer-parts-shop

# 2. Khởi động Docker
docker-compose up -d
```

### Cách 2 — Chạy thủ công

```bash
# 1. Clone project
git clone https://github.com/tnb2k3/computer-parts-shop.git
cd computer-parts-shop

# 2. Cài dependencies
composer install

# 3. Tạo database MySQL tên: computer_shop
# Rồi import file SQL trong thư mục /read (nếu có)

# 4. Cấu hình kết nối DB trong file config
# (sửa host, username, password, database)

# 5. Chạy server
php -S localhost:8000 -t public
```

Truy cập: `http://localhost:8000`

---

## 📸 Screenshots

> *(Thêm ảnh chụp màn hình giao diện vào đây)*

---

## 👨‍💻 Tác giả

**Trần Ngọc Bảo**
- GitHub: [@tnb2k3](https://github.com/tnb2k3)
- Email: babykonnit7@gmail.com
- LinkedIn: [linkedin.com/in/baodev666](https://linkedin.com/in/baodev666)

---

## 📄 License

Dự án này được tạo cho mục đích học tập.
