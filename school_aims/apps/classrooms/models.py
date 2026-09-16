from django.db import models


class Classroom(models.Model):
    TYPE_CHOICES = [
        ('lecture', 'Lecture Hall'),
        ('lab', 'Laboratory'),
        ('tutorial', 'Tutorial Room'),
        ('seminar', 'Seminar Room'),
    ]

    building = models.CharField(max_length=100)
    room_number = models.CharField(max_length=20)
    capacity = models.IntegerField()
    classroom_type = models.CharField(max_length=20, choices=TYPE_CHOICES, default='lecture')
    virtual_meeting_link = models.CharField(max_length=500, blank=True, null=True)
    has_projector = models.BooleanField(default=False)
    has_whiteboard = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return f"{self.building} - Room {self.room_number} ({self.get_classroom_type_display()})"
