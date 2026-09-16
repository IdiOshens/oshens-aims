from django.db import models


class SchoolClass(models.Model):
    TERM_CHOICES = [
        ('1', 'Term 1'),
        ('2', 'Term 2'),
        ('3', 'Term 3'),
    ]
    STATUS_CHOICES = [
        ('active', 'Active'),
        ('inactive', 'Inactive'),
    ]

    class_code = models.CharField(max_length=20, unique=True)
    class_name = models.CharField(max_length=100)
    subject = models.ForeignKey('subjects.Subject', on_delete=models.CASCADE, related_name='classes')
    teacher = models.ForeignKey('users.Teacher', on_delete=models.SET_NULL, null=True, blank=True, related_name='classes')
    grade_level = models.IntegerField(default=1, help_text="Form 1-4 or Grade 1-12")
    term = models.CharField(max_length=5, choices=TERM_CHOICES, default='1')
    academic_year = models.IntegerField(default=2026)
    description = models.TextField(blank=True, null=True)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='active')
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name = "Class / Course"
        verbose_name_plural = "Classes / Courses"

    def __str__(self):
        return f"{self.class_code} - {self.class_name}"


class ClassSchedule(models.Model):
    DAY_CHOICES = [
        ('Monday', 'Monday'),
        ('Tuesday', 'Tuesday'),
        ('Wednesday', 'Wednesday'),
        ('Thursday', 'Thursday'),
        ('Friday', 'Friday'),
        ('Saturday', 'Saturday'),
        ('Sunday', 'Sunday'),
    ]
    SCHEDULE_TYPE_CHOICES = [
        ('physical', 'Physical'),
        ('virtual', 'Virtual'),
        ('hybrid', 'Hybrid'),
    ]
    TERM_CHOICES = [
        ('1', 'Term 1'),
        ('2', 'Term 2'),
        ('3', 'Term 3'),
    ]

    school_class = models.ForeignKey(SchoolClass, on_delete=models.CASCADE, related_name='schedules')
    classroom = models.ForeignKey('classrooms.Classroom', on_delete=models.CASCADE, related_name='schedules')
    day_of_week = models.CharField(max_length=15, choices=DAY_CHOICES)
    start_time = models.TimeField()
    end_time = models.TimeField()
    term = models.CharField(max_length=5, choices=TERM_CHOICES, default='1')
    year = models.IntegerField(default=2026)
    schedule_type = models.CharField(max_length=20, choices=SCHEDULE_TYPE_CHOICES, default='physical')
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ['day_of_week', 'start_time']

    def __str__(self):
        return f"{self.school_class.class_name} | {self.day_of_week} ({self.start_time.strftime('%H:%M')} - {self.end_time.strftime('%H:%M')})"
