# 🔐 Thông Tin Đăng Nhập Admin

## Admin Accounts

### Account 1
- **Username**: `admin`
- **Email**: `admin@computershop.com`
- **Password**: `admin123`
- **Role**: Admin

### Account 2  
- **Username**: `admin_bao`
- **Email**: `bao089492@gmail.com`
- **Password**: `baodz123`
- **Role**: Admin

---

## Cách Đăng Nhập

1. Truy cập: **http://localhost:8080/login**
2. Nhập email hoặc username và password
3. Sau khi đăng nhập, truy cập: **http://localhost:8080/admin**

---

## Lỗi Thường Gặp

### ❌ Không truy cập được admin
**Nguyên nhân**: Chưa đăng nhập hoặc không phải admin
**Giải pháp**: Đăng nhập bằng tài khoản admin trước

### ❌ Login không chuyển đến admin
**Nguyên nhân**: UserController redirect về trang chủ sau login
**Giải pháp**: Sau khi login, thủ công vào `/admin`

---

## Quick Test

```bash
# Test login trực tiếp qua curl
curl -X POST http://localhost:8080/login \
  -d "email=admin@computershop.com&password=admin123"
```
