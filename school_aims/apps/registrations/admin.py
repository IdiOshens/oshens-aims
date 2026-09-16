from django.contrib import admin
from .models import StudentRegistration, Attendance


@admin.register(StudentRegistration)
class StudentRegistrationAdmin(admin.ModelAdmin):
    list_display = ('student', 'school_class', 'registration_date', 'status', 'grade')
    list_filter = ('status', 'school_class__grade_level', 'school_class__term')
    search_fields = ('student__first_name', 'student__last_name', 'school_class__class_name')


@admin.register(Attendance)
class AttendanceAdmin(admin.ModelAdmin):
    list_display = ('registration', 'attendance_date', 'status', 'access_method', 'check_in_time')
    list_filter = ('status', 'access_method', 'attendance_date')
    search_fields = ('registration__student__first_name', 'registration__student__last_name')
