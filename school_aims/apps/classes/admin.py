from django.contrib import admin
from .models import SchoolClass, ClassSchedule


@admin.register(SchoolClass)
class SchoolClassAdmin(admin.ModelAdmin):
    list_display = ('class_code', 'class_name', 'subject', 'teacher', 'grade_level', 'term', 'academic_year', 'status')
    list_filter = ('grade_level', 'term', 'academic_year', 'status', 'subject')
    search_fields = ('class_code', 'class_name')


@admin.register(ClassSchedule)
class ClassScheduleAdmin(admin.ModelAdmin):
    list_display = ('school_class', 'classroom', 'day_of_week', 'start_time', 'end_time', 'schedule_type', 'term')
    list_filter = ('day_of_week', 'schedule_type', 'term', 'year')
    search_fields = ('school_class__class_name', 'classroom__room_number')
