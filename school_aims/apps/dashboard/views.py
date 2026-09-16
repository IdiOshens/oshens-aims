from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from django.utils import timezone
from apps.users.models import User, StudentProfile, TeacherProfile
from apps.subjects.models import Subject
from apps.classes.models import SchoolClass, ClassSchedule
from apps.classrooms.models import Classroom
from apps.registrations.models import StudentRegistration, Attendance
from apps.notifications.models import Notification


def get_greeting(name):
    hour = timezone.localtime().hour
    if 5 <= hour < 12:
        greeting = "Good morning"
    elif 12 <= hour < 17:
        greeting = "Good afternoon"
    else:
        greeting = "Good evening"
    return f"{greeting}, {name}"


@login_required
def dashboard_home(request):
    user = request.user
    role = user.role
    name = user.get_full_name() or user.username
    greeting = get_greeting(name)
    
    # Get current day schedules
    current_day = timezone.localtime().strftime('%A')
    today = timezone.localdate()
    
    # Unread notifications
    notifications = Notification.objects.filter(user=user, is_read=False)[:5]

    context = {
        'greeting': greeting,
        'current_day': current_day,
        'notifications': notifications,
        'role': role,
    }

    if role == 'admin' or user.is_staff or user.is_superuser:
        context.update({
            'total_students': StudentProfile.objects.count(),
            'total_teachers': TeacherProfile.objects.count(),
            'total_classes': SchoolClass.objects.count(),
            'total_subjects': Subject.objects.count(),
            'total_classrooms': Classroom.objects.count(),
            'today_schedules': ClassSchedule.objects.filter(day_of_week=current_day).select_related('school_class', 'classroom')[:10],
            'recent_attendance': Attendance.objects.select_related('registration__student', 'registration__school_class').order_by('-id')[:10],
        })
        return render(request, 'dashboard/admin_dashboard.html', context)

    elif role == 'teacher':
        teacher = getattr(user, 'teacher_profile', None)
        teacher_classes = SchoolClass.objects.filter(teacher=teacher) if teacher else SchoolClass.objects.none()
        teacher_schedules = ClassSchedule.objects.filter(school_class__teacher=teacher, day_of_week=current_day).select_related('school_class', 'classroom') if teacher else ClassSchedule.objects.none()

        context.update({
            'teacher': teacher,
            'my_classes': teacher_classes,
            'today_schedules': teacher_schedules,
            'total_my_students': StudentRegistration.objects.filter(school_class__in=teacher_classes).count(),
        })
        return render(request, 'dashboard/teacher_dashboard.html', context)

    else:  # student
        student = getattr(user, 'student_profile', None)
        registrations = StudentRegistration.objects.filter(student=student).select_related('school_class') if student else StudentRegistration.objects.none()
        student_classes = [reg.school_class for reg in registrations]
        student_schedules = ClassSchedule.objects.filter(school_class__in=student_classes, day_of_week=current_day).select_related('school_class', 'classroom') if student else ClassSchedule.objects.none()

        context.update({
            'student': student,
            'registrations': registrations,
            'today_schedules': student_schedules,
        })
        return render(request, 'dashboard/student_dashboard.html', context)
