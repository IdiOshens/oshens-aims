from django.contrib import admin
from .models import Classroom


@admin.register(Classroom)
class ClassroomAdmin(admin.ModelAdmin):
    list_display = ('building', 'room_number', 'capacity', 'classroom_type', 'has_projector', 'has_whiteboard')
    list_filter = ('classroom_type', 'has_projector', 'has_whiteboard', 'building')
    search_fields = ('building', 'room_number')
