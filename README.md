# 🏥 Doctor Management System

![Laravel](https://img.shields.io/badge/Laravel-10-red.svg?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.1-blue.svg?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)

## 🚀 About the Project
**Doctor Management System** is a web-based platform built using Laravel that allows patients to book appointments with doctors, doctors will manage the appountments, and make online/offline payments. Doctors can access patient records, view upcoming appointments,  reschedule the apponintments and mark offline payments. Admins can oversee the system and appointments, and monitor activities.

---

## 🏆 Key Features

### **🩺 For Patients**
✅ Register & Login  
✅ Book Appointments Online  
✅ View & Cancel Appointments  
✅ Online Payment via Razorpay    
✅ View and Download Invoices    
✅ View and Download Prescriptions    

### **👨‍⚕️ For Doctors**
✅ View & Manage Appointments  
✅ Access Patient Details  
✅ Approve or Cancel Appointments  
✅ Mark Offline Payments  
✅ View Payment Summary  
✅ View and Download Invoices  
✅ Manage Prescriptions  

### **🛠️ For Admin**
✅ Manage Doctors Specialty  
✅ Manage Doctors & Patients  
✅ View All Appointments and Take Appropriate Action  
✅ Mark Offline Payments 
✅ View and Download Invoices  

---

## 🛠️ Technology Stack

- **Backend:** Laravel 9 (PHP 8.0)  
- **Database:** MySQL 8.0  
- **Frontend:** Blade Templates, Bootstrap, Javascript, Jquery
- **Payment Gateway:** Razorpay  
- **Authentication:** Laravel UI  

---

## 🔧 Installation & Setup

### **Step 1: Clone Repository**
```sh
git clone https://github.com/har9721/doctor-appointment-system.git
cd doctor-management-system
```

### **Step 2: Install Dependencies**
```sh
composer install
npm install
npm run dev
```

### **Step 3:Configure Environment**
```sh
cp .env.example .env
php artisan key:generate
```

Edit **.env** and configure your database and razorpay payment gateway credentials.

### **Step 4:Run Migrations & Seed Data**
```sh
php artisan migrate --seed
```

### **Step 5:Start Development Server**
```sh
php artisan serve
```

---

### 📌 Usage

1️⃣ **Login Credentials**  
👉 Admin: admin@yopmail.com / 12345678.  
👉 Doctor: create a new doctor from admin login and default password is 12345678.  
👉 Patient: register as a new patient and default password is 12345678.  

2️⃣ **Booking Appointment**  
👉 Patients log in, apply filters according to their needs, and book an appointment.  
👉 They can select a date and an available time slot.  
👉 Patient and Doctor both will receive an email.  

3️⃣ **Payment System**  
👉 Patients can pay the fees online via Razorpay.  
👉 Admin and Doctors can mark offline payments as completed.  
👉 Patients will receive the invoice on email.  
👉 Patients will receive an email reminder if they haven't paid.  

4️⃣ **Appointment Management**  
👉 Doctors can approve/cancel/reschedule the appointment.  
👉 Admin can also approve/cancel/reschedule the appointment in the absence of doctors.  
👉 Patients can cancel appointments before confirmation.  

5️⃣ **Viewing Medical History**  
👉 Doctors can access patient details.  

---

## 🔮 Upcoming Features

🚀 **Maintain Patient Medical History –**  
Store and track past diagnoses, treatments, and reports.  

🚀 **Automated Appointment Reminders –**  
Patients will receive email reminders before their appointments.  

🚀 **Doctor Availability Calendar –**  
Patients can view real-time doctor availability before booking an appointment.  

🚀 **Admin Dashboard Enhancements –**  
Graphs and reports for better system insights.  

---

## 📸 Screenshots

[📸 View Full Screenshots](screenshots.md)

---

### 📜 License
This project is licensed under the MIT License.

### 🔗 Contact
👤 Harshal Chaudhary
📧 Email: harshalchaudhary21@gmail.com
🔗 Discord: harshal_21_11930

