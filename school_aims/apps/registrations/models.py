from django.db import models


class StudentRegistration(models.Model):
    STATUS_CHOICES = [
        ('active', 'Active'),
        ('dropped', 'Dropped'),
        ('completed', 'Completed'),
    ]

    student = models.ForeignKey('users.Student', on_delete=models.CASCADE, related_name='registrations')
    school_class = models.ForeignKey('classes.SchoolClass', on_delete=models.CASCADE, related_name='registrations')
    registration_date = models.DateField(auto_now_add=True)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='active')
    grade = models.CharField(max_length=5, blank=True, null=True, help_text="A, B+, C, etc.")
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        unique_together = ('student', 'school_class')
        verbose_name = "Class Registration"
        verbose_name_plural = "Class Registrations"

    def __str__(self):
        return f"{self.student.full_name} -> {self.school_class.class_name}"


class Attendance(models.Model):
    STATUS_CHOICES = [
        ('present', 'Present'),
        ('absent', 'Absent'),
        ('late', 'Late'),
        ('excused', 'Excused'),
    ]
    ACCESS_METHOD_CHOICES = [
        ('physical', 'Physical (Classroom)'),
        ('virtual', 'Virtual (Online Meeting)'),
        ('qr', 'QR Code Scan'),
        ('manual', 'Manual Entry'),
    ]

    registration = models.ForeignKey(StudentRegistration, on_delete=models.CASCADE, related_name='attendance_records')
    attendance_date = models.DateField()
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='absent')
    access_method = models.CharField(max_length=20, choices=ACCESS_METHOD_CHOICES, default='physical')
    check_in_time = models.TimeField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        unique_together = ('registration', 'attendance_date')
        verbose_name = "Attendance Record"
        verbose_name_plural = "Attendance Records"

    def __str__(self):
        return f"{self.registration.student.full_name} | {self.attendance_date} ({self.get_status_display()})"
