from django.shortcuts import render, get_object_or_404, redirect
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from django.utils import timezone
from .models import StudentRegistration, Attendance
from apps.classrooms.models import Classroom


@login_required
def registration_list(request):
    registrations = StudentRegistration.objects.select_related('student', 'school_class').all()
    return render(request, 'registrations/registration_list.html', {'registrations': registrations})


@login_required
def attendance_list(request):
    attendance_records = Attendance.objects.select_related('registration__student', 'registration__school_class').all()
    return render(request, 'registrations/attendance_list.html', {'attendance_records': attendance_records})


@login_required
def join_meeting(request, classroom_id):
    classroom = get_object_or_404(Classroom, id=classroom_id)
    
    # Auto-log virtual attendance if student
    if request.user.role == 'student' and hasattr(request.user, 'student_profile'):
        student_profile = request.user.student_profile
        today = timezone.localdate()
        current_time = timezone.localtime().time()

        reg = StudentRegistration.objects.filter(
            student=student_profile,
            school_class__schedules__classroom=classroom
        ).first()

        if reg:
            Attendance.objects.get_or_create(
                registration=reg,
                attendance_date=today,
                defaults={
                    'status': 'present',
                    'access_method': 'virtual',
                    'check_in_time': current_time
                }
            )

    if classroom.virtual_meeting_link:
        return redirect(classroom.virtual_meeting_link)
    else:
        messages.warning(request, "No virtual meeting link configured for this classroom.")
        return redirect('dashboard:home')
