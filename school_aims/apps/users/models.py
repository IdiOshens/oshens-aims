from django.db import models
from django.contrib.auth.models import AbstractUser, BaseUserManager

class CustomUserManager(BaseUserManager):
    def create_user(self, email, password=None, **extra_fields):
        if not email:
            raise ValueError('Email is required')
        email = self.normalize_email(email)
        user = self.model(email=email, **extra_fields)
        user.set_password(password)
        user.save()
        return user

    def create_superuser(self, email, password=None, **extra_fields):
        extra_fields.setdefault('is_staff', True)
        extra_fields.setdefault('is_superuser', True)
        extra_fields.setdefault('role', 'admin')
        return self.create_user(email, password, **extra_fields)

class User(AbstractUser):
    ROLE_CHOICES = (
        ('admin', 'Admin'),
        ('teacher', 'Teacher'),
        ('student', 'Student'),
        ('parent', 'Parent'),
    )
    username = None
    email = models.EmailField(unique=True)
    role = models.CharField(max_length=20, choices=ROLE_CHOICES, default='student')
    role_id = models.IntegerField(null=True, blank=True)
    phone = models.CharField(max_length=20, blank=True)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    USERNAME_FIELD = 'email'
    REQUIRED_FIELDS = []
    objects = CustomUserManager()

    @property
    def username(self):
        return self.email

    def __str__(self):
        return self.email

    def save(self, *args, **kwargs):
        is_new = self.pk is None
        super().save(*args, **kwargs)
        if not self.role_id:
            if self.role == 'student':
                student, _ = Student.objects.get_or_create(
                    user=self,
                    defaults={
                        'first_name': self.first_name,
                        'last_name': self.last_name,
                        'email': self.email,
                        'admission_number': f"STU{Student.objects.count() + 1:04d}"
                    }
                )
                self.role_id = student.student_id
                User.objects.filter(pk=self.pk).update(role_id=self.role_id)
            elif self.role == 'teacher':
                teacher, _ = Teacher.objects.get_or_create(
                    user=self,
                    defaults={
                        'first_name': self.first_name,
                        'last_name': self.last_name,
                        'email': self.email,
                        'staff_number': f"TCH{Teacher.objects.count() + 1:04d}"
                    }
                )
                self.role_id = teacher.teacher_id
                User.objects.filter(pk=self.pk).update(role_id=self.role_id)
            elif self.role == 'admin':
                name_val = f"{self.first_name} {self.last_name}".strip() or self.email
                admin_obj, _ = Admin.objects.get_or_create(
                    user=self,
                    defaults={
                        'name': name_val,
                        'email': self.email,
                        'staff_id': f"ADM{Admin.objects.count() + 1:04d}"
                    }
                )
                self.role_id = admin_obj.admin_id
                User.objects.filter(pk=self.pk).update(role_id=self.role_id)

    def get_role_object(self):
        if self.role == 'student':
            return Student.objects.filter(student_id=self.role_id).first()
        elif self.role == 'teacher':
            return Teacher.objects.filter(teacher_id=self.role_id).first()
        elif self.role == 'admin':
            return Admin.objects.filter(admin_id=self.role_id).first()
        return None

class Student(models.Model):
    GENDER_CHOICES = (
        ('Male', 'Male'),
        ('Female', 'Female'),
    )
    user = models.OneToOneField(User, on_delete=models.CASCADE, related_name='student_profile')
    student_id = models.AutoField(primary_key=True)
    admission_number = models.CharField(max_length=50, unique=True)
    first_name = models.CharField(max_length=50)
    last_name = models.CharField(max_length=50)
    email = models.EmailField(unique=True)
    date_of_birth = models.DateField(null=True, blank=True)
    gender = models.CharField(max_length=10, choices=GENDER_CHOICES, blank=True)
    phone = models.CharField(max_length=20, blank=True)
    address = models.TextField(blank=True)
    current_class = models.CharField(max_length=2, blank=True)  # Form 1-4
    guardian_name = models.CharField(max_length=100, blank=True)
    guardian_phone = models.CharField(max_length=20, blank=True)
    enrollment_date = models.DateField(auto_now_add=True)
    status = models.CharField(max_length=20, default='active')
    created_at = models.DateTimeField(auto_now_add=True)

    @property
    def full_name(self):
        return f"{self.first_name} {self.last_name}"

    def __str__(self):
        return f"{self.first_name} {self.last_name} ({self.admission_number})"

class Teacher(models.Model):
    GENDER_CHOICES = (
        ('Male', 'Male'),
        ('Female', 'Female'),
    )
    user = models.OneToOneField(User, on_delete=models.CASCADE, related_name='teacher_profile')
    teacher_id = models.AutoField(primary_key=True)
    staff_number = models.CharField(max_length=50, unique=True)
    first_name = models.CharField(max_length=50)
    last_name = models.CharField(max_length=50)
    email = models.EmailField(unique=True)
    phone = models.CharField(max_length=20, blank=True)
    gender = models.CharField(max_length=10, choices=GENDER_CHOICES, blank=True)
    hire_date = models.DateField(null=True, blank=True)
    qualification = models.CharField(max_length=255, blank=True)
    specialization = models.CharField(max_length=255, blank=True)
    status = models.CharField(max_length=20, default='active')
    created_at = models.DateTimeField(auto_now_add=True)

    @property
    def full_name(self):
        return f"{self.first_name} {self.last_name}"

    def __str__(self):
        return f"{self.first_name} {self.last_name} ({self.staff_number})"

class Admin(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE, related_name='admin_profile')
    admin_id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=100)
    email = models.EmailField(unique=True)
    staff_id = models.CharField(max_length=50, unique=True)
    position = models.CharField(max_length=100, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return self.name


# Compatibility aliases
StudentProfile = Student
TeacherProfile = Teacher
AdminProfile = Admin

