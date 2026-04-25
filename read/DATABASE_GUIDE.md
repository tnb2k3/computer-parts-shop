# 🗄️ Hướng Dẫn Quản Lý Database

## 📍 Database Hiện Tại Nằm Ở Đâu?

### Docker Container
Database của bạn **KHÔNG** nằm trực tiếp trên máy Windows, mà chạy trong **Docker Container**:

```
Docker Container: computer_shop_mysql
├── MySQL Server 8.0
├── Database: computer_shop
├── Port: 3307 (host) -> 3306 (container)
└── Data: /var/lib/mysql (trong container)
```

### Thông Tin Kết Nối
- **Host**: `localhost` (từ máy Windows)
- **Port**: `3307` (không phải 3306 mặc định!)
- **Database**: `computer_shop`
- **Username**: `root`
- **Password**: `root`

---

## 🔧 Cách Truy Cập Database

### 1️⃣ Command Line (Hiện Tại)

**Vào MySQL Shell:**
```powershell
docker exec -it computer_shop_mysql mysql -uroot -proot computer_shop
```

**Chạy lệnh SQL trực tiếp:**
```powershell
docker exec computer_shop_mysql mysql -uroot -proot computer_shop -e "SELECT * FROM products;"
```

**Reset Database:**
```powershell
.\reset-database.ps1
```

### 2️⃣ phpMyAdmin (Web Interface) - KHUYẾN NGHỊ

**Đã cấu hình sẵn!** Chỉ cần chạy:
```powershell
docker-compose up -d
```

Sau đó truy cập: **http://localhost:8081**

**Đăng nhập:**
- Server: `mysql`
- Username: `root`  
- Password: `root`

---

## 📊 Sử Dụng phpMyAdmin

### Truy cập
1. Mở browser: http://localhost:8081
2. Login với `root` / `root`
3. Chọn database `computer_shop`

### Các Tính Năng
- ✅ **Browse**: Xem dữ liệu bảng
- ✅ **Structure**: Xem cấu trúc bảng
- ✅ **SQL**: Chạy câu lệnh SQL
- ✅ **Insert**: Thêm dữ liệu mới
- ✅ **Export**: Xuất database
- ✅ **Import**: Nhập SQL file

### Ví Dụ Thao Tác

**Xem products:**
1. Click `computer_shop` (bên trái)
2. Click `products` table
3. Click tab "Browse"

**Chỉnh sửa sản phẩm:**
1. Browse products table
2. Click icon "Edit" (bút chì)
3. Sửa thông tin
4. Click "Go"

**Chạy SQL:**
1. Click tab "SQL"
2. Nhập query, ví dụ:
```sql
SELECT * FROM products WHERE price > 5000000;
```
3. Click "Go"

---

## 🐳 Cấu Trúc Docker

### File `docker-compose.yml`
```yaml
services:
  mysql:                      # Database service
    image: mysql:8.0
    container_name: computer_shop_mysql
    ports:
      - "3307:3306"          # Port mapping
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: computer_shop
      
  phpmyadmin:                # phpMyAdmin service
    image: phpmyadmin
    container_name: computer_shop_phpmyadmin
    ports:
      - "8081:80"            # Web interface
    environment:
      PMA_HOST: mysql
      PMA_USER: root
      PMA_PASSWORD: root
```

### Kiểm Tra Containers
```powershell
docker ps
```

Bạn sẽ thấy:
- `computer_shop_mysql` (port 3307)
- `computer_shop_phpmyadmin` (port 8081)
- `computer_shop_nginx` (port 8080)

---

## 🔄 Workflow Quản Lý Database

### Development (Đang Code)
1. **Chỉnh sửa schema**: Edit `src/Database/schema.sql`
2. **Apply changes**: Chạy `.\reset-database.ps1`
3. **Xem kết quả**: Vào phpMyAdmin (http://localhost:8081)

### Testing Data (Thêm/Sửa Data)
1. Vào phpMyAdmin
2. Browse table cần sửa
3. Edit/Insert data
4. Reload web app để test

### Backup Database
**Qua phpMyAdmin:**
1. Chọn database `computer_shop`
2. Tab "Export"
3. Quick export → Go
4. Download file SQL

**Qua Command:**
```powershell
docker exec computer_shop_mysql mysqldump -uroot -proot computer_shop > backup.sql
```

### Restore Database
**Qua phpMyAdmin:**
1. Tab "Import"
2. Choose file
3. Go

**Qua Command:**
```powershell
docker exec -i computer_shop_mysql mysql -uroot -proot computer_shop < backup.sql
```

---

## ⚙️ Các Lệnh Hữu Ích

### Quản Lý Docker
```powershell
# Start tất cả services
docker-compose up -d

# Stop tất cả services
docker-compose down

# Xem logs MySQL
docker logs computer_shop_mysql

# Restart MySQL
docker restart computer_shop_mysql

# Xóa containers (giữ data)
docker-compose down

# Xóa containers + data
docker-compose down -v
```

### Truy cập MySQL
```powershell
# MySQL shell
docker exec -it computer_shop_mysql mysql -uroot -proot

# Chạy SQL file
docker exec -i computer_shop_mysql mysql -uroot -proot computer_shop < file.sql

# Export data
docker exec computer_shop_mysql mysqldump -uroot -proot computer_shop > dump.sql
```

---

## 🎯 Quick Start

**Lần đầu setup:**
```powershell
cd "c:\Users\sieub\Desktop\PRO1014 DU AN 1\myphp"
docker-compose up -d
.\reset-database.ps1
```

**Mở phpMyAdmin:**
1. Browser: http://localhost:8081
2. Login: root / root
3. Select: computer_shop

**Mở Web App:**
- http://localhost:8080

---

## 📝 Tóm Tắt

| Công cụ | URL | Mục đích |
|---------|-----|----------|
| **Web App** | http://localhost:8080 | Website chính |
| **phpMyAdmin** | http://localhost:8081 | Quản lý DB qua web |
| **MySQL Port** | localhost:3307 | Kết nối trực tiếp |

**Database nằm trong Docker, không phải trên Windows trực tiếp!**
