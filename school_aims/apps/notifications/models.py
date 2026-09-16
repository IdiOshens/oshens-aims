from django.db import models
from django.conf import settings


class Notification(models.Model):
    TYPE_CHOICES = [
        ('attendance', 'Attendance'),
        ('schedule', 'Schedule'),
        ('enrollment', 'Registration / Enrollment'),
        ('system', 'System'),
        ('alert', 'Alert'),
        ('lecture', 'Live Lecture / Class'),
    ]

    user = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='notifications')
    message = models.TextField()
    related_id = models.IntegerField(null=True, blank=True)
    type = models.CharField(max_length=30, choices=TYPE_CHOICES, default='system')
    is_read = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ['-created_at']

    def __str__(self):
        return f"{self.user.email}: {self.message[:30]}..."
