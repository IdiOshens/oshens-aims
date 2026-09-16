from django.contrib import admin
from django.contrib.auth.admin import UserAdmin
from .models import User, Student, Teacher, Admin

class CustomUserAdmin(UserAdmin):
    list_display = ('email', 'role', 'is_active', 'created_at')
    list_filter = ('role', 'is_active')
    search_fields = ('email', 'first_name', 'last_name')
    ordering = ('-created_at',)
    fieldsets = (
        (None, {'fields': ('email', 'password')}),
        ('Personal info', {'fields': ('first_name', 'last_name', 'phone')}),
        ('Permissions', {'fields': ('role', 'is_active', 'is_staff', 'is_superuser', 'groups', 'user_permissions')}),
        ('Important dates', {'fields': ('created_at',)}),
    )
    readonly_fields = ('created_at',)

admin.site.register(User, CustomUserAdmin)

@admin.register(Student)
class StudentAdmin(admin.ModelAdmin):
    list_display = ('admission_number', 'first_name', 'last_name', 'current_class', 'status')
    list_filter = ('current_class', 'status', 'gender')
    search_fields = ('admission_number', 'first_name', 'last_name', 'email')
    ordering = ('-created_at',)

@admin.register(Teacher)
class TeacherAdmin(admin.ModelAdmin):
    list_display = ('staff_number', 'first_name', 'last_name', 'status')
    list_filter = ('status', 'gender')
    search_fields = ('staff_number', 'first_name', 'last_name', 'email')
    ordering = ('-created_at',)

@admin.register(Admin)
class AdminAdmin(admin.ModelAdmin):
    list_display = ('staff_id', 'name', 'email')
    search_fields = ('staff_id', 'name', 'email')
    ordering = ('-created_at',)
