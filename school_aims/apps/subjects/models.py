from django.db import models


class Subject(models.Model):
    subject_code = models.CharField(max_length=20, unique=True)
    subject_name = models.CharField(max_length=100, unique=True)
    head_teacher = models.ForeignKey(
        'users.Teacher', 
        on_delete=models.SET_NULL, 
        null=True, 
        blank=True, 
        related_name='headed_subjects'
    )
    description = models.TextField(blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return f"{self.subject_code} - {self.subject_name}"
