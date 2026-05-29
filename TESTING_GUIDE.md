# 🧪 Panduan Testing Fitur Popular Topics

## ❌ **Masalah yang Ditemui**
- Error "Forbidden" saat mengakses `/guru-bk/popular-topics`
- User tidak bisa mengakses fitur meskipun sudah login sebagai Guru BK

## ✅ **Solusi yang Sudah Diterapkan**

### 1. **Perbaikan RoleMiddleware**
- Fixed comparison antara enum dan string
- Middleware sekarang support kedua format

### 2. **Perbaikan Form Request Authorization**
- Updated `StorePopularTopicRequest` dan `UpdatePopularTopicRequest`
- Menggunakan enum comparison yang benar

### 3. **User Seeder**
- Dibuat user test dengan role yang benar
- Admin: `admin@konselorkita.com` / `password`
- Guru BK: `gurubk@konselorkita.com` / `password`

---

## 🔧 **Langkah Testing Manual**

### **Step 1: Pastikan Server Berjalan**
```bash
php artisan serve
```
Akses: http://127.0.0.1:8000

### **Step 2: Login sebagai Guru BK**
1. Buka http://127.0.0.1:8000/login
2. Login dengan:
   - **Email**: `gurubk@konselorkita.com`
   - **Password**: `password`

### **Step 3: Test Authentication**
Setelah login, akses: http://127.0.0.1:8000/guru-bk/test-auth

**Expected Response:**
```json
{
    "user": {
        "id": 70,
        "name": "Ibu Sari (Guru BK)",
        "email": "gurubk@konselorkita.com"
    },
    "role": "guru_bk",
    "is_guru_bk": true,
    "message": "Authentication successful!"
}
```

### **Step 4: Test Popular Topics**
Jika Step 3 berhasil, akses: http://127.0.0.1:8000/guru-bk/popular-topics

**Expected Result:**
- ✅ Halaman index Popular Topics muncul
- ✅ Menampilkan 8 topik dari seeder
- ✅ Tombol "Tambah Topik" tersedia

---

## 🐛 **Troubleshooting**

### **Jika masih "Forbidden":**

#### **Check 1: User Role**
```bash
php artisan tinker
```
```php
$user = \App\Models\User::where('email', 'gurubk@konselorkita.com')->first();
echo $user->role->value; // Should output: guru_bk
echo $user->isGuruBk() ? 'true' : 'false'; // Should output: true
```

#### **Check 2: Session**
- Logout dan login ulang
- Clear browser cache/cookies
- Coba browser incognito/private

#### **Check 3: Cache**
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

#### **Check 4: Middleware**
Test route debug: http://127.0.0.1:8000/guru-bk/test-auth

---

## 🔍 **Debug Commands**

### **Check Users:**
```bash
php artisan tinker --execute="
\App\Models\User::all(['id', 'name', 'email', 'role'])->each(function(\$u) {
    echo \$u->id . ' - ' . \$u->name . ' (' . \$u->role->value . ')' . PHP_EOL;
});
"
```

### **Check Routes:**
```bash
php artisan route:list --path=guru-bk/popular-topics
```

### **Check Middleware:**
```bash
php artisan route:list --path=guru-bk/test-auth
```

---

## 📝 **Expected Behavior**

### **✅ Successful Flow:**
1. Login sebagai Guru BK → Dashboard
2. Klik menu "Topik Populer" → Index page dengan 8 topik
3. Klik "Tambah Topik" → Form create
4. Isi form dan submit → Redirect ke index dengan success message
5. Edit/Delete topik → Berfungsi normal

### **❌ Error Scenarios:**
- **403 Forbidden**: User tidak punya akses (role salah/tidak login)
- **404 Not Found**: Route tidak terdaftar
- **500 Server Error**: Ada bug di code

---

## 🚀 **Next Steps**

### **Jika masih error:**
1. **Screenshot error** yang muncul
2. **Check browser console** untuk JavaScript errors
3. **Check Laravel logs** di `storage/logs/laravel.log`
4. **Test dengan user Admin** untuk memastikan middleware bekerja

### **Jika sudah berhasil:**
1. Test semua CRUD operations
2. Test upload gambar
3. Test toggle status
4. Verify tampilan di landing page

---

## 📞 **Support**

Jika masih ada masalah, berikan informasi:
1. **Screenshot error**
2. **Browser yang digunakan**
3. **Steps yang sudah dicoba**
4. **Output dari debug commands**

---

**Happy Testing! 🎉**